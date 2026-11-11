<?php

// app/Http/Controllers/StaffController.php
namespace App\Http\Controllers;

use App\Models\EmploymentApplication;
use App\Models\StaffProfile;
use App\Models\StaffDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Marketer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Staff\StaffCredentialService;
use App\Services\Staff\StaffDepartments;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Enums\AnalyticsPeriod;
use Throwable;


class StaffController extends Controller
{
    public function __construct(
        private readonly StaffDepartments $departments,
        private readonly StaffCredentialService $credentials,
    ) {}

    // Show all staff
    public function index()
    {
        $staff = StaffProfile::with('user', 'employmentApplication')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.index', compact('staff'));
    }

    // Create staff from application
    public function createFromApplication($applicationId)
    {
        $application = EmploymentApplication::findOrFail($applicationId);
        $departments = $this->departments->options();

        return view('staff.create-from-application', compact('application', 'departments'));
    }

    // Add this method to StaffController.php
    public function getDepartmentCount($department)
    {
        $count = StaffProfile::where('department', $department)->count();
        return response()->json(['count' => $count]);
    }
    // Store new staff
    // Update the store method in StaffController.php
    public function store(Request $request)
    {
        $request->validate([
            'employment_application_id' => 'required|exists:employment_applications,id',
            'department' => 'required|string',
            'designation' => 'required|string',
            'date_of_employment' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,probation',
            'salary' => 'nullable|numeric',
            'email' => 'required|email',
            // Left blank, a password is generated and emailed
            'password' => 'nullable|min:8',
            'other_department' => 'required_if:department,' . StaffDepartments::OTHER . '|nullable|string|max:255',
        ]);

        $existing = User::with('staffProfile')->where('email', $request->email)->first();

        // Only plain customers or former staff can be converted, anyone else needs a different email
        if ($existing && ! $this->canBecomeStaff($existing)) {
            return back()->withInput()->withErrors([
                'email' => 'This email already belongs to another account. Use a different login email for this staff member.',
            ]);
        }



        DB::beginTransaction();

        try {

            $department = $this->departments->resolve($request->department, $request->other_department);

            $plainPassword = $request->filled('password')
                ? $request->password
                : $this->credentials->generatePassword();

            $application = EmploymentApplication::findOrFail(
                $request->employment_application_id
            );

            // Create user
            $user = $existing ?? new User();
            $user->forceFill([
                'first_name' => $request->first_name ?? $application->first_name,
                'last_name'  => $request->surname ?? $application->surname,
                'phone'      => $application->contact_number,
                'email'      => $request->email,
                'user_type'  => 'staff',
                'password'   => Hash::make($plainPassword),
            //    'role'       => $request->role ?? 'staff',
            ])->save();

            // Generate staff ID
            $staffId = $this->nextStaffId();

            // Create staff profile
            $staffData = [
                'user_id' => $user->id,
                'employment_application_id' => $request->employment_application_id,
                'staff_id' => $staffId,
                'department' => $department,
                'designation' => $request->designation,
                'date_of_employment' => $request->date_of_employment,
                'employment_type' => $request->employment_type,
                'salary' => $request->salary,
                'status' => 'active',
            ];

            if ($request->filled('supervisor_id')) {
                $staffData['supervisor_id'] = $request->supervisor_id;
            }

            if ($request->filled('work_location')) {
                $staffData['work_location'] = $request->work_location;
            }

            if ($request->filled('work_schedule')) {
                $staffData['work_schedule'] = $request->work_schedule;
            }

            if ($request->filled('probation_period')) {
                $staffData['probation_period'] = $request->probation_period;
            }

            if ($request->filled('notes')) {
                $staffData['notes'] = $request->notes;
            }

            $staff = StaffProfile::create($staffData);

            // File uploads
            if ($request->hasFile('offer_letter')) {
                $this->uploadStaffDocument(
                    $staff,
                    $request->file('offer_letter'),
                    'contract',
                    'Offer Letter'
                );
            }

            if ($request->hasFile('contract')) {
                $this->uploadStaffDocument(
                    $staff,
                    $request->file('contract'),
                    'contract',
                    'Employment Contract'
                );
            }

            // Update application
            $application->update(['status' => 'hired']);

            DB::commit();

        Log::info($request->input('email'));


            // Send email AFTER commit so a mail failure never undoes a created account
            return $this->redirectAfterCreate($user, $plainPassword);
        } catch (\Exception $e) {

           Log::info($e);

            DB::rollBack();

            return back()->withInput()->with(
                'error',
                'Something went wrong: ' . $e->getMessage()
            );
        }
    }

    // Deleted profiles still hold their staff ID, so count them too or a new hire could reuse an ID
    private function nextStaffId(): string
    {
        $number = StaffProfile::withTrashed()->count() + 1;

        do {
            $staffId = 'STAFF' . date('Ym') . str_pad($number++, 4, '0', STR_PAD_LEFT);
        } while (StaffProfile::withTrashed()->where('staff_id', $staffId)->exists());

        return $staffId;
    }

    private function canBecomeStaff(User $user): bool
    {
        $hasActiveStaffProfile = $user->staffProfile !== null;

        return ! $hasActiveStaffProfile
            && in_array($user->user_type, ['customer', 'staff'], true);
    }

    // Helper method for document upload
    private function uploadStaffDocument($staff, $file, $type, $title)
    {
        $directory = public_path('staff-documents/' . $staff->staff_id);

        // Create directory if it does not exist
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generate unique filename to avoid overwrite
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Move file to public folder
        $file->move($directory, $fileName);

        $filePath = 'staff-documents/' . $staff->staff_id . '/' . $fileName;

        StaffDocument::create([
            'staff_profile_id' => $staff->id,
            'document_type' => $type,
            'title' => $title,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
    }


    public function updateFromApplication(Request $request, $id)
    {
        $request->validate([
            'employment_application_id' => 'required|exists:employment_applications,id',
            'department' => 'required|string',
            'designation' => 'required|string',
            'date_of_employment' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,probation',
            'salary' => 'nullable|numeric',
            'email' => 'required|email|unique:users,email,' . $request->user_id, // Use user_id from request
            'password' => 'nullable|min:8', // Password is optional on update
            'other_department' => 'required_if:department,' . StaffDepartments::OTHER . '|nullable|string|max:255',
        ]);

        // Find existing staff profile
        $staff = StaffProfile::with('user')->findOrFail($id);

        $department = $this->departments->resolve($request->department, $request->other_department);

        $application = EmploymentApplication::findOrFail(
            $request->employment_application_id
        );

        // Update user account - only update fields that are provided
        $userData = [
            'first_name' => $request->first_name ?? $staff->user->first_name,
            'last_name' => $request->surname ?? $staff->user->last_name,
            'email' => $request->email,
            'role' => $request->role ?? $staff->user->role ?? 'staff',
        ];

        // Only update phone if application exists
        if ($application) {
            $userData['phone'] = $application->contact_number;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update the user
        $staff->user->update($userData);

        // Update staff profile
        $staffData = [
            'employment_application_id' => $request->employment_application_id,
            'department' => $department,
            'designation' => $request->designation,
            'date_of_employment' => $request->date_of_employment,
            'employment_type' => $request->employment_type,
            'salary' => $request->salary,
        ];

        // Add optional fields if provided
        if ($request->filled('supervisor_id')) {
            $staffData['supervisor_id'] = $request->supervisor_id;
        }

        if ($request->filled('work_location')) {
            $staffData['work_location'] = $request->work_location;
        }

        if ($request->filled('work_schedule')) {
            $staffData['work_schedule'] = $request->work_schedule;
        }

        if ($request->filled('probation_period')) {
            $staffData['probation_period'] = $request->probation_period;
        }

        if ($request->filled('notes')) {
            $staffData['notes'] = $request->notes;
        }

        $staff->update($staffData);

        // Handle file uploads (if new files are uploaded)
        if ($request->hasFile('offer_letter')) {
            // Delete old file if exists
            // $this->deleteOldDocument($staff, 'offer_letter');
            $this->uploadStaffDocument($staff, $request->file('offer_letter'), 'offer_letter', 'Offer Letter');
        }

        if ($request->hasFile('contract')) {
            // Delete old file if exists
            // $this->deleteOldDocument($staff, 'contract');
            $this->uploadStaffDocument($staff, $request->file('contract'), 'contract', 'Employment Contract');
        }


        return redirect(url('/admin/employment/applications/' . $request->employment_application_id))
            ->with('success', 'Staff member updated successfully!');

        // return redirect()->route('admin.staff.index')
        //     ->with('success', 'Staff member updated successfully!');
    }

    // Helper method to delete old documents
    private function deleteOldDocument($staff, $documentType)
    {
        // You'll need to implement this based on how you store documents
        // This is just an example - adjust according to your document storage system
        if ($staff->documents()->where('type', $documentType)->exists()) {
            $oldDocument = $staff->documents()->where('type', $documentType)->first();
            // Delete file from storage
            if (Storage::exists($oldDocument->file_path)) {
                Storage::delete($oldDocument->file_path);
            }
            // Delete database record
            $oldDocument->delete();
        }
    }


    public function editFromApplication($id)
    {
        // Fetch the application
        $application = EmploymentApplication::findOrFail($id);

        // Fetch the staff profile linked to this application
        $staff = StaffProfile::with('user')
            ->where('employment_application_id', $id)
            ->first();

        $departments = $this->departments->options();

        $supervisors = StaffProfile::with('user')
            ->where('status', 'active')
            ->get();

        return view('staff.edit-from-application', compact('staff', 'application', 'departments', 'supervisors'));
    }

    // Show staff profile
    public function show($id)
    {
        $staff = StaffProfile::with('user', 'employmentApplication')->findOrFail($id);
        return view('staff.show', compact('staff'));
    }

    // Edit staff
    public function edit($id)
    {
        $staff = StaffProfile::findOrFail($id);
        $departments = $this->departments->options();

        return view('staff.edit', compact('staff', 'departments'));
    }

    // Update staff
    public function update(Request $request, $id)
    {
        $staff = StaffProfile::findOrFail($id);

        $data = $request->validate([
            'department' => 'required|string',
            'other_department' => 'required_if:department,' . StaffDepartments::OTHER . '|nullable|string|max:255',
            'designation' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,probation',
            'salary' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'status' => 'required|in:active,suspended,terminated,on_leave',
        ]);

        $data['department'] = $this->departments->resolve($data['department'], $data['other_department'] ?? null);

        // Only validated fields are saved, so extra posted fields can never overwrite other columns
        $staff->update(collect($data)->except('other_department')->all());

        return redirect()->route('admin.staff.show', $id)->with('success', 'Staff profile updated!');
    }



    // Delete a staff member: the profile is soft deleted so attendance, leave and documents are kept,
    // and the login is turned back into a plain customer account so the staff portal is closed to them
    public function destroy($id)
    {
        $staff = StaffProfile::with('user')->findOrFail($id);
        $user = $staff->user;

        if ($user && $user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user?->full_name ?: $staff->staff_id;

        DB::transaction(function () use ($staff, $user) {
            if ($user && $user->user_type === 'staff') {
                $user->forceFill(['user_type' => 'customer'])->save();
            }

            $staff->delete();
        });

        $this->forgetAnalyticsCache();

        return redirect()->route('admin.staff.index')
            ->with('success', "{$name} was deleted from staff.");
    }

    // The analytics report is cached for a few minutes, so drop it to show the new staff total at once
    private function forgetAnalyticsCache(): void
    {
        foreach (AnalyticsPeriod::cases() as $period) {
            Cache::forget("admin.analytics.{$period->value}");
        }
    }

    // Emails the new login details, a mail failure still counts as a created account with a clear warning
    private function redirectAfterCreate(User $user, string $plainPassword)
    {
        try {
            $this->credentials->sendLoginDetails($user, $plainPassword);
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('admin.staff.index')
                ->with('warning', 'Staff member added, but the login email could not be sent. Open the profile and use Regenerate Password to resend it.');
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member added successfully! Login details sent to ' . $user->email);
    }
}

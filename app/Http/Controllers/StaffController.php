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


class StaffController extends Controller
{
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
        $departments = ['HR', 'IT', 'Finance', 'Operations', 'Marketing', 'Sales', 'Editor', 'Reporter'];
        
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
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
    ]);

    DB::beginTransaction();

    try {

        // Handle "other" department
        $department = $request->department;
        if ($request->department === 'other' && $request->filled('other_department')) {
            $department = $request->other_department;
        }

        $application = EmploymentApplication::findOrFail(
            $request->employment_application_id
        );

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->surname,
            'phone' => $application->contact_number,
            'email' => $request->email,
            'user_type' => 'marketer',
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'staff',
        ]);

        // Generate staff ID
        $staffId = 'STAFF' . date('Ym') . str_pad(
            StaffProfile::count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        // Create marketer
        $marketer = Marketer::create([
            'user_id' => $user->id,
            'referral_code' => Str::upper(Str::random(8)),
        ]);

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

        // Send email AFTER commit (important)
        if (!empty($application->email)) {
            $this->sendWelcomeEmail(
                $application->email,
                $request->password,
                $staff
            );
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully! Login credentials sent to ' . $request->email);

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->withInput()->with(
            'error',
            'Something went wrong: ' . $e->getMessage()
        );
    }
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
    ]);

    // Find existing staff profile
    $staff = StaffProfile::with('user')->findOrFail($id);
    
    // Handle "other" department
    $department = $request->department;
    if ($request->department === 'other' && $request->filled('other_department')) {
        $department = $request->other_department;
    }
    
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
    
        $departments = ['HR', 'IT', 'Finance', 'Marketing', 'Operations', 'Sales', 'Admin'];
    
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
        $departments = ['HR', 'IT', 'Finance', 'Operations', 'Marketing', 'Sales'];
        
        return view('staff.edit', compact('staff', 'departments'));
    }

    // Update staff
    public function update(Request $request, $id)
    {
        $staff = StaffProfile::findOrFail($id);
        
        $request->validate([
            'department' => 'required|string',
            'designation' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract',
            'salary' => 'nullable|numeric',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'status' => 'required|in:active,suspended,terminated,on_leave',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.show', $id)->with('success', 'Staff profile updated!');
    }
    
   
    
    protected function sendWelcomeEmail($email, $plainPassword, $staff)
{
    try {
        // Compute full name from related user
        $fullName = $staff->user->first_name . ' ' . $staff->user->last_name;

        Mail::send('emails.staff-welcome', [
            'user'     => $email,
            'password' => $plainPassword,
            'staff'    => $staff,
            'full_name'=> $fullName, // pass to Blade
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Welcome to the Team 🎉');
        });

        return true;

    } catch (\Throwable $e) {
        // Show error immediately
        throw $e;
    }
}

    


}




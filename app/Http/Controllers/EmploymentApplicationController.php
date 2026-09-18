<?php
// app/Http/Controllers/EmploymentApplicationController.php

namespace App\Http\Controllers;

use App\Models\EmploymentApplication;
use App\Mail\EmploymentApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\StaffProfile;

class EmploymentApplicationController extends Controller
{
    /**
     * Display employment application form
     */
    public function create()
    {
        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 
            'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 
            'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo', 'Jigawa', 
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 
            'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 
            'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
        ];

        return view('employment.create', compact('states'));
    }

    /**
     * Store employment application
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Data
            'position_applying_for' => 'required|string|max:255',
            'surname' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'other_name' => 'nullable|string|max:100',
            'email' => 'required',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:-18 years',
            'state_of_origin' => 'required|string|max:100',
            'lga' => 'required|string|max:100',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'educational_qualification' => 'required|string|max:255',
            'residential_address' => 'required|string|max:500',
            'residential_lga_state' => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            
            // Next of Kin
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_relationship' => 'required|string|max:100',
            'next_of_kin_contact' => 'required|string|max:20',
            'next_of_kin_address' => 'required|string|max:500',
            'parent_guardian_name' => 'nullable|string|max:255',
            'parent_guardian_address' => 'nullable|string|max:500',
            
            // Declaration
            'declaration_signature' => 'required|string',
            'declaration_date' => 'required|date',
            
            // Educational Qualifications (dynamic array)
            'educational_institution.*' => 'nullable|string|max:255',
            'educational_qualification_type.*' => 'nullable|string|max:255',
            'educational_date_obtained.*' => 'nullable|date',
            
            // Professional Qualifications
            'professional_body.*' => 'nullable|string|max:255',
            'professional_membership_grade.*' => 'nullable|string|max:100',
            'professional_year_obtained.*' => 'nullable|date',
            
            // Employment History
            'employment_organization.*' => 'nullable|string|max:255',
            'employment_address.*' => 'nullable|string|max:500',
            'employment_phone.*' => 'nullable|string|max:20',
            'employment_position.*' => 'nullable|string|max:255',
            'employment_from_date.*' => 'nullable|date',
            'employment_to_date.*' => 'nullable|date',
            
            // Document Uploads
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg',
            'resume' => 'required|file|mimes:pdf,doc,docx',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|mimes:pdf,jpeg,png,jpg',
            'means_of_identification' => 'required|array|min:1',
            'means_of_identification.*' => 'file|mimes:pdf,jpeg,png,jpg',
        ], 
        [
            'date_of_birth.before' => 'You must be at least 18 years old to apply.',
            'certificates.min' => 'Please upload at least one certificate.',
            'means_of_identification.min' => 'Please upload at least one means of identification.',
            'declaration_signature.required' => 'Please provide your signature.',
        ]);

        if ($validator->fails()) {
        
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create necessary directories if they don't exist
            $directories = [
                'signatures',
                'passport_photos',
                'resumes',
                'certificates',
                'identifications'
            ];
            
            foreach ($directories as $directory) {
                $path = public_path('uploads/' . $directory);
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }
            }
            
            
            // Handle Signature Upload
            $signaturePath = null;

            if ($request->filled('declaration_signature')) {
                $signatureData = $request->declaration_signature;
            
                if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $matches)) {
                    $extension = $matches[1]; // png, jpg, etc
                    $data = substr($signatureData, strpos($signatureData, ',') + 1);
                    $data = base64_decode($data);
            
                    if ($data === false) {
                        throw new \Exception('Base64 decode failed');
                    }
            
                    // Create directory if not exists
                    $directory = public_path('uploads/signatures');
                    if (!File::exists($directory)) {
                        File::makeDirectory($directory, 0755, true);
                    }
            
                    // Sanitize filename
                    $filename = strtolower(preg_replace('/\s+/', '_', $request->first_name . '_' . $request->surname))
                                . '_signature_' . time() . '_' . uniqid() . '.' . $extension;
            
                    $filePath = $directory . '/' . $filename;
            
                    // Save file
                    file_put_contents($filePath, $data);
                
                    
                
            
                    $signaturePath = 'uploads/signatures/' . $filename;
                 
                } else {
                    // Non-base64 fallback (rare)
                    $signaturePath = $signatureData;
                   
                }
            } else {
                $signaturePath = null;
            }

            // Handle Passport Photo Upload
            $passportPhotoPath = null;
            if ($request->hasFile('passport_photo')) {
                $file = $request->file('passport_photo');
                $filename = 'passport_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/passport_photos'), $filename);
                $passportPhotoPath = 'uploads/passport_photos/' . $filename;
          
            }
            
            // Handle Resume/CV Upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $filename = 'resume_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/resumes'), $filename);
                $resumePath = 'uploads/resumes/' . $filename;
           
            }
            
            // Handle Educational/Professional Certificates (Multiple Files)
            $certificatePaths = [];
            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $file) {
                    $filename = 'certificate_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/certificates'), $filename);
                    $certificatePaths[] = 'uploads/certificates/' . $filename;
                }
            }
            

            // Handle Means of Identification (Multiple Files)
            $identificationPaths = [];
            if ($request->hasFile('means_of_identification')) {
                foreach ($request->file('means_of_identification') as $file) {
                    $filename = 'id_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/identifications'), $filename);
                    $identificationPaths[] = 'uploads/identifications/' . $filename;
                }
            }
            
            // Prepare educational qualifications array
            $educationalQualifications = [];
            if ($request->educational_institution) {
                foreach ($request->educational_institution as $index => $institution) {
                    if ($institution) {
                        $educationalQualifications[] = [
                            'institution' => $institution,
                            'qualification' => $request->educational_qualification_type[$index] ?? '',
                            'date_obtained' => $request->educational_date_obtained[$index] ?? null,
                        ];
                    }
                }
            }
            
            // Prepare professional qualifications array
            $professionalQualifications = [];
            if ($request->professional_body) {
                foreach ($request->professional_body as $index => $body) {
                    if ($body) {
                        $professionalQualifications[] = [
                            'professional_body' => $body,
                            'membership_grade' => $request->professional_membership_grade[$index] ?? '',
                            'year_obtained' => $request->professional_year_obtained[$index] ?? null,
                        ];
                    }
                }
            }
            
            // Prepare employment history array
            $employmentHistory = [];
            if ($request->employment_organization) {
                foreach ($request->employment_organization as $index => $organization) {
                    if ($organization) {
                        $employmentHistory[] = [
                            'organization' => $organization,
                            'address' => $request->employment_address[$index] ?? '',
                            'phone' => $request->employment_phone[$index] ?? '',
                            'position' => $request->employment_position[$index] ?? '',
                            'from_date' => $request->employment_from_date[$index] ?? null,
                            'to_date' => $request->employment_to_date[$index] ?? null,
                        ];
                    }
                }
            }
            
            // Create employment application
            $application = EmploymentApplication::create([
                // Personal Data
                'position_applying_for' => $request->position_applying_for,
                'surname' => $request->surname,
                'first_name' => $request->first_name,
                'other_name' => $request->other_name,
                'email' =>  $request->email,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'state_of_origin' => $request->state_of_origin,
                'lga' => $request->lga,
                'marital_status' => $request->marital_status,
                'educational_qualification' => $request->educational_qualification,
                'residential_address' => $request->residential_address,
                'residential_lga_state' => $request->residential_lga_state,
                'contact_number' => $request->contact_number,
                
                // Next of Kin
                'next_of_kin_name' => $request->next_of_kin_name,
                'next_of_kin_relationship' => $request->next_of_kin_relationship,
                'next_of_kin_contact' => $request->next_of_kin_contact,
                'next_of_kin_address' => $request->next_of_kin_address,
                'parent_guardian_name' => $request->parent_guardian_name,
                'parent_guardian_address' => $request->parent_guardian_address,
                
                // Declaration
                'declaration_signature' => $signaturePath,
                'declaration_date' => $request->declaration_date,
                
                // Qualifications and History
                'educational_qualifications' => $educationalQualifications,
                'professional_qualifications' => $professionalQualifications,
                'employment_history' => $employmentHistory,
                'guarantors' => [],
                
                // Documents
                'passport_photo' => $passportPhotoPath,
                'resume' => $resumePath,
                'certificates' => $certificatePaths,
                'means_of_identification' => $identificationPaths,
                
                // Status
                'status' => 'pending',
            ]);
            
            
           
            // Send email notification
            // Mail::to(config('mail.from.address'))->send(new EmploymentApplicationReceived($application));
            
            // // Also send confirmation to applicant if they provided email
            // if ($request->has('email')) {
            //     Mail::to($request->email)->send(new EmploymentApplicationReceived($application, true));
            //     //  dd('true');
            // }
            
            return redirect()->route('employment.application.success', $application->id)
                ->with('success', 'Your employment application has been submitted successfully!');
                
        } catch (\Exception $e) {
             
            //  dd($e->getMessage());
             
            // Clean up uploaded files if error occurs
            $this->cleanupUploads([
                $passportPhotoPath ?? null,
                $resumePath ?? null,
                ...($certificatePaths ?? []),
                ...($identificationPaths ?? []),
                $signaturePath ?? null
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your application. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Helper function to upload files to public/uploads/
     */
    private function uploadFile($file, $directory)
    {
        if (!$file) {
            return null;
        }
        
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $uploadPath = public_path('uploads/' . $directory);
        
        // Create directory if it doesn't exist
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }
        
        // Move file to public directory
        $file->move($uploadPath, $filename);
        
        return 'uploads/' . $directory . '/' . $filename;
    }
    
    /**
     * Clean up uploaded files on error
     */
    private function cleanupUploads(array $paths)
    {
        foreach ($paths as $path) {
            if ($path && File::exists(public_path($path))) {
                File::delete(public_path($path));
            }
        }
    }
    
    /**
     * Display application success page
     */
    public function success($id)
    {
        $application = EmploymentApplication::findOrFail($id);
        
        return view('employment.success', compact('application'));
    }
    
    /**
     * Admin: List all applications
     */
    public function index(Request $request)
    {
        $query = EmploymentApplication::latest();
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('surname', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('position_applying_for', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $applications = $query->paginate(20);
        
        return view('employment.index', compact('applications'));
    }
    
    /**
     * Admin: View single application
     */
    // public function show(EmploymentApplication $application)
    // {
        
    //     return view('employment.show', compact('application'));
    // }
    
    
    public function show(EmploymentApplication $application)
    {
        $staffProfile = StaffProfile::where(
            'employment_application_id',
            $application->id
        )->first();
    
        return view('employment.show', compact('application', 'staffProfile'));
    }


    
    /**
     * Admin: Update application status
     */
   public function updateStatus(Request $request, EmploymentApplication $application)
    {
        
         
            
            
        $request->validate([
            'status' => 'required|in:pending,under_review,shortlisted,rejected,hired',
            'notes' => 'nullable|string|max:1000',
        ]);
    
        $application->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);
    
        if ($request->status === 'hired') {
            return redirect()->route(
                'admin.staff.create-from-application',
                ['id' => $application->id]
            );
        }
    
        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    
    /**
     * Admin: Download application documents
     */
    public function downloadDocument(EmploymentApplication $application, $type)
    {
        $filePath = null;
        $fileName = '';
        
        switch ($type) {
            case 'passport':
                $filePath = $application->passport_photo;
                $fileName = 'passport-photo-' . $application->surname . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
            case 'resume':
                $filePath = $application->resume;
                $fileName = 'resume-' . $application->surname . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
            case 'signature':
                $filePath = $application->declaration_signature;
                $fileName = 'signature-' . $application->surname . '.png';
                break;
            default:
                abort(404);
        }
        
        if (!$filePath || !File::exists(public_path($filePath))) {
            abort(404);
        }
        
        return response()->download(public_path($filePath), $fileName);
    }
    
    /**
     * Handle bulk actions for applications
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:pending,under_review,shortlisted,rejected,hired,delete',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:employment_applications,id'
        ]);
    
        try {
            $applications = EmploymentApplication::whereIn('id', $request->application_ids);
            
            switch ($request->action) {
                case 'delete':
                    $applications->delete();
                    $message = 'Applications deleted successfully';
                    break;
                default:
                    $applications->update(['status' => $request->action]);
                    $message = 'Applications updated successfully';
                    break;
            }
    
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function submitGuarantor(Request $request, $applicationId)
    {
        $request->validate([
          // Guarantors (minimum 2)
                'guarantor_name.*' => 'required|string|max:255',
                'guarantor_address.*' => 'required|string|max:500',
                'guarantor_lga_state.*' => 'required|string|max:100',
                'guarantor_residential_phone.*' => 'required|string|max:20',
                'guarantor_occupation.*' => 'required|string|max:255',
                'guarantor_office_address.*' => 'required|string|max:500',
                'guarantor_designation.*' => 'required|string|max:255',
                'guarantor_office_phone.*' => 'required|string|max:20',
                'guarantor_years_known.*' => 'required|integer|min:1',
                'guarantor_relationship.*' => 'required|string|max:100',
                'guarantor_signature.*' => 'required|string',
        ]);
    
        try {
            DB::transaction(function () use ($applicationId, $request) {
    
                // Lock the row for concurrency safety
                $application = EmploymentApplication::where('id', $applicationId)
                    ->lockForUpdate()
                    ->firstOrFail();
    
                $guarantors = $application->guarantors ?? [];

                // Maximum of 2 guarantors
                if (count($guarantors) >= 2) {
                    throw new \Exception('Maximum number of guarantors already submitted.');
                }

                // Handle guarantor signature upload
                $guarantorSignaturePath = null;
                if ($request->has('guarantor_signature') && $request->guarantor_signature) {
                    $signatureData = $request->guarantor_signature;
                    
                    if (preg_match('/^data:image\/(\w+);base64,/', $signatureData)) {
                        // Create signatures directory if it doesn't exist
                        $signaturesPath = public_path('uploads/signatures');
                        if (!File::exists($signaturesPath)) {
                            File::makeDirectory($signaturesPath, 0755, true);
                        }
                        
                        // Extract image data
                        $data = substr($signatureData, strpos($signatureData, ',') + 1);
                        $data = base64_decode($data);
                        
                        // Generate filename
                        $filename = 'guarantor_signature_' . time() . '_' . uniqid() . '.png';
                        $filePath = public_path('uploads/signatures/' . $filename);
                        
                        // Save file
                        file_put_contents($filePath, $data);
                        
                        $guarantorSignaturePath = 'uploads/signatures/' . $filename;
                    }
                }

                // Append guarantor
                $guarantors[] = [
                    'name' => $request->name,
                    'address' => $request->address,
                    'lga_state' => $request->lga_state,
                    'residential_phone' => $request->residential_phone,
                    'occupation' => $request->occupation,
                    'office_address' => $request->office_address,
                    'designation' => $request->designation,
                    'office_phone' => $request->office_phone,
                    'years_known' => $request->years_known,
                    'relationship' => $request->relationship,
                    'signature' => $guarantorSignaturePath,
                    'date' => now()->toDateString(),
                    'submitted_at' => now(),
                ];

                // Save back appended JSON
                $application->update([
                    'guarantors' => $guarantors
                ]);
            });
    
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    
        return view('employment.thank-you');
    }
    
    public function guarantor_show($applicationId)
    {
        $application = EmploymentApplication::findOrFail($applicationId);
    
        // Lock if already completed
        if (count($application->guarantors ?? []) >= 2) {
            return view('employment.closed');
        }
    
        return view('employment.guarantor', compact('application'));
    }
    
    /**
     * Helper to display uploaded files
     */
    public function viewFile($path)
    {
        $fullPath = public_path($path);
        
        if (!File::exists($fullPath)) {
            abort(404);
        }
        
        $mimeType = File::mimeType($fullPath);
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
        ]);
    }
}
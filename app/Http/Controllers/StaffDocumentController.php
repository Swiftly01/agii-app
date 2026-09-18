<?php

// app/Http/Controllers/StaffDocumentController.php
namespace App\Http\Controllers;

use App\Models\StaffDocument;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StaffDocumentController extends Controller
{
    // Staff upload document (for staff portal)
    public function create()
    {
        $staffProfile = Auth::user()->staffProfile;
        $documentTypes = StaffDocument::getDocumentTypes();
        
        return view('staff-portal.documents.create', compact('staffProfile', 'documentTypes'));
    }

    // Store document from staff portal
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', array_keys(StaffDocument::getDocumentTypes())),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);

        $staffProfile = Auth::user()->staffProfile;
        
        // Upload file
        $file = $request->file('document_file');
        $filePath = $file->store('staff-documents/' . $staffProfile->staff_id, 'private');
        
        // Create document record
        $document = StaffDocument::create([
            'staff_profile_id' => $staffProfile->id,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'status' => 'pending',
        ]);

        return redirect()->route('staff.portal.documents')
            ->with('success', 'Document uploaded successfully! Waiting for admin approval.');
    }

    // List documents for staff portal
    // public function index()
    // {
    //     $staffProfile = Auth::user()->staffProfile;
    //     $documents = $staffProfile->documents()
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(15);
        
    //     $documentTypes = StaffDocument::getDocumentTypes();
    //     $statuses = [
    //         'pending' => 'Pending Review',
    //         'approved' => 'Approved',
    //         'rejected' => 'Rejected'
    //     ];
        
    //     return view('staff-portal.documents.index', compact('documents', 'documentTypes', 'statuses'));
    // }

// public function index(Request $request)
// {
//     $staffProfile = Auth::user()->staffProfile;
//     $documentTypes = StaffDocument::getDocumentTypes();

//     // Get staff documents
//     $documents = $staffProfile->documents()->latest()->get(); // Eloquent Collection

//     // Add pre-existing application files if staff just got created
//     $application = $staffProfile->employmentApplication;

//     $appFiles = [];

//     if ($application) {
//         if ($application->passport_photo) {
//             $appFiles[] = (object)[
//                 'id' => 'app-passport',
//                 'title' => 'Passport Photo',
//                 'description' => 'Uploaded during application',
//                 'document_type' => 'passport',
//                 'file_path' => $application->passport_photo,
//                 'file_name' => pathinfo($application->passport_photo, PATHINFO_BASENAME),
//                 'file_size' => @filesize(public_path($application->passport_photo)) ?: 0,
//                 'status' => 'approved',
//                 'issue_date' => null,
//                 'expiry_date' => null,
//                 'review_notes' => null,
//                 'created_at' => $application->created_at
//             ];
//         }

//         // Resume
//         if ($application->resume) {
//             $appFiles[] = (object)[
//                 'id' => 'app-resume',
//                 'title' => 'Resume',
//                 'description' => 'Uploaded during application',
//                 'document_type' => 'resume',
//                 'file_path' => $application->resume,
//                 'file_name' => pathinfo($application->resume, PATHINFO_BASENAME),
//                 'file_size' => @filesize(public_path($application->resume)) ?: 0,
//                 'status' => 'approved',
//                 'issue_date' => null,
//                 'expiry_date' => null,
//                 'review_notes' => null,
//                 'created_at' => $application->created_at
//             ];
//         }

//         // Certificates
//         if ($application->certificates && is_array($application->certificates)) {
//             foreach ($application->certificates as $index => $file) {
//                 $appFiles[] = (object)[
//                     'id' => "app-cert-{$index}",
//                     'title' => 'Certificate',
//                     'description' => 'Uploaded during application',
//                     'document_type' => 'certificate',
//                     'file_path' => $file,
//                     'file_name' => pathinfo($file, PATHINFO_BASENAME),
//                     'file_size' => @filesize(public_path($file)) ?: 0,
//                     'status' => 'approved',
//                     'issue_date' => null,
//                     'expiry_date' => null,
//                     'review_notes' => null,
//                     'created_at' => $application->created_at
//                 ];
//             }
//         }

//         // Means of ID
//         if ($application->means_of_identification && is_array($application->means_of_identification)) {
//             foreach ($application->means_of_identification as $index => $file) {
//                 $appFiles[] = (object)[
//                     'id' => "app-id-{$index}",
//                     'title' => 'Means of Identification',
//                     'description' => 'Uploaded during application',
//                     'document_type' => 'identification',
//                     'file_path' => $file,
//                     'file_name' => pathinfo($file, PATHINFO_BASENAME),
//                     'file_size' => @filesize(public_path($file)) ?: 0,
//                     'status' => 'approved',
//                     'issue_date' => null,
//                     'expiry_date' => null,
//                     'review_notes' => null,
//                     'created_at' => $application->created_at
//                 ];
//             }
//         }
//     }

//     // Convert $documents to a plain collection to avoid Eloquent getKey issues
//     $documents = collect($documents)->merge(collect($appFiles));

//     return view('staff-portal.documents.index', compact('staffProfile', 'documents', 'documentTypes'));
// }


public function index(Request $request, StaffProfile $staffProfile = null)
{
    // CASE 1: Admin passed a staff profile via route
    if ($staffProfile) {
        // optional: authorize admin
        // $this->authorize('viewDocuments', $staffProfile);
    } 
    // CASE 2: Logged-in staff viewing own documents
    else {
        $staffProfile = Auth::user()->staffProfile;
    }

    if (!$staffProfile) {
        abort(404, 'Staff profile not found');
    }

    $documentTypes = StaffDocument::getDocumentTypes();

    // Staff-uploaded documents
    $documents = $staffProfile->documents()->latest()->get();

    // Application documents
    $application = $staffProfile->employmentApplication;
    $appFiles = [];

    if ($application) {
        if ($application->passport_photo) {
            $appFiles[] = $this->mapApplicationFile(
                'Passport Photo',
                'passport',
                $application->passport_photo,
                $application->created_at,
                'app-passport'
            );
        }

        if ($application->resume) {
            $appFiles[] = $this->mapApplicationFile(
                'Resume',
                'resume',
                $application->resume,
                $application->created_at,
                'app-resume'
            );
        }

        if (is_array($application->certificates)) {
            foreach ($application->certificates as $i => $file) {
                $appFiles[] = $this->mapApplicationFile(
                    'Certificate',
                    'certificate',
                    $file,
                    $application->created_at,
                    "app-cert-$i"
                );
            }
        }

        if (is_array($application->means_of_identification)) {
            foreach ($application->means_of_identification as $i => $file) {
                $appFiles[] = $this->mapApplicationFile(
                    'Means of Identification',
                    'identification',
                    $file,
                    $application->created_at,
                    "app-id-$i"
                );
            }
        }
    }

    $documents = collect($documents)->merge($appFiles);

    return view(
        'staff-portal.documents.index',
        compact('staffProfile', 'documents', 'documentTypes')
    );
}


private function mapApplicationFile(
    string $title,
    string $type,
    string $path,
    $createdAt,
    string $id
) {
    return (object)[
        'id' => $id,
        'title' => $title,
        'description' => 'Uploaded during application',
        'document_type' => $type,
        'file_path' => $path,
        'file_name' => pathinfo($path, PATHINFO_BASENAME),
        'file_size' => @filesize(public_path($path)) ?: 0,
        'status' => 'approved',
        'issue_date' => null,
        'expiry_date' => null,
        'review_notes' => null,
        'created_at' => $createdAt
    ];
}


    // Download document
    public function download($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Check authorization
        if (Auth::user()->staffProfile->id !== $document->staff_profile_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    // Preview document
    public function preview($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Check authorization
        if (Auth::user()->staffProfile->id !== $document->staff_profile_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        $path = Storage::disk('private')->path($document->file_path);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    }

    // Delete document (staff can only delete pending ones)
    public function destroy($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Check authorization and status
        if (Auth::user()->staffProfile->id !== $document->staff_profile_id || $document->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete file
        Storage::disk('private')->delete($document->file_path);
        
        $document->delete();
        
        return redirect()->route('staff.portal.documents')
            ->with('success', 'Document deleted successfully.');
    }
}
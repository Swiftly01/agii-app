<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use App\Models\StaffDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferLetterMail;
use Illuminate\Support\Str;

class AdminOfferLetterController extends Controller
{
    /**
     * Show the form for sending offer letters
     */
    public function create()
    {
        // Get all active staff
        $staff = StaffProfile::with('user')
            ->where('status', 'active')
            ->orWhere('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.offer-letters.create', compact('staff'));
    }

    /**
     * Send offer letters to selected staff
     */
    public function sendToSelected(Request $request)
    {
        $request->validate([
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff_profiles,id',
            'offer_letter' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'title' => 'required|string|max:255',
            'effective_date' => 'required|date',
            'send_email' => 'boolean',
            'email_message' => 'nullable|string',
        ]);

        $uploadedFile = $request->file('offer_letter');
        $successCount = 0;
        $failedStaff = [];

        foreach ($request->staff_ids as $staffId) {
            try {
                $staff = StaffProfile::with('user')->findOrFail($staffId);
                
                // Create directory if it doesn't exist
                $directory = public_path('uploads/staff-documents/' . $staff->staff_id . '/offer-letters');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                
                // Generate unique filename
                $originalName = $uploadedFile->getClientOriginalName();
                $extension = $uploadedFile->getClientOriginalExtension();
                $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) 
                           . '_' . time() . '_' . $staff->staff_id . '.' . $extension;
                
                // Store file in public directory
                $filePath = 'uploads/staff-documents/' . $staff->staff_id . '/offer-letters/' . $fileName;
                $uploadedFile->move($directory, $fileName);
                
                // Create document record
                $document = StaffDocument::create([
                    'staff_profile_id' => $staff->id,
                    'document_type' => 'offer_letter',
                    'title' => $request->title,
                    'file_path' => $filePath,
                    'file_url' => url($filePath), // Store public URL
                    'file_name' => $originalName,
                    'stored_name' => $fileName,
                    'file_type' => $uploadedFile->getMimeType(),
                    'file_size' => $uploadedFile->getSize(),
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'effective_date' => $request->effective_date,
                    'metadata' => json_encode([
                        'sent_by' => auth()->user()->name,
                        'sent_at' => now()->toDateTimeString(),
                        'send_email' => $request->send_email ?? false,
                    ]),
                ]);
                
                // Send email if requested
                if ($request->send_email && $staff->user && $staff->user->email) {
                    Mail::to($staff->user->email)->send(
                        new OfferLetterMail($document, $staff, $request->email_message)
                    );
                }
                
                $successCount++;
                
            } catch (\Exception $e) {
                $failedStaff[] = [
                    'staff_id' => $staffId,
                    'name' => $staff->user->name ?? 'Unknown',
                    'error' => $e->getMessage()
                ];
            }
        }

        $message = "Offer letter sent to {$successCount} staff member(s).";
        
        if (!empty($failedStaff)) {
            $message .= " Failed for " . count($failedStaff) . " staff member(s).";
        }

        return redirect()->route('admin.offer-letters.create')
            ->with('success', $message)
            ->with('failed_staff', $failedStaff);
    }

    /**
     * Send offer letter to all staff
     */
    public function sendToAll(Request $request)
    {
        $request->validate([
            'offer_letter' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'title' => 'required|string|max:255',
            'effective_date' => 'required|date',
        ]);

        // Get all active staff IDs
        $allStaffIds = StaffProfile::whereIn('status', ['active', 'pending'])
            ->pluck('id')
            ->toArray();

        // Create new request with all staff IDs
        $newRequest = new Request($request->all());
        $newRequest->merge(['staff_ids' => $allStaffIds]);

        return $this->sendToSelected($newRequest);
    }

    /**
     * List all sent offer letters
     */
    public function index()
    {
        $documents = StaffDocument::with(['staff.user', 'reviewer'])
            ->where('document_type', 'offer_letter')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.offer-letters.index', compact('documents'));
    }

    /**
     * Download offer letter (Admin)
     */
    public function download($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        if (!File::exists(public_path($document->file_path))) {
            abort(404, 'File not found.');
        }

        return response()->download(
            public_path($document->file_path), 
            $document->file_name
        );
    }

    /**
     * Preview offer letter (Admin)
     */
    public function preview($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        if (!File::exists(public_path($document->file_path))) {
            abort(404, 'File not found.');
        }

        $filePath = public_path($document->file_path);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
        ]);
    }

    /**
     * Delete offer letter
     */
    public function destroy($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Delete physical file
        if (File::exists(public_path($document->file_path))) {
            File::delete(public_path($document->file_path));
        }
        
        // Delete record
        $document->delete();
        
        return redirect()->route('admin.offer-letters.index')
            ->with('success', 'Offer letter deleted successfully.');
    }
}
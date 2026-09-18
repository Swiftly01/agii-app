<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StaffDocument;
use Illuminate\Support\Facades\File;

class StaffOfferLetterController extends Controller
{
    /**
     * Display staff's offer letters
     */
    public function myOfferLetters()
    {
        $staffId = auth()->user()->staffProfile->id;
        
        $documents = StaffDocument::where('staff_profile_id', $staffId)
            ->where('document_type', 'offer_letter')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff.offer-letters.index', compact('documents'));
    }

    /**
     * Download offer letter (Staff)
     */
    public function download($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Verify document belongs to the logged-in staff
        if ($document->staff_profile_id !== auth()->user()->staffProfile->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!File::exists(public_path($document->file_path))) {
            abort(404, 'File not found.');
        }

        return response()->download(
            public_path($document->file_path), 
            $document->file_name
        );
    }

    /**
     * View offer letter (Staff)
     */
    public function view($id)
    {
        $document = StaffDocument::findOrFail($id);
        
        // Verify document belongs to the logged-in staff
        if ($document->staff_profile_id !== auth()->user()->staffProfile->id) {
            abort(403, 'Unauthorized access.');
        }

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
}
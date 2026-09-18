<?php

namespace App\Http\Controllers;

use App\Models\StaffDocument;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDocumentController extends Controller
{
    public function pending()
    {
        $pendingDocuments = StaffDocument::with(['staffProfile.user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.documents.pending', compact('pendingDocuments'));
    }

    public function review($id)
    {
        $document = StaffDocument::with(['staffProfile.user'])->findOrFail($id);
        
        return view('admin.documents.review', compact('document'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'required_if:status,rejected|nullable|string'
        ]);

        $document = StaffDocument::findOrFail($id);
        
        $document->update([
            'status' => $request->status,
            'review_notes' => $request->review_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // You can add notification to staff here
        
        return redirect()->route('admin.documents.pending')
            ->with('success', 'Document ' . $request->status . ' successfully.');
    }

    public function allDocuments(Request $request)
    {
        $query = StaffDocument::with(['staffProfile.user', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('staffProfile.user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get document types for filter dropdown
        $documentTypes = StaffDocument::distinct()
            ->whereNotNull('document_type')
            ->pluck('document_type')
            ->toArray();

        $documents = $query->paginate(30);
        
        return view('admin.documents.index', compact('documents', 'documentTypes'));
    }

    // Bulk action methods
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:staff_documents,id'
        ]);

        $count = 0;
        foreach ($request->document_ids as $documentId) {
            $document = StaffDocument::find($documentId);
            if ($document && $document->status == 'pending') {
                $document->update([
                    'status' => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now()
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.documents.pending')
            ->with('success', "{$count} documents approved successfully.");
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:staff_documents,id',
            'reject_notes' => 'required|string'
        ]);

        $count = 0;
        foreach ($request->document_ids as $documentId) {
            $document = StaffDocument::find($documentId);
            if ($document && $document->status == 'pending') {
                $document->update([
                    'status' => 'rejected',
                    'review_notes' => $request->reject_notes,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now()
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.documents.pending')
            ->with('success', "{$count} documents rejected successfully.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:staff_documents,id',
            'bulk_action' => 'required|in:approved,rejected',
            'review_notes' => 'required_if:bulk_action,rejected|nullable|string'
        ]);

        $count = 0;
        foreach ($request->document_ids as $documentId) {
            $document = StaffDocument::find($documentId);
            if ($document && $document->status == 'pending') {
                $document->update([
                    'status' => $request->bulk_action,
                    'review_notes' => $request->review_notes,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now()
                ]);
                $count++;
            }
        }

        $action = $request->bulk_action == 'approved' ? 'approved' : 'rejected';
        return redirect()->route('admin.documents.pending')
            ->with('success', "{$count} documents {$action} successfully.");
    }
}
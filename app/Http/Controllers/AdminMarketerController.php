<?php

namespace App\Http\Controllers;

use App\Enums\MarketerStatus;
use App\Models\Marketer;
use App\Services\Marketer\MarketerApprovalService;
use App\Services\Marketer\MarketerDirectory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMarketerController extends Controller
{
    public function __construct(
        private readonly MarketerDirectory $directory,
        private readonly MarketerApprovalService $approval,
    ) {}

    // Marketer list with platform totals and per-marketer vendor and commission figures
    public function index(Request $request)
    {
        return view('admin.marketers.index', [
            'marketers' => $this->directory->paginate(
                $request->string('search')->toString() ?: null,
                MarketerStatus::tryFrom($request->string('status')->toString()),
            ),
            'totals' => $this->directory->totals(),
        ]);
    }

    // Approve an application, the marketer is emailed and gets full dashboard access
    public function approve(Marketer $marketer): RedirectResponse
    {
        $this->approval->approve($marketer, auth()->user());

        return back()->with('success', "{$marketer->user->full_name} has been approved as a marketer.");
    }

    // Reject an application with a reason that is emailed to the marketer
    public function reject(Request $request, Marketer $marketer): RedirectResponse
    {
        $validated = $request->validate(['reason' => ['required', 'string', 'max:1000']]);

        $this->approval->reject($marketer, auth()->user(), $validated['reason']);

        return back()->with('success', "{$marketer->user->full_name}'s application was rejected.");
    }

    // PDF of the vendors marketers added: every marketer's by default, or one marketer's with ?marketer={id}
    public function exportVendorsPdf(Request $request)
    {
        $marketer = $request->filled('marketer')
            ? Marketer::with('user')->findOrFail($request->integer('marketer'))
            : null;

        $data = [
            'vendors' => $this->directory->vendorsForExport($marketer),
            'marketer' => $marketer,
            'generatedAt' => now(),
            'generatedBy' => $request->user()->full_name,
        ];

        $filename = 'vendors-added-by-' . ($marketer ? Str::slug($marketer->user?->full_name ?: 'marketer') : 'marketers')
            . '-' . now()->format('Y-m-d') . '.pdf';

        // Real PDF download when barryvdh/laravel-dompdf is installed
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.marketers.vendors-pdf', $data + ['printMode' => false])
                ->setPaper('a4', 'landscape')
                ->download($filename);
        }

        // Until the package is installed, open a print-ready page; "Save as PDF" in the print dialog gives the same file
        return response()->view('admin.marketers.vendors-pdf', $data + ['printMode' => true]);
    }

    // One marketer's referral code and every vendor they referred, with dates and commission
    public function show(Marketer $marketer)
    {
        $marketer->load('user')->loadCount([
            'referredVendors',
            'referredVendors as vendors_with_products_count' => fn ($query) => $query->whereHas('products'),
        ]);

        return view('admin.marketers.show', [
            'marketer' => $marketer,
            'referrals' => $this->directory->referrals($marketer),
        ]);
    }
}

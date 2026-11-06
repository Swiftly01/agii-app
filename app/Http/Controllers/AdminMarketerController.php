<?php

namespace App\Http\Controllers;

use App\Models\Marketer;
use App\Services\Marketer\MarketerDirectory;
use Illuminate\Http\Request;

class AdminMarketerController extends Controller
{
    public function __construct(private readonly MarketerDirectory $directory) {}

    // Marketer list with platform totals and per-marketer vendor and commission figures
    public function index(Request $request)
    {
        return view('admin.marketers.index', [
            'marketers' => $this->directory->paginate($request->string('search')->toString() ?: null),
            'totals' => $this->directory->totals(),
        ]);
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

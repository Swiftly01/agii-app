<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AdminVendorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'vendor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('business_name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('products', fn ($q) => $q->where('status', 'active'));
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('products', fn ($q) => $q->where('status', 'active'));
            }
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $vendors = $query->withCount(['products' => fn ($q) => $q->where('status', 'active')])
            ->withSum('products', 'views')
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->except('page'));

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(int $id)
    {
        $vendor = User::with(['products' => fn ($q) => $q->with('category')->orderBy('created_at', 'desc')])
            ->where('user_type', 'vendor')
            ->findOrFail($id);

        $vendorStats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('status', 'active')->count(),
            'pending_products' => $vendor->products()->where('status', 'pending')->count(),
            'total_views' => $vendor->products()->sum('views'),
            'total_sales' => $vendor->products()->where('status', 'sold')->count(),
            'average_rating' => $vendor->products()->avg('rating'),
        ];

        return view('admin.vendors.show', compact('vendor', 'vendorStats'));
    }

    public function edit(int $id)
    {
        $vendor = User::where('user_type', 'vendor')->findOrFail($id);

        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $vendor = User::where('user_type', 'vendor')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($vendor->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'business_category' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('admin.vendors.show', $vendor->id)
            ->with('success', "\"{$vendor->full_name}\" was updated.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $vendor = User::where('user_type', 'vendor')->findOrFail($id);
        $vendor->delete();

        return back()->with('success', 'Vendor deleted successfully.');
    }
}

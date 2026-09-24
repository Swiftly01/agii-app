<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorContact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $customers = $query->withCount('vendorContacts')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));

        return view('admin.customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = User::where('user_type', 'customer')->findOrFail($id);

        $contacts = VendorContact::where('user_id', $customer->id)
            ->with('product')
            ->latest('contact_date')
            ->get();

        $stats = [
            'total_contacts' => $contacts->count(),
            'deals_closed' => $contacts->where('deal_outcome', 'sold')->count(),
            'total_deal_value' => $contacts->where('deal_outcome', 'sold')->sum('deal_value'),
        ];

        return view('admin.customers.show', compact('customer', 'contacts', 'stats'));
    }

    public function edit(int $id)
    {
        $customer = User::where('user_type', 'customer')->findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $customer = User::where('user_type', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer->id)
            ->with('success', "\"{$customer->full_name}\" was updated.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $customer = User::where('user_type', 'customer')->findOrFail($id);
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
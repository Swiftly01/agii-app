<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorContact;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.vendor');
    }


    public function home()
    {
        return view('dashboard.vendor');
    }

    public function customerDashboard()
    {
        $user = Auth::user();
        // $user = auth()->user();


        if ($user->user_type === 'marketer') {
            return redirect()->route('marketer.dashboard');
        } elseif ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $contacts = VendorContact::with(['vendor', 'product'])
            ->where('user_id', $user->id)
            ->orderBy('contact_date', 'desc')
            ->get();

        $totalContacts = $contacts->count();
        $successfulContacts = $contacts->where('deal_outcome', 'successful')->count();
        $unsuccessfulContacts = $contacts->where('deal_outcome', 'unsuccessful')->count();
        $pendingOutcomes = $contacts->whereNull('deal_outcome')->count();
        $pendingFollowup = $contacts->where('status', 'pending_followup')->count();

        $successRate = $totalContacts > 0 ? round(($successfulContacts / $totalContacts) * 100) : 0;
        $pendingRate = $totalContacts > 0 ? round(($pendingOutcomes / $totalContacts) * 100) : 0;
        $unsuccessfulRate = $totalContacts > 0 ? round(($unsuccessfulContacts / $totalContacts) * 100) : 0;

        // Get recent products for the "Add Contact" functionality
        $recentProducts = Product::where('user_id', '!=', $user->id) // Products from other users (vendors)
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent activities
        $recentActivities = $this->getRecentActivities($user);

        return view('dashboard.customer', compact(
            'contacts',
            'recentProducts',
            'totalContacts',
            'successfulContacts',
            'unsuccessfulContacts',
            'pendingOutcomes',
            'pendingFollowup',
            'successRate',
            'pendingRate',
            'unsuccessfulRate',
            'recentActivities'
        ));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'contact_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        $vendor = User::findOrFail($request->vendor_id);
        $product = Product::findOrFail($request->product_id);

        VendorContact::create([
            'user_id' =>  $user = Auth::id(),
            'vendor_id' => $vendor->id,
            'product_id' => $product->id,
            'vendor_name' => $vendor->business_name ?: $vendor->full_name,
            'vendor_contact_info' => $vendor->email ?: $vendor->phone,
            'product_name' => $product->title,
            'contact_date' => now(),
            'contact_method' => $request->contact_method,
            'notes' => $request->notes,
            'status' => 'contacted'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact recorded successfully!'
        ]);
    }

    public function updateOutcome(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:vendor_contacts,id',
            'deal_outcome' => 'required|in:successful,unsuccessful,negotiating,cancelled,pending_payment,delivered',
            'deal_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'vendor_rating' => 'nullable|in:positive,neutral,negative'
        ]);

        $contact = VendorContact::where('id', $request->contact_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $contact->update([
            'deal_outcome' => $request->deal_outcome,
            'deal_value' => $request->deal_value,
            'notes' => $request->notes ?: $contact->notes,
            'vendor_rating' => $request->vendor_rating,
            'outcome_reported_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deal outcome updated successfully!'
        ]);
    }

    private function getRecentActivities($user)
    {
        $contacts = VendorContact::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return $contacts->map(function ($contact) {
            $icon = 'question';
            $description = 'Contacted ' . $contact->vendor_name;

            if ($contact->deal_outcome) {
                switch ($contact->deal_outcome) {
                    case 'successful':
                        $icon = 'check-circle';
                        $description = 'Marked deal with ' . $contact->vendor_name . ' as successful';
                        break;
                    case 'unsuccessful':
                        $icon = 'times-circle';
                        $description = 'Reported deal with ' . $contact->vendor_name . ' as unsuccessful';
                        break;
                    case 'negotiating':
                        $icon = 'comments';
                        $description = 'Still negotiating with ' . $contact->vendor_name;
                        break;
                    default:
                        $icon = 'info-circle';
                        $description = 'Updated deal with ' . $contact->vendor_name;
                }
            }

            return [
                'icon' => $icon,
                'description' => $description,
                'time' => $contact->updated_at->diffForHumans()
            ];
        });
    }

    public function getVendorProducts($vendorId)
    {
        $products = Product::where('user_id', $vendorId)
            ->active()
            ->get(['id', 'title', 'price', 'images']);

        return response()->json($products);
    }
}

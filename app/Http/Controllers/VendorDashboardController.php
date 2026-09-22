<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\VendorContact;
use App\Models\User;
use App\Services\ProductBoostService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function __construct(private readonly ProductBoostService $productBoostService)
    {
    }
    public function index()
    {
        $vendor = Auth::user();
        
        // Get vendor's products
        $products = Product::where('user_id', $vendor->id)->get();

        // Calculate stats from actual database data
        $totalViews = $products->sum('views');
        $totalInquiries = VendorContact::where('vendor_id', $vendor->id)->count();
        $activeProducts = $products->where('status', 'active')->count();
        $newProductsThisWeek = $products->where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Calculate average rating (you might need to implement reviews system)
        $averageRating = 4.8; // Placeholder - implement your review system

        // Calculate engagement rate
        $engagementRate = $totalViews > 0 ? round(($totalInquiries / $totalViews) * 100, 1) : 0;

        // Recent customer contacts from VendorContact
        $recentContacts = VendorContact::with(['user', 'product'])
            ->where('vendor_id', $vendor->id)
            ->orderBy('contact_date', 'desc')
            ->limit(5)
            ->get();

        // Top performing products with view and inquiry counts
        $topProducts = Product::where('user_id', $vendor->id)
            ->withCount(['vendorContacts as inquiries_count' => function ($query) {
                $query->where('vendor_id', Auth::id());
            }])
            ->orderBy('views', 'desc')
            ->limit(4)
            ->get();

        // Recent activities based on actual data
        $recentActivities = $this->getRecentActivities($vendor);

        if (is_null($vendor->latitude) || is_null($vendor->longitude)) {
            $location = Location::where('lga', $vendor->local_government)->first();

            if ($location) {
                $vendor->latitude = $location->latitude;
                $vendor->longitude = $location->longitude;
                $vendor->save();
            }
        }

        return view('dashboard.vendor', compact(
            'totalViews',
            'totalInquiries',
            'activeProducts',
            'newProductsThisWeek',
            'averageRating',
            'engagementRate',
            'recentContacts',
            'topProducts',
            'recentActivities'
        ));
    }

    private function getRecentActivities($vendor)
    {
        $activities = [];

        // Get recent contacts for activities
        $recentContacts = VendorContact::with(['product'])
            ->where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentContacts as $contact) {
            $activities[] = [
                'icon' => 'chat-dots',
                'title' => 'New Customer Contact',
                'description' => ($contact->user->first_name ?? 'Customer') . ' contacted about ' . $contact->product_name,
                'time' => $contact->created_at->diffForHumans()
            ];
        }

        // Get products with recent views
        $recentProducts = Product::where('user_id', $vendor->id)
            ->where('updated_at', '>=', Carbon::now()->subDay())
            ->where('views', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($recentProducts as $product) {
            $activities[] = [
                'icon' => 'eye',
                'title' => 'Product Viewed',
                'description' => $product->title . ' received ' . $product->views . ' views',
                'time' => $product->updated_at->diffForHumans()
            ];
        }

        // If no recent activities, show some default messages
        if (empty($activities)) {
            $activities[] = [
                'icon' => 'info-circle',
                'title' => 'Welcome to Your Dashboard',
                'description' => 'Start by adding your first product',
                'time' => 'Just now'
            ];
        }

        return $activities;
    }


    public function showAdvert()
    {
        $vendor = Auth::user();

        // Get vendor's products
        $products = Product::where('user_id', $vendor->id)->paginate(20);
        $boostUsage = $this->productBoostService->boostUsage($vendor);

        return view('dashboard.vendor_adverts', compact('products', 'boostUsage'));
    }
}

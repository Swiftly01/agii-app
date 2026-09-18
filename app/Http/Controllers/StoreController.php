<?php
// app/Http/Controllers/StoreController.php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    // public function index(Request $request)
    // {
    //     $stores = Store::with(['user', 'products'])
    //         ->where('is_active', true)
    //         ->whereHas('user', function ($query) {
    //             $query->whereHas('activeSubscription');
    //         })
    //         ->whereHas('products')
    //         ->orderBy('rating', 'desc')
    //         ->paginate(12);

    //     return view('stores.index', compact('stores'));
    // }


    public function index(Request $request)
    {
        $query = Store::with(['user', 'products'])
            ->where('is_active', true)
            ->whereHas('user', function ($query) {
                $query->whereHas('activeSubscription');
            })
            ->whereHas('products'); // Only stores with products

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('store_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category')) {
            $query->whereHas('products.category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'rating');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderBy('store_name', 'asc');
                break;
            case 'products':
                $query->withCount('products')->orderBy('products_count', 'desc');
                break;
            default: // rating
                $query->orderBy('rating', 'desc');
        }

        $stores = $query->paginate(12);

        $categories = Category::all(); // For filter dropdown

        return view('stores.index', compact('stores', 'categories'));
    }
    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        $user = Auth::user();

        // Check if user already has a store
        if ($user->store) {
            return redirect()->route('stores.edit', $user->store)
                ->with('info', 'You already have a store. You can edit it here.');
        }

        // Check if user is a vendor
        if ($user->user_type !== 'vendor') {
            return redirect()->route('dashboard')
                ->with('error', 'Only vendors can create stores.');
        }

        // Simple store creation check using User model methods
        if (!$user->canCreateStore()) {
            $message = 'Store creation requires an active subscription on plans 4, 5, or 6.';

            if ($user->hasActiveSubscription() && !$user->isOnStorePlan()) {
                $currentPlan = $user->current_plan;
                $message = "Your current plan ({$currentPlan->name}) does not include store features. Please upgrade to plans 4, 5, or 6.";
            } elseif (!$user->hasActiveSubscription()) {
                $message = 'No active subscription found. Please subscribe to plans 4, 5, or 6 to create a store.';
            }

            return redirect()->route('vendor.plans')->with('error', $message);
        }

        return view('stores.create', [
            'plan_features' => $user->getStorePlanFeatures()
        ]);
    }
    /**
     * Store a newly created store in storage.
     */
    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {

        // Check if user already has a store
        if (Auth::user()->store) {
            return redirect()->route('stores.edit', Auth::user()->store)
                ->with('info', 'You already have a store.');
        }



        $store = Store::where('user_id', Auth::id())->first();

        $request->validate([
            'store_name' => 'required|string|max:255|unique:stores,store_name',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'store_address' => 'nullable|string|max:500',
            'return_policy' => 'nullable|string|max:1000',
            'shipping_policy' => 'nullable|string|max:1000',
        ]);

        try {
            // Generate unique slug
            $slug = Str::slug($request->store_name);
            $originalSlug = $slug;
            $counter = 1;

            while (Store::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $storeData = [
                'user_id' => Auth::id(),
                'store_name' => $request->store_name,
                'slug' => $slug,
                'description' => $request->description,
                'store_phone' => $request->store_phone,
                'store_email' => $request->store_email,
                'store_address' => $request->store_address,
                'return_policy' => $request->return_policy,
                'shipping_policy' => $request->shipping_policy,
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {

                $image = $request->file('logo');
                $directory = public_path('stores/logos');

                // Create folder if missing
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Move file
                $image->move($directory, $filename);

                // Delete old logo if exists
                if (!empty($store->logo) && file_exists(public_path($store->logo))) {
                    unlink(public_path($store->logo));
                }

                // Save relative path
                $storeData['logo'] = 'stores/logos/' . $filename;
            }



            // Handle banner upload
            if ($request->hasFile('banner')) {

                $image = $request->file('banner');
                $directory = public_path('stores/banners');

                // Create folder if missing
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Move file
                $image->move($directory, $filename);

                // Delete old banner if exists
                if (!empty($store->banner) && file_exists(public_path($store->banner))) {
                    unlink(public_path($store->banner));
                }

                // Save relative path
                $storeData['banner'] = 'stores/banners/' . $filename;
            }


            $store = Store::create($storeData);

            return redirect()->route('stores.show', $store->slug)
                ->with('success', 'Store created successfully! Your store is now live.');
        } catch (\Exception $e) {

            dd($e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create store. Please try again.');
        }
    }

    /**
     * Display the specified store.
     */
    /**
     * Display the specified store with paginated products
     */
    public function show($slug, Request $request)
    {
        $store = Store::with(['user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get paginated products
        $products = Product::with(['category', 'user'])
            ->where('store_id', $store->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get store statistics
        $stats = [
            'total_products' => Product::where('store_id', $store->id)->where('status', 'active')->count(),
            // 'total_services' => $store->services()->where('is_active', true)->count(),
            'average_rating' => $store->rating,
            'total_views' => Product::where('store_id', $store->id)->sum('views'),
            // 'total_sales' => Product::where('store_id', $store->id)->sum('sold_count') ?? 0,
        ];

        // Get unique categories for filtering
        $categories = Category::whereHas('products', function ($query) use ($store) {
            $query->where('store_id', $store->id)->where('status', 'active');
        })->get();

        return view('stores.show', compact('store', 'products', 'stats', 'categories'));
    }
    /**
     * Show the form for editing the specified store.
     */
    public function edit(Store $store)
    {
        // Authorization - user can only edit their own store
        if ($store->user_id !== Auth::id()) {
            abort(403);
        }

        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Store $store)
    {
        // Authorization
        if ($store->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email',
            'store_address' => 'nullable|string',
            'return_policy' => 'nullable|string',
            'shipping_policy' => 'nullable|string',
        ]);

        $updateData = [
            'store_name' => $request->store_name,
            'description' => $request->description,
            'store_phone' => $request->store_phone,
            'store_email' => $request->store_email,
            'store_address' => $request->store_address,
            'return_policy' => $request->return_policy,
            'shipping_policy' => $request->shipping_policy,
        ];

        /*
    |--------------------------------------------------------------------------
    | HANDLE LOGO UPLOAD USING move()
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('logo')) {

            $image = $request->file('logo');

            // Ensure directory exists
            $directory = public_path('product-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Filename
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file
            $image->move($directory, $filename);

            // Delete old logo
            if ($store->logo && file_exists(public_path($store->logo))) {
                unlink(public_path($store->logo));
            }

            // Save relative path in DB
            $updateData['logo'] = 'product-images/' . $filename;
        }

        /*
    |--------------------------------------------------------------------------
    | HANDLE BANNER UPLOAD USING move()
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('banner')) {

            $image = $request->file('banner');

            // Ensure directory exists
            $directory = public_path('product-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move($directory, $filename);

            // Delete old banner
            if ($store->banner && file_exists(public_path($store->banner))) {
                unlink(public_path($store->banner));
            }

            $updateData['banner'] = 'product-images/' . $filename;
        }

        /*
    |--------------------------------------------------------------------------
    | UPDATE STORE
    |--------------------------------------------------------------------------
    */

        $store->update($updateData);

        return redirect()
            ->route('stores.edit', $store)
            ->with('success', 'Store updated successfully!');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        // Authorization
        if ($store->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated files
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }
        if ($store->banner) {
            Storage::disk('public')->delete($store->banner);
        }

        $store->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Store deleted successfully!');
    }
}

<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VendorContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


public function index(Request $request, $category = null)
{
    // Shared categories for navigation
    $categories = Category::all();
    
    // Check if this is a Hotel category
    $isHotelCategory = false;
    $hotelOwners = collect();
    
    // Initialize featuredServices with empty collection as default
    $featuredServices = collect();
    
    if ($category) {
        $catModel = Category::where('slug', $category)->first();
        if ($catModel && strtolower($catModel->name) === 'hotel') {
            $isHotelCategory = true;
            
            // Get all hotel products grouped by user
            $hotelProducts = Product::with(['user', 'category'])
                ->where('status', 'active')
                ->where('category_id', $catModel->id)
                ->orderBy('user_id')
                ->get();
            
            // Group products by user
            $hotelOwners = $hotelProducts->groupBy('user_id')->map(function($products, $userId) {
                $user = $products->first()->user;
                return [
                    'user' => $user,
                    'hotels' => $products,
                    'total_hotels' => $products->count(),
                    'user_rating' => $user->rating ?? 0,
                    'user_location' => $user->location ?? 'N/A'
                ];
            })->values();
        }
    }
    

    // -----------------------------------------
    // SERVICES PAGE
    // -----------------------------------------
    if (request()->is('services*')) {
        $serviceCategories = Category::where('type', 'service')->get();

        $servicesQuery = Product::with(['user', 'category'])
            ->where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('type', 'service'));

        // Filter by category
        if ($category) {
            $catModel = Category::where('slug', $category)
                ->where('type', 'service')
                ->first();

            if ($catModel) {
                $servicesQuery->where('category_id', $catModel->id);
            }
        }

        // 🔹 PRICE FILTER
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $servicesQuery->where('price', '>=', (float) $request->min_price);
        }
        
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $servicesQuery->where('price', '<=', (float) $request->max_price);
        }

        // 🔹 CONDITION FILTER (for services)
        if ($request->filled('condition') && $request->condition !== '') {
            $servicesQuery->where('condition', $request->condition);
        }

        // 🔹 LOCATION FILTER - For services
        if ($request->filled('location') && $request->location !== '') {
            $location = $request->location;
            
            if ($location === 'Online') {
                // Search for online/remote services
                $servicesQuery->where(function($q) {
                    $q->where('location', 'LIKE', '%online%')
                      ->orWhere('location', 'LIKE', '%remote%')
                      ->orWhere('location', 'LIKE', '%virtual%');
                });
            } else {
                // Check if it's a state
                $isState = \App\Models\Location::where('state', $location)->exists();
                
                if ($isState) {
                    // It's a state - get all LGAs in this state
                    $lgas = \App\Models\Location::where('state', $location)
                        ->pluck('lga')
                        ->unique()
                        ->toArray();
                    
                    if (!empty($lgas)) {
                        $servicesQuery->where(function($q) use ($lgas) {
                            foreach ($lgas as $lga) {
                                $q->orWhere('location', 'LIKE', '%' . $lga . '%');
                            }
                        });
                    }
                } else {
                    // Assume it's an LGA or location name
                    $servicesQuery->where(function($q) use ($location) {
                        $q->where('location', 'LIKE', '%' . $location . '%')
                          ->orWhere('location', 'LIKE', '%' . $location . '%');
                    });
                }
            }
        }

        // 🔹 NEGOTIABLE FILTER
        if ($request->has('negotiable') && $request->negotiable == '1') {
            $servicesQuery->where('negotiable', true);
        }

        // 🔹 AVAILABLE NOW FILTER (for services)
        if ($request->has('available') && $request->available == '1') {
            // Assuming you have an 'available' or 'is_available' field
            // Or you might want to filter by service provider availability
            $servicesQuery->where('is_available', true); // Adjust based on your schema
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $servicesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // 🔹 SORTING
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $servicesQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $servicesQuery->orderBy('price', 'desc');
                break;
            case 'rating':
                $servicesQuery->orderBy('rating', 'desc');
                break;
            case 'featured':
                $servicesQuery->orderByRaw(
                    'CASE WHEN boost_expires_at IS NOT NULL AND boost_expires_at > ? THEN 0 ELSE 1 END',
                    [now()]
                )->orderBy('featured', 'desc')->latest();
                break;
            case 'popular':
                $servicesQuery->orderBy('views', 'desc')->latest();
                break;
            default:
                $servicesQuery->latest();
                break;
        }

        // Paginate services
        $featuredServices = $servicesQuery->paginate(20)->withQueryString();

        return view('services.index', compact(
            'featuredServices',
            'serviceCategories',
            'categories',
            'category'
        ));
    }


    // -----------------------------------------
    // PRODUCTS PAGE - With Hotel Special Case
    // -----------------------------------------
    $productsQuery = Product::with(['user', 'category'])
        ->where('status', 'active');

    // Filter
    if ($category && !$isHotelCategory) { // Skip category filter for hotels since we handle it differently
        $catModel = Category::where('slug', $category)->first();
        if ($catModel) {
            $productsQuery->where('category_id', $catModel->id);
        }
    }

    // 🔹 PRICE FILTER
    if ($request->filled('min_price') && is_numeric($request->min_price)) {
        $productsQuery->where('price', '>=', (float) $request->min_price);
    }
    
    if ($request->filled('max_price') && is_numeric($request->max_price)) {
        $productsQuery->where('price', '<=', (float) $request->max_price);
    }

    // 🔹 CONDITION FILTER - Handle both array and single value
    if ($request->filled('condition')) {
        $conditions = $request->condition;
        
        // Handle both array and string inputs
        if (!is_array($conditions)) {
            $conditions = [$conditions];
        }
        
        // Remove empty values
        $validConditions = array_filter($conditions, function($value) {
            return $value !== null && $value !== '' && $value !== 'null';
        });
        
        // Only apply filter if we have valid non-empty conditions
        if (!empty($validConditions)) {
            $productsQuery->whereIn('condition', $validConditions);
        }
    }

    // 🔹 LOCATION FILTER - Match state to LGAs
    if ($request->filled('location') && $request->location !== '') {
        $location = $request->location;
        
        // Check if it's a state (by checking if it exists in locations table as a state)
        $isState = \App\Models\Location::where('state', $location)->exists();
        
        if ($isState) {
            // It's a state - get all LGAs in this state
            $lgas = \App\Models\Location::where('state', $location)
                ->pluck('lga')
                ->unique()
                ->toArray();
            
            if (!empty($lgas)) {
                $productsQuery->where(function($q) use ($lgas) {
                    foreach ($lgas as $lga) {
                        $q->orWhere('location', 'LIKE', '%' . $lga . '%');
                    }
                });
            }
        } else {
            // Assume it's an LGA or partial LGA name
            $productsQuery->where('location', 'LIKE', '%' . $location . '%');
        }
    }

    // 🔹 NEGOTIABLE FILTER
    if ($request->has('negotiable') && $request->negotiable == '1') {
        $productsQuery->where('negotiable', true);
    }

    // Search
    if ($request->has('search')) {
        $search = $request->search;
        $productsQuery->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    // 🔹 SORTING
    $sort = $request->get('sort', 'latest');
    switch ($sort) {
        case 'price_low':
            $productsQuery->orderBy('price', 'asc');
            break;
        case 'price_high':
            $productsQuery->orderBy('price', 'desc');
            break;
        case 'rating':
            $productsQuery->orderBy('rating', 'desc');
            break;
        case 'featured':
            $productsQuery->orderByRaw(
                'CASE WHEN boost_expires_at IS NOT NULL AND boost_expires_at > ? THEN 0 ELSE 1 END',
                [now()]
            )->orderBy('featured', 'desc')->orderBy('created_at', 'desc');
            break;
        default:
            $productsQuery->latest();
            break;
    }

    // For hotel category, we won't use the normal products query
    if ($isHotelCategory) {
        $products = collect(); // Empty collection since we're showing grouped hotels
    } else {
        // Paginate products for non-hotel categories
        $products = $productsQuery->paginate(20)->withQueryString();
    }

    // EXTRA DATA FOR HOME PAGE ONLY
    if (request()->is('/')) {
        $featuredStores = Store::with(['user', 'products'])
            ->where('is_active', true)
            ->whereHas('products')
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
        $featuredProducts = Product::with(['user', 'category'])
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where('featured', true)
                    ->orWhere(function ($q2) {
                        $q2->where('is_boost_carousel_pick', true)
                            ->where('boost_expires_at', '>', now());
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
        
        // SERVICE data for homepage sections (NOT paginated)
        $serviceCategories = Category::where('type', 'service')->get();

        $featuredServices = Product::with(['user', 'category'])
            ->where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('type', 'service'))
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();
        
        $featuredHotels = Product::where('category_id', 35)
            ->take(10)
            ->get();

        // WELCOME PAGE RETURN
        return view('welcome', compact(
            'products',
            'categories',
            'category',
            'featuredStores',
            'featuredProducts',
            'featuredServices',
            'featuredHotels',
            'serviceCategories',
            'isHotelCategory',
            'hotelOwners'
        ));
    }
    
    // For products page with hotel category
    if ($isHotelCategory) {
        return view('products.hotels', compact(
            'categories',
            'category',
            'hotelOwners',
            'isHotelCategory',
            'featuredServices' // Add this
        ));
    }
    
    // Normal products view (for /products route)
    // Get a default set of featured services for the products page
    $featuredServices = Product::with(['user', 'category'])
        ->where('status', 'active')
        ->whereHas('category', fn($q) => $q->where('type', 'service'))
        ->orderBy('created_at', 'desc')
        ->limit(12)
        ->get();
    
    return view('products.index', compact(
        'products',
        'categories',
        'category',
        'isHotelCategory',
        'featuredServices' // Add this
    ));
}
    
    

public function userHotels(Request $request, $userId)
{
    $user = User::findOrFail($userId);
    $categories = Category::all();
    
    // Find Hotel category
    $hotelCategory = Category::where('name', 'LIKE', '%hotel%')
        ->orWhere('name', 'LIKE', '%Hotel%')
        ->first();
    
    if (!$hotelCategory) {
        abort(404, 'Hotel category not found');
    }
    
    $hotels = Product::with(['user', 'category'])
        ->where('status', 'active')
        ->where('user_id', $userId)
        ->where('category_id', $hotelCategory->id)
        ->orderBy('created_at', 'desc')
        ->paginate(12);
    
    return view('products.user-hotels', compact('user', 'hotels', 'categories'));
}




public function homeByCategory(Request $request, $category = null)
{
    // Shared categories for navigation
    $categories = Category::all();

    $isHotelCategory = false;
    $hotelOwners = collect();

    // -----------------------------------------
    // CATEGORY CHECK (HOTEL SPECIAL CASE)
    // -----------------------------------------
    if ($category) {
        $catModel = Category::where('slug', $category)->first();

        if ($catModel && strtolower($catModel->name) === 'hotel') {
            $isHotelCategory = true;

            $hotelProducts = Product::with(['user', 'category'])
                ->where('status', 'active')
                ->where('category_id', $catModel->id)
                ->orderBy('user_id')
                ->get();

            $hotelOwners = $hotelProducts
                ->groupBy('user_id')
                ->map(function ($products) {
                    $user = $products->first()->user;

                    return [
                        'user' => $user,
                        'hotels' => $products,
                        'total_hotels' => $products->count(),
                        'user_rating' => $user->rating ?? 0,
                        'user_location' => $user->location ?? 'N/A',
                    ];
                })
                ->values();
        }
    }

    // -----------------------------------------
    // PRODUCTS (CATEGORY FILTER + PAGINATION + FILTERS)
    // -----------------------------------------
    $products = collect(); // default

      if (!$isHotelCategory) {
        $productsQuery = Product::with(['user', 'category'])
            ->where('status', 'active');

        // Category filter
        if ($category) {
            $catModel = Category::where('slug', $category)->first();
            if ($catModel) {
                $productsQuery->where('category_id', $catModel->id);
                
                // Debug: Log category info
                \Log::info("Filtering by category: {$catModel->name} (ID: {$catModel->id})");
            }
        }

        // 🔹 PRICE FILTER
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $minPrice = (float) $request->min_price;
            $productsQuery->where('price', '>=', $minPrice);
            \Log::info("Min price filter: ₦{$minPrice}");
        }
        
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $maxPrice = (float) $request->max_price;
            $productsQuery->where('price', '<=', $maxPrice);
            \Log::info("Max price filter: ₦{$maxPrice}");
        }

        // 🔹 CONDITION FILTER 
      
            if ($request->filled('condition') && $request->condition !== '') {
                $productsQuery->where('condition', $request->condition);
               
            }

        // 🔹 LOCATION FILTER
        if ($request->filled('location') && $request->location !== '') {
            $location = $request->location;
            $productsQuery->where('location', 'LIKE', '%' . $location . '%');
            \Log::info("Location filter: {$location}");
        }

        // 🔹 NEGOTIABLE FILTER
        if ($request->has('negotiable') && $request->negotiable == '1') {
            $productsQuery->where('negotiable', true);
            \Log::info("Negotiable filter: true");
        }

        // 🔹 SORTING
        $sort = $request->get('sort', 'latest');
        \Log::info("Sort by: {$sort}");
        
        switch ($sort) {
            case 'price_low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'rating':
                $productsQuery->orderBy('rating', 'desc');
                break;
            case 'featured':
                $productsQuery->orderByRaw(
                'CASE WHEN boost_expires_at IS NOT NULL AND boost_expires_at > ? THEN 0 ELSE 1 END',
                [now()]
            )->orderBy('featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->latest();
                break;
        }

        // Debug: Get the SQL query before pagination
        $sql = $productsQuery->toSql();
        $bindings = $productsQuery->getBindings();
        \Log::info("Final SQL Query:", ['sql' => $sql, 'bindings' => $bindings]);

        // PAGINATION
        $products = $productsQuery->paginate(12)->withQueryString();
        
        \Log::info("Total products found: {$products->total()}");
    }

    // -----------------------------------------
    // HOME PAGE EXTRAS (NOT PAGINATED)
    // -----------------------------------------
    $featuredStores = Store::with(['user', 'products'])
        ->where('is_active', true)
        ->whereHas('products')
        ->inRandomOrder()
        ->limit(10)
        ->get();

    $featuredProducts = Product::with(['user', 'category'])
        ->where('status', 'active')
        ->where(function ($q) {
            $q->where('featured', true)
                ->orWhere(function ($q2) {
                    $q2->where('is_boost_carousel_pick', true)
                        ->where('boost_expires_at', '>', now());
                });
        })
        ->latest()
        ->limit(12)
        ->get();

    $serviceCategories = Category::where('type', 'service')->get();

    $featuredServices = Product::with(['user', 'category'])
        ->where('status', 'active')
        ->whereHas('category', fn ($q) => $q->where('type', 'service'))
        ->latest()
        ->limit(12)
        ->get();

    $featuredHotels = Product::where('category_id', 35)
        ->latest()
        ->limit(10)
        ->get();

    return view('welcome', compact(
        'products',
        'categories',
        'category',
        'featuredStores',
        'featuredProducts',
        'featuredServices',
        'featuredHotels',
        'serviceCategories',
        'isHotelCategory',
        'hotelOwners'
    ));
}




    public function show($slug)
    {
        $product = Product::with(['user', 'category', 'reviews' => fn ($q) => $q->with('user')->latest()])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // The current customer's own review (if any), so the form can be
        // pre-filled as an edit rather than a fresh submission.
        $userReview = Auth::check()
            ? $product->reviews->firstWhere('user_id', Auth::id())
            : null;

        // Get related products
        $relatedProducts = Product::with(['user', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();



        return view('products.product-detail', compact('product', 'relatedProducts', 'userReview'));
    }

    /**
     * All currently-boosted products (vendor-paid, via the boost
     * subscription feature) — not the same set as $featuredProducts on the
     * home page carousel, which mixes in admin-curated "featured" picks too.
     */
    public function boosted(Request $request)
    {
        $products = Product::with(['user', 'category'])
            ->where('status', 'active')
            ->boosted()
            ->orderBy('boost_expires_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('products.boosted', compact('products'));
    }

    public function byCategory($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $products = Product::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('products.category', compact('products', 'category'));
    }


   
        public function trackView(Request $request)
        {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);
        
            $product = Product::find($request->product_id);
            
          
         $product->increment('views');
            
            return response()->json([
                'success' => true,
                'views_count' =>  $product->views
            ]);
        }




    public function getInquiriesCount($productId)
    {
        $inquiriesCount = VendorContact::where('product_id', $productId)->count();

        return response()->json([
            'success' => true,
            'inquiries_count' => $inquiriesCount
        ]);
    }
    public function storeContact(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'contact_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $vendor = User::findOrFail($request->vendor_id);
            $product = Product::findOrFail($request->product_id);

            // Check if contact already exists for this user today to avoid duplicates
            $existingContact = VendorContact::where('user_id', Auth::id())
                ->where('vendor_id', $vendor->id)
                ->where('product_id', $product->id)
                ->whereDate('contact_date', today())
                ->first();

            if (!$existingContact) {
                VendorContact::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendor->id,
                    'product_id' => $product->id,
                    'vendor_name' => $vendor->business_name ?: $vendor->full_name,
                    'vendor_contact_info' => $this->getVendorContactInfo($vendor, $request->contact_method),
                    'product_name' => $product->title,
                    'contact_date' => now(),
                    'contact_method' => $request->contact_method,
                    'notes' => $request->notes,
                    'status' => 'contacted'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contact tracked successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track contact: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getVendorContactInfo($vendor, $contactMethod)
    {
        if ($contactMethod === 'whatsapp') {
            return $vendor->whatsapp_number ?: $vendor->phone ?: $vendor->email;
        } elseif ($contactMethod === 'phone') {
            return $vendor->phone ?: $vendor->whatsapp_number ?: $vendor->email;
        }

        return $vendor->email ?: $vendor->phone;
    }






    public function create($type)
    {
        // Use the parameter
        $categories = Category::active()
            ->mainCategories()
            ->byType($type) // if you have scopeByType
            ->get();

        return view('products.create', compact('categories', 'type'));
    }



    public function store_users_vendors_me(Request $request, $category = null)
    {
        // Get user's current location (you can get this from request or session)
        $userLat = $request->input('lat'); // or from session: session('user_lat')
        $userLng = $request->input('lng'); // or from session: session('user_lng')

        // If no coordinates in request, try to get from user's profile or use default
        if (!$userLat || !$userLng) {
            $user = Auth::user();
            if ($user && $user->local_government) {
                // Get coordinates from locations table based on user's LGA
                $location = Location::where('lga', $user->local_government)->first();
                if ($location) {
                    $userLat = $location->latitude;
                    $userLng = $location->longitude;
                }
            }

            // Fallback to a default location (e.g., Lagos)
            if (!$userLat || !$userLng) {
                $userLat = 6.5244;
                $userLng = 3.3792;
            }
        }

        // Get vendors in the same local government area
        $vendors = User::where('user_type', 'vendor')
            ->where('local_government', Auth::user()->local_government ?? 'Damban')
            ->where('status', 'active')
            ->get();

        // Calculate distances and get nearby vendors
        $nearbyVendors = $this->getNearbyVendors($vendors, $userLat, $userLng);

        // Get products from nearby vendors
        $vendorIds = $nearbyVendors->pluck('id')->toArray();

        $productsQuery = Product::whereIn('user_id', $vendorIds)
            ->where('status', 'active');

        // Filter by category if provided
        if ($category) {
            $categoryModel = Category::where('slug', $category)->first();
            if ($categoryModel) {
                $productsQuery->where('category_id', $categoryModel->id);
            }
        }

        $products = $productsQuery->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get categories for filter
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Featured items
        // $featuredStores = User::where('user_type', 'vendor')
        //     ->where('status', 'active')
        //     ->where('featured', true)
        //     ->limit(6)
        //     ->get();


      
            
        $featuredStores = Store::with(['user', 'products'])
            ->where('is_active', true)
            ->whereHas('products') // ensures store has products
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
      

            
        $featuredProducts = Product::where('status', 'active')
            ->where('featured', true)
            ->limit(8)
            ->get();
            


        return view('store', compact(
            'products',
            'categories',
            'category',
            'featuredStores',
            'featuredProducts',
            'nearbyVendors',
            'userLat',
            'userLng'
        ));
    }

    public function store_users_vendors_me_spatial(Request $request, $category = null)
    {
        $userLat = $request->input('lat', 6.5244);
        $userLng = $request->input('lng', 3.3792);
        $radiusKm = 50; // Search radius in kilometers

        // Get nearby vendors using spatial query
        $nearbyVendors = User::select('users.*')
            ->join('locations', 'locations.lga', '=', 'users.local_government')
            ->where('users.user_type', 'vendor')
            ->where('users.status', 'active')
            ->whereRaw("
            (6371 * acos(cos(radians(?)) * cos(radians(locations.latitude)) *
            cos(radians(locations.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(locations.latitude)))) <= ?
        ", [$userLat, $userLng, $userLat, $radiusKm])
            ->with(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->each(function ($vendor) use ($userLat, $userLng) {
                // Calculate distance for each vendor
                $vendorLocation = Location::where('lga', $vendor->local_government)->first();
                if ($vendorLocation) {
                    $vendor->distance = round($this->calculateDistance(
                        $userLat,
                        $userLng,
                        $vendorLocation->latitude,
                        $vendorLocation->longitude
                    ), 2);
                    $vendor->distance_unit = 'km';
                }
            })
            ->sortBy('distance');

        // Get products from nearby vendors
        $vendorIds = $nearbyVendors->pluck('id')->toArray();

        $productsQuery = Product::whereIn('user_id', $vendorIds)
            ->where('status', 'active');

        if ($category) {
            $categoryModel = Category::where('slug', $category)->first();
            if ($categoryModel) {
                $productsQuery->where('category_id', $categoryModel->id);
            }
        }

        $products = $productsQuery->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('status', 'active')->get();
        $featuredStores = User::where('user_type', 'vendor')->where('featured', true)->limit(6)->get();
        $featuredProducts = Product::where('featured', true)->limit(8)->get();

        return view('store', compact(
            'products',
            'categories',
            'category',
            'featuredStores',
            'featuredProducts',
            'nearbyVendors',
            'userLat',
            'userLng'
        ));
    }

    /**
     * Calculate distances and get nearby vendors
     */
    private function getNearbyVendors($vendors, $userLat, $userLng, $radiusKm = 50)
    {
        $nearbyVendors = collect();

        foreach ($vendors as $vendor) {
            // Get vendor's location coordinates
            $vendorLocation = Location::where('lga', $vendor->local_government)->first();

            if ($vendorLocation && $vendorLocation->latitude && $vendorLocation->longitude) {
                $distance = $this->calculateDistance(
                    $userLat,
                    $userLng,
                    $vendorLocation->latitude,
                    $vendorLocation->longitude
                );

                // Add distance to vendor object
                $vendor->distance = round($distance, 2);
                $vendor->distance_unit = 'km';

                if ($distance <= $radiusKm) {
                    $nearbyVendors->push($vendor);
                }
            }
        }

        // Sort by distance (nearest first)
        return $nearbyVendors->sortBy('distance');
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }



    public function store(Request $request)
    {
        // Debug: Check what's being received
        Log::info('Product Store Request:', $request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,used_like_new,used_good,used_fair',
            'location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'images' => 'required|array|min:5|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'specifications' => 'nullable',
            'negotiable' => 'boolean',
        ]);

        try {
            // Handle image uploads - store in public/product-images directory
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Create directory if it doesn't exist
                    $directory = public_path('product-images');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    // Move image to public/product-images directory
                    $image->move($directory, $filename);

                    // Store relative path for database
                    $imagePaths[] = 'product-images/' . $filename;
                }
            }

            // Generate slug
            $slug = $this->generateUniqueSlug($request->title);

            // Handle specifications
            $specifications = [];
            if ($request->has('specifications') && is_array($request->specifications)) {
                $specifications = array_filter($request->specifications, function ($value) {
                    return !empty($value) && $value !== '';
                });
            }
            $user = Auth::user();

            $storeId = $user->store ? $user->store->id : null;

            // Create product
            $product = Product::create([
                'user_id' => Auth::id(),
                'store_id' => $storeId,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'old_price' => $request->old_price,
                'condition' => $request->condition,
                'location' => $request->location,
                'quantity' => $request->quantity,
                'images' => $imagePaths,
                'specifications' => $specifications,
                'negotiable' => $request->boolean('negotiable'),
                'tags' => $request->tags ?? [],
                'status' => 'active',
                'meta_title' => $request->title,
                'meta_description' => Str::limit($request->description, 160),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product listed successfully!',
                'product' => $product,
                'redirect_url' => route('products.added', $product->id)
            ]);
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }
    public function added($id)
    {
        $product = Product::with(['user', 'category'])->findOrFail($id);
        $relatedProducts = Product::with(['user', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
        return view('products.show', compact('product', 'relatedProducts'));
    }


    public function getSubcategories($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $subcategories = $category->children()->active()->get();

        return response()->json([
            'success' => true,
            'subcategories' => $subcategories,
            'specification_fields' => $category->getSpecificationFields()
        ]);
    }


    public function getSpecifications($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // Get specification fields for the category
        $specificationFields = $category->getSpecificationFields();

        return response()->json([
            'success' => true,
            'fields' => $specificationFields
        ]);
    }
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }


   public function edit($slug)
{
    $product = Product::where('slug', $slug)
        ->when(!auth()->user()->isAdmin(), function($query) {
            // Restrict to owner if not admin
            $query->where('user_id', Auth::id());
        })
        ->firstOrFail();

    $categories = Category::active()->mainCategories()->get();

    return view('products.edit', compact('product', 'categories'));
}

    
    
    

    public function update(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,used_like_new,used_good,used_fair',
            'location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'images' => 'sometimes|array|min:5|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'specifications' => 'nullable',
            'negotiable' => 'boolean',
            'status' => 'required|in:active,inactive,pending',
        ]);

        try {
            // Handle image uploads if new images are provided
            $imagePaths = $product->images ?? [];

            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($product->images as $oldImage) {
                    $oldImagePath = public_path($oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Upload new images
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $directory = public_path('product-images');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($directory, $filename);
                    $imagePaths[] = 'product-images/' . $filename;
                }
            }

            // Handle specifications
            $specifications = [];
            if ($request->has('specifications') && is_array($request->specifications)) {
                $specifications = array_filter($request->specifications, function ($value) {
                    return !empty($value) && $value !== '';
                });
            }

            // Generate new slug if title changed
            $newSlug = $product->slug;
            if ($request->title !== $product->title) {
                $newSlug = $this->generateUniqueSlug($request->title);
            }

            $user = Auth::user();
            $storeId = $user->store ? $user->store->id : null;

            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'store_id' => $storeId,
                'title' => $request->title,
                'slug' => $newSlug,
                'description' => $request->description,
                'price' => $request->price,
                'old_price' => $request->old_price,
                'condition' => $request->condition,
                'location' => $request->location,
                'quantity' => $request->quantity,
                'images' => $imagePaths,
                'specifications' => $specifications,
                'negotiable' => $request->boolean('negotiable'),
                'status' => $request->status,
                'meta_title' => $request->title,
                'meta_description' => Str::limit($request->description, 160),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product,
                'redirect_url' => route('products.added', $product->id)
            ]);
        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($slug)
    {
        try {
            $product = Product::where('slug', $slug)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Delete associated images
            foreach ($product->images as $image) {
                $imagePath = public_path($image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $product->delete();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Product deleted successfully!',
            //     'redirect_url' => route('vendor.dashboard')
            // ]);

            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Product deletion error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Error deleting product: ' . $e->getMessage()
            // ], 500);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    // app/Http/Controllers/ProductController.php

/**
 * Display vendor advert reports with filters for active/inactive
 * 
 * 1. When they were registered
 * 2. Their advert durations
 * 3. Amount Paid by each of the active adverts
 * 4. Their active email address and contact number
 * 5. Expiration date of each of those adverts
 */
public function vendorAdvertReport(Request $request)
{
    $statusFilter = $request->get('status', 'all'); // all, active, inactive
    
    // Base query for vendors
    $vendorsQuery = User::where('user_type', 'vendor')
        ->with([
            'subscriptions' => function ($query) use ($statusFilter) {
                $query->when($statusFilter === 'active', function ($q) {
                    $q->where('status', 'active')
                      ->where('expires_at', '>', now());
                })
                ->when($statusFilter === 'inactive', function ($q) {
                    $q->where(function ($q) {
                        $q->where('status', '!=', 'active')
                          ->orWhere('expires_at', '<=', now());
                    });
                })
                ->latest();
            },
            'products' => function ($query) use ($statusFilter) {
                $query->with(['category'])
                    ->when($statusFilter === 'active', function ($q) {
                        $q->where('status', 'active');
                    })
                    ->when($statusFilter === 'inactive', function ($q) {
                        $q->where('status', '!=', 'active');
                    })
                    ->latest();
            }
        ]);

    // Apply product existence filter
    if ($statusFilter !== 'all') {
        $vendorsQuery->whereHas('products', function ($query) use ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->where('status', 'active');
            } elseif ($statusFilter === 'inactive') {
                $query->where('status', '!=', 'active');
            }
        });
    }

    // Apply subscription existence filter
    if ($statusFilter === 'active') {
        $vendorsQuery->whereHas('subscriptions', function ($query) {
            $query->where('status', 'active')
                  ->where('expires_at', '>', now());
        });
    } elseif ($statusFilter === 'inactive') {
        $vendorsQuery->where(function ($query) {
            $query->doesntHave('subscriptions')
                  ->orWhereHas('subscriptions', function ($q) {
                      $q->where('status', '!=', 'active')
                        ->orWhere('expires_at', '<=', now());
                  });
        });
    }

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $vendorsQuery->where(function ($query) use ($search) {
            $query->where('business_name', 'like', "%$search%")
                  ->orWhere('full_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
        });
    }

    // Apply sorting
    $sort = $request->get('sort', 'newest');
    switch ($sort) {
        case 'oldest':
            $vendorsQuery->orderBy('created_at', 'asc');
            break;
        case 'most_ads':
            $vendorsQuery->withCount(['products as active_products_count' => function ($query) {
                $query->where('status', 'active');
            }])->orderBy('active_products_count', 'desc');
            break;
        case 'highest_paid':
            $vendorsQuery->withSum(['subscriptions as total_paid_sum' => function ($query) {
                $query->where('status', 'active');
            }], 'amount_paid')->orderBy('total_paid_sum', 'desc');
            break;
        default:
            $vendorsQuery->orderBy('created_at', 'desc');
            break;
    }

    $vendors = $vendorsQuery->paginate(50);

    // Process vendor data for the view
    $vendorReports = $vendors->map(function ($vendor) use ($statusFilter) {
        // Get most recent subscription
        $activeSubscription = $vendor->subscriptions->first();
        $hasActiveSubscription = $activeSubscription && 
                                $activeSubscription->status === 'active' && 
                                $activeSubscription->expires_at > now();
        
        // Get products based on status filter
        $products = $vendor->products;
        if ($statusFilter === 'active') {
            $products = $products->where('status', 'active');
        } elseif ($statusFilter === 'inactive') {
            $products = $products->where('status', '!=', 'active');
        }

        // Calculate advert durations for each product
        $productsWithDuration = $products->map(function ($product) use ($activeSubscription, $hasActiveSubscription) {
            // Calculate duration (in days) based on subscription or product creation
            $durationDays = 0;
            $expirationDate = null;
            $amountPaid = 0;
            $subscriptionStatus = 'none';
            
            if ($hasActiveSubscription) {
                $subscriptionStatus = 'active';
                $startDate = $activeSubscription->created_at;
                $expirationDate = $activeSubscription->expires_at;
                $durationDays = $startDate->diffInDays($expirationDate);
                $amountPaid = $activeSubscription->amount_paid ?? $activeSubscription->price ?? 0;
            } elseif ($activeSubscription) {
                $subscriptionStatus = 'inactive';
                $expirationDate = $activeSubscription->expires_at;
                $durationDays = $activeSubscription->created_at->diffInDays($expirationDate);
                $amountPaid = $activeSubscription->amount_paid ?? $activeSubscription->price ?? 0;
            } else {
                $subscriptionStatus = 'none';
                $durationDays = 30; // Default duration
                $expirationDate = $product->created_at->addDays(30);
            }
            
            $isExpired = $expirationDate ? $expirationDate->isPast() : true;
            $isAdvertActive = $product->status === 'active' && !$isExpired;
            
            return [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category->name ?? 'N/A',
                'created_at' => $product->created_at,
                'duration_days' => $durationDays,
                'expiration_date' => $expirationDate,
                'amount_paid' => $amountPaid,
                'is_expired' => $isExpired,
                'advert_status' => $product->status,
                'subscription_status' => $subscriptionStatus,
                'is_active' => $isAdvertActive,
                'days_until_expiry' => $expirationDate ? now()->diffInDays($expirationDate, false) : null,
            ];
        });

        // Calculate totals
        $totalAmountPaid = $productsWithDuration->sum('amount_paid');
        $activeAdsCount = $productsWithDuration->where('is_active', true)->count();
        $inactiveAdsCount = $productsWithDuration->where('is_active', false)->count();

        return [
            'vendor' => [
                'id' => $vendor->id,
                'business_name' => $vendor->business_name,
                'full_name' => $vendor->full_name,
                'registration_date' => $vendor->created_at,
                'email' => $vendor->email,
                'phone' => $vendor->phone,
                'whatsapp' => $vendor->whatsapp_number,
                'active_contact' => $vendor->phone ?: $vendor->whatsapp_number ?: $vendor->email,
            ],
            'subscription' => $activeSubscription ? [
                'plan_name' => $activeSubscription->plan_name,
                'price' => $activeSubscription->price,
                'amount_paid' => $activeSubscription->amount_paid,
                'status' => $activeSubscription->status,
                'starts_at' => $activeSubscription->created_at,
                'expires_at' => $activeSubscription->expires_at,
                'duration_days' => $activeSubscription->created_at->diffInDays($activeSubscription->expires_at),
                'is_active' => $hasActiveSubscription,
            ] : null,
            'products' => $productsWithDuration,
            'total_active_ads' => $activeAdsCount,
            'total_inactive_ads' => $inactiveAdsCount,
            'total_amount_paid' => $totalAmountPaid,
            'vendor_status' => $hasActiveSubscription ? 'active' : 'inactive',
        ];
    });

    // Filtering options
    $filters = [
        'sort' => $sort,
        'search' => $request->get('search', ''),
        'status' => $statusFilter,
    ];

    return view('admin.vendor-advert-report', compact('vendorReports', 'vendors', 'filters'));
}

/**
 * Export vendor advert report to CSV with status filter
 */
public function exportVendorAdvertReport(Request $request)
{
    $statusFilter = $request->get('status', 'all');
    
    $vendorsQuery = User::where('user_type', 'vendor')
        ->with(['subscriptions', 'products.category']);

    // Apply status filter
    if ($statusFilter === 'active') {
        $vendorsQuery->whereHas('subscriptions', function ($query) {
            $query->where('status', 'active')
                  ->where('expires_at', '>', now());
        })->whereHas('products', function ($query) {
            $query->where('status', 'active');
        });
    } elseif ($statusFilter === 'inactive') {
        $vendorsQuery->where(function ($query) {
            $query->doesntHave('subscriptions')
                  ->orWhereHas('subscriptions', function ($q) {
                      $q->where('status', '!=', 'active')
                        ->orWhere('expires_at', '<=', now());
                  });
        });
    }

    $vendors = $vendorsQuery->orderBy('created_at', 'desc')->get();

    $statusText = $statusFilter === 'all' ? 'all' : ($statusFilter === 'active' ? 'active' : 'inactive');
    $fileName = "vendor-advert-report-{$statusText}-" . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () use ($vendors, $statusFilter) {
        $file = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($file, [
            'Vendor ID',
            'Business Name',
            'Vendor Name',
            'Registration Date',
            'Email',
            'Contact Number',
            'Vendor Status',
            'Advert ID',
            'Advert Title',
            'Category',
            'Advert Created',
            'Advert Status',
            'Duration (Days)',
            'Amount Paid',
            'Expiration Date',
            'Days Until Expiry',
            'Subscription Status',
            'Subscription Plan',
            'Subscription Start',
            'Subscription End'
        ]);

        foreach ($vendors as $vendor) {
            $activeSubscription = $vendor->subscriptions->sortByDesc('created_at')->first();
            $hasActiveSubscription = $activeSubscription && 
                                   $activeSubscription->status === 'active' && 
                                   $activeSubscription->expires_at > now();
            
            $vendorStatus = $hasActiveSubscription ? 'Active' : 'Inactive';
            
            // Get products based on status filter
            $products = $vendor->products;
            if ($statusFilter === 'active') {
                $products = $products->where('status', 'active');
            } elseif ($statusFilter === 'inactive') {
                $products = $products->where('status', '!=', 'active');
            }

            foreach ($products as $product) {
                $expirationDate = null;
                $amountPaid = 0;
                $durationDays = 0;
                $subscriptionStatus = 'None';
                $daysUntilExpiry = null;
                
                if ($activeSubscription) {
                    $subscriptionStatus = $activeSubscription->status;
                    $expirationDate = $activeSubscription->expires_at;
                    $durationDays = $activeSubscription->created_at->diffInDays($expirationDate);
                    $amountPaid = $activeSubscription->amount_paid ?? $activeSubscription->price ?? 0;
                    
                    if ($expirationDate) {
                        $daysUntilExpiry = now()->diffInDays($expirationDate, false);
                        $daysUntilExpiry = $daysUntilExpiry > 0 ? $daysUntilExpiry : 0;
                    }
                } else {
                    $expirationDate = $product->created_at->addDays(30);
                    $durationDays = 30;
                    $daysUntilExpiry = now()->diffInDays($expirationDate, false);
                    $daysUntilExpiry = $daysUntilExpiry > 0 ? $daysUntilExpiry : 0;
                }
                
                $advertStatus = $product->status === 'active' ? 'Active' : 'Inactive';
                $isExpired = $expirationDate ? $expirationDate->isPast() : true;
                
                if ($isExpired && $product->status === 'active') {
                    $advertStatus = 'Expired';
                }

                fputcsv($file, [
                    $vendor->id,
                    $vendor->business_name ?? 'N/A',
                    $vendor->full_name,
                    $vendor->created_at->format('Y-m-d'),
                    $vendor->email,
                    $vendor->phone ?: $vendor->whatsapp_number ?: 'N/A',
                    $vendorStatus,
                    $product->id,
                    $product->title,
                    $product->category->name ?? 'N/A',
                    $product->created_at->format('Y-m-d'),
                    $advertStatus,
                    $durationDays,
                    number_format($amountPaid, 2),
                    $expirationDate ? $expirationDate->format('Y-m-d') : 'N/A',
                    $daysUntilExpiry,
                    $subscriptionStatus,
                    $activeSubscription->plan_name ?? 'N/A',
                    $activeSubscription ? $activeSubscription->created_at->format('Y-m-d') : 'N/A',
                    $activeSubscription ? $activeSubscription->expires_at->format('Y-m-d') : 'N/A'
                ]);
            }
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Export specific vendor report (single vendor)
 */
public function exportVendorReport($vendorId)
{
    $vendor = User::where('user_type', 'vendor')
        ->where('id', $vendorId)
        ->with(['subscriptions', 'products.category'])
        ->firstOrFail();

    $fileName = "vendor-report-{$vendor->id}-" . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () use ($vendor) {
        $file = fopen('php://output', 'w');
        
        // Vendor Information Header
        fputcsv($file, ['VENDOR INFORMATION']);
        fputcsv($file, ['Business Name', $vendor->business_name ?? 'N/A']);
        fputcsv($file, ['Full Name', $vendor->full_name]);
        fputcsv($file, ['Email', $vendor->email]);
        fputcsv($file, ['Phone', $vendor->phone ?? 'N/A']);
        fputcsv($file, ['WhatsApp', $vendor->whatsapp_number ?? 'N/A']);
        fputcsv($file, ['Registration Date', $vendor->created_at->format('Y-m-d H:i:s')]);
        fputcsv($file, []); // Empty row
        
        // Subscription Information
        $activeSubscription = $vendor->subscriptions->first();
        fputcsv($file, ['SUBSCRIPTION INFORMATION']);
        if ($activeSubscription) {
            fputcsv($file, ['Plan Name', $activeSubscription->plan_name]);
            fputcsv($file, ['Status', $activeSubscription->status]);
            fputcsv($file, ['Amount Paid', number_format($activeSubscription->amount_paid ?? $activeSubscription->price ?? 0, 2)]);
            fputcsv($file, ['Start Date', $activeSubscription->created_at->format('Y-m-d H:i:s')]);
            fputcsv($file, ['Expiry Date', $activeSubscription->expires_at->format('Y-m-d H:i:s')]);
            fputcsv($file, ['Duration (Days)', $activeSubscription->created_at->diffInDays($activeSubscription->expires_at)]);
            fputcsv($file, ['Is Active', $activeSubscription->expires_at > now() ? 'Yes' : 'No']);
        } else {
            fputcsv($file, ['No active subscription found']);
        }
        fputcsv($file, []); // Empty row
        
        // Advert Details Header
        fputcsv($file, ['ADVERT DETAILS']);
        fputcsv($file, [
            'Advert ID',
            'Title',
            'Category',
            'Created',
            'Status',
            'Duration (Days)',
            'Amount Paid',
            'Expiration Date',
            'Days Until Expiry'
        ]);

        foreach ($vendor->products as $product) {
            $expirationDate = null;
            $amountPaid = 0;
            $durationDays = 0;
            
            if ($activeSubscription) {
                $expirationDate = $activeSubscription->expires_at;
                $durationDays = $activeSubscription->created_at->diffInDays($expirationDate);
                $amountPaid = $activeSubscription->amount_paid ?? $activeSubscription->price ?? 0;
            } else {
                $expirationDate = $product->created_at->addDays(30);
                $durationDays = 30;
            }
            
            $daysUntilExpiry = $expirationDate ? now()->diffInDays($expirationDate, false) : null;
            $advertStatus = $product->status;
            if ($expirationDate && $expirationDate->isPast() && $product->status === 'active') {
                $advertStatus = 'Expired';
            }

            fputcsv($file, [
                $product->id,
                $product->title,
                $product->category->name ?? 'N/A',
                $product->created_at->format('Y-m-d'),
                $advertStatus,
                $durationDays,
                number_format($amountPaid, 2),
                $expirationDate ? $expirationDate->format('Y-m-d') : 'N/A',
                $daysUntilExpiry
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
    
    
}

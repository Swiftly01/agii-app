<?php
// app/Http/Controllers/StoreController.php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoreAroundMeController extends Controller
{
    public function store_users_vendors_me(Request $request, $category = null)
    {
        // Get user's current location from request, session, or IP
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');
        $radiusKm = $request->input('radius', 50);

        // If not in request, check session
        if (!$userLat || !$userLng) {
            $userLat = session('user_latitude');
            $userLng = session('user_longitude');
        }

        // If still no coordinates, try to get from logged-in user's profile
        if (!$userLat || !$userLng) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user && $user->local_government) {
                    $location = Location::where('lga', $user->local_government)->first();
                    if ($location) {
                        $userLat = $location->latitude;
                        $userLng = $location->longitude;

                        // Store in session for future use
                        session(['user_latitude' => $userLat, 'user_longitude' => $userLng]);
                    }
                }
            }
        }

        // If still no coordinates, try to get from IP address
        if (!$userLat || !$userLng) {
            $ipLocation = $this->getLocationFromIP($request->ip());
            if ($ipLocation) {
                $userLat = $ipLocation['lat'];
                $userLng = $ipLocation['lng'];

                // Store in session for future use
                session(['user_latitude' => $userLat, 'user_longitude' => $userLng]);
            }
        }

        // Final fallback to default location (Lagos)
        if (!$userLat || !$userLng) {
            $userLat = 6.5244;
            $userLng = 3.3792;
            session(['user_latitude' => $userLat, 'user_longitude' => $userLng]);
        }

        // Get nearby vendors using direct coordinates from users table
        $nearbyVendors = $this->getNearbyVendorsWithCoordinates($userLat, $userLng, $radiusKm);

        // Get products - if we have nearby vendors, show their products first
        $productsQuery = Product::where('status', 'active');

        // If we have nearby vendors, prioritize their products
        if ($nearbyVendors->count() > 0) {
            $vendorIds = $nearbyVendors->pluck('id')->toArray();
            $productsQuery->whereIn('user_id', $vendorIds);
        }

        // Filter by category if provided
        if ($category) {
            $categoryModel = Category::where('slug', $category)->first();
            if ($categoryModel) {
                $productsQuery->where('category_id', $categoryModel->id);
            }
        }

        // Handle sorting
        $sort = $request->input('sort', 'latest');
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
            case 'nearby':
                // If nearby sort is selected and we have location, sort by distance
                if ($nearbyVendors->count() > 0) {
                    $vendorIds = $nearbyVendors->pluck('id')->toArray();
                    $productsQuery->orderByRaw("FIELD(user_id, " . implode(',', $vendorIds) . ") DESC")
                        ->orderBy('created_at', 'desc');
                } else {
                    $productsQuery->orderBy('created_at', 'desc');
                }
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
        }

        $products = $productsQuery->with(['user', 'category'])
            ->paginate(12)
            ->appends($request->except('page'));

        // Get categories for filter
        $categories = Category::all();


        // Featured items - show from all vendors, not just nearby
        $featuredStores = User::where('user_type', 'vendor')
            // ->where('featured', true)
            ->limit(6)
            ->get();
            
            
        $nearbyStores = Store::with(['user', 'products'])
                        ->where('is_active', true)
                        ->whereIn('user_id', $nearbyVendors->pluck('id'))
                        ->whereHas('user', fn($q) => $q->whereHas('activeSubscription'))
                        ->whereHas('products', fn($q) => $q->where('status', 'active'))
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
            'nearbyStores',
            'featuredProducts',
            'nearbyVendors',
            'userLat',
            'userLng',
            'radiusKm'
        ));
    }


    public function show($vendorId, Request $request)
    {
        $vendor = User::withCount('products')->findOrFail($vendorId);

        // Build products query with filters
        $productsQuery = Product::where('user_id', $vendorId)
            ->where('status', 'active');

        // Apply sorting
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
            case 'latest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        // Apply category filter
        if ($request->has('category')) {
            $productsQuery->where('category', $request->category);
        }

        $products = $productsQuery->paginate(12);

        return view('show', compact('vendor', 'products'));
    }
    /**
     * Get nearby vendors using coordinates directly from users table
     */
    private function getNearbyVendorsWithCoordinates($userLat, $userLng, $radiusKm = 50)
    {
        // Get vendors who have coordinates in the users table
        $vendors = User::where('user_type', 'vendor')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Calculate distances and filter
        return $vendors->map(function ($vendor) use ($userLat, $userLng) {
            $distance = $this->calculateDistance(
                $userLat,
                $userLng,
                $vendor->latitude,
                $vendor->longitude
            );

            $vendor->distance = round($distance, 2);
            $vendor->distance_unit = 'km';
            $vendor->coordinates = [
                'lat' => $vendor->latitude,
                'lng' => $vendor->longitude
            ];

            return $vendor;
        })
            ->filter(function ($vendor) use ($radiusKm) {
                return $vendor->distance <= $radiusKm;
            })
            ->sortBy('distance');
    }

    /**
     * Alternative: Get nearby vendors using MySQL spatial query (more efficient for large datasets)
     */
    private function getNearbyVendorsSpatial($userLat, $userLng, $radiusKm = 50)
    {
        return User::where('user_type', 'vendor')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$userLat, $userLng, $userLat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get()
            ->each(function ($vendor) {
                $vendor->distance = round($vendor->distance, 2);
                $vendor->distance_unit = 'km';
            });
    }

    /**
     * Update all vendors with coordinates from locations table
     */
    public function updateVendorsCoordinates()
    {
        try {
            $updatedCount = User::where('user_type', 'vendor')
                ->whereNotNull('local_government')
                ->where(function ($query) {
                    $query->whereNull('latitude')
                        ->orWhereNull('longitude');
                })
                ->get()
                ->map(function ($user) {
                    $location = Location::where('lga', $user->local_government)->first();
                    if ($location) {
                        $user->update([
                            'latitude' => $location->latitude,
                            'longitude' => $location->longitude
                        ]);
                        return $user->id;
                    }
                    return null;
                })
                ->filter()
                ->count();

            return response()->json([
                'success' => true,
                'message' => "Updated coordinates for {$updatedCount} vendors",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating vendor coordinates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor coordinates'
            ], 500);
        }
    }

    /**
     * Get nearby vendors using spatial query
     */
    private function getNearbyVendors($userLat, $userLng, $radiusKm = 50)
    {
        // Get all active vendors
        $vendors = User::where('user_type', 'vendor')

            ->get();

        // Calculate distances and filter
        return $vendors->map(function ($vendor) use ($userLat, $userLng) {
            $vendorLocation = Location::where('lga', $vendor->local_government)->first();

            if ($vendorLocation && $vendorLocation->latitude && $vendorLocation->longitude) {
                $distance = $this->calculateDistance(
                    $userLat,
                    $userLng,
                    $vendorLocation->latitude,
                    $vendorLocation->longitude
                );
                $vendor->distance = round($distance, 2);
                $vendor->distance_unit = 'km';
                $vendor->coordinates = [
                    'lat' => $vendorLocation->latitude,
                    'lng' => $vendorLocation->longitude
                ];
            } else {
                // If no coordinates found, set a large distance
                $vendor->distance = 9999;
                $vendor->distance_unit = 'km';
                $vendor->coordinates = null;
            }

            return $vendor;
        })
            ->filter(function ($vendor) use ($radiusKm) {
                return $vendor->distance <= $radiusKm;
            })
            ->sortBy('distance');
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

    /**
     * Get approximate location from IP address
     */
    private function getLocationFromIP($ip)
    {
        try {
            // For local development or private IPs, return default location
            if ($ip === '127.0.0.1' || $ip === '::1' || substr($ip, 0, 7) === '192.168') {
                return [
                    'lat' => 6.5244, // Lagos
                    'lng' => 3.3792,
                    'city' => 'Lagos',
                    'country' => 'Nigeria'
                ];
            }

            // Use ipinfo.io service (free tier available)
            $url = "http://ipinfo.io/{$ip}/json?token=" . env('IPINFO_TOKEN', ''); // Get free token from ipinfo.io

            $client = new \GuzzleHttp\Client();
            $response = $client->get($url, ['timeout' => 5]);
            $data = json_decode($response->getBody(), true);

            if (isset($data['loc'])) {
                $coordinates = explode(',', $data['loc']);
                return [
                    'lat' => floatval($coordinates[0]),
                    'lng' => floatval($coordinates[1]),
                    'city' => $data['city'] ?? 'Unknown',
                    'country' => $data['country'] ?? 'Unknown'
                ];
            }
        } catch (\Exception $e) {
            Log::warning('IP location detection failed: ' . $e->getMessage());
        }


        // Fallback to major Nigerian cities based on common IP ranges
        return $this->getFallbackNigerianLocation($ip);
    }

    /**
     * Fallback location for Nigerian IP ranges
     */
    private function getFallbackNigerianLocation($ip)
    {
        // Simple fallback - in production, you might want to use a proper IP database
        $nigerianCities = [
            ['lat' => 6.5244, 'lng' => 3.3792, 'city' => 'Lagos'], // Lagos
            ['lat' => 9.0765, 'lng' => 7.3986, 'city' => 'Abuja'], // Abuja
            ['lat' => 7.3776, 'lng' => 3.9470, 'city' => 'Ibadan'], // Ibadan
            ['lat' => 4.8156, 'lng' => 7.0498, 'city' => 'Port Harcourt'], // Port Harcourt
            ['lat' => 10.3103, 'lng' => 9.8439, 'city' => 'Bauchi'], // Bauchi
            ['lat' => 11.8333, 'lng' => 13.1500, 'city' => 'Maiduguri'], // Maiduguri
            ['lat' => 5.1167, 'lng' => 7.3667, 'city' => 'Aba'], // Aba
            ['lat' => 6.3350, 'lng' => 5.6037, 'city' => 'Benin'], // Benin
        ];

        // Use IP to deterministically select a city (simple hash method)
        $hash = crc32($ip);
        $index = abs($hash) % count($nigerianCities);

        return $nigerianCities[$index];
    }

    /**
     * Store user location from AJAX request (works for guests too)
     */




    /**
     * Store user location from AJAX request (works for guests too)
     * Now supports both GET and POST methods
     */
    public function storeUserLocation(Request $request)
    {
        try {
            // Support both GET and POST parameters
            $latitude = $request->input('latitude', $request->query('latitude'));
            $longitude = $request->input('longitude', $request->query('longitude'));

            // Validate the coordinates
            if (!$latitude || !$longitude) {
                return response()->json([
                    'success' => false,
                    'message' => 'Latitude and longitude are required'
                ], 400);
            }

            // Validate numeric values
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coordinates provided'
                ], 400);
            }

            // Store in session (works for both guests and logged-in users)
            session([
                'user_latitude' => $latitude,
                'user_longitude' => $longitude,
                'location_source' => $request->has('source') ? $request->source : 'browser'
            ]);

            // Optional: Store in database if user is logged in
            if (Auth::check()) {
                $user = Auth::user();
                $user->update([
                    'last_known_latitude' => $latitude,
                    'last_known_longitude' => $longitude,
                    'location_updated_at' => now()
                ]);
            }

            // Return success without redirect to avoid GET/POST issues
            return response()->json([
                'success' => true,
                'message' => 'Location stored successfully',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'stored_in_session' => true
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing user location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to store location'
            ], 500);
        }
    }

    /**
     * New method to handle location updates via GET (for URL parameters)
     */
    public function updateLocation(Request $request)
    {
        try {
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');
            $radius = $request->query('radius', 50);

            if (!$latitude || !$longitude) {
                // If no coordinates provided, redirect back to store page
                return redirect()->route('store.index');
            }

            // Validate coordinates
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                return redirect()->route('store.index')->with('error', 'Invalid coordinates provided');
            }

            // Store in session
            session([
                'user_latitude' => $latitude,
                'user_longitude' => $longitude,
                'location_source' => 'url_parameters'
            ]);

            // Redirect to store page with the same parameters
            return redirect()->route('store.index', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating location via GET: ' . $e->getMessage());
            return redirect()->route('store.index')->with('error', 'Failed to update location');
        }
    }

    /**
     * Get vendors by local government area
     */
    public function getVendorsByLGA($lga)
    {
        $vendors = User::where('user_type', 'vendor')

            ->where('local_government', $lga)
            ->get()
            ->map(function ($vendor) {
                $vendor->products_count = $vendor->products()->where('status', 'active')->count();
                return $vendor;
            });

        return response()->json([
            'success' => true,
            'vendors' => $vendors,
            'count' => $vendors->count()
        ]);
    }

    /**
     * Search vendors by location
     */
    public function searchVendorsByLocation(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        // Search in locations table for matching LGAs
        $locations = Location::where('lga', 'like', "%{$query}%")
            ->orWhere('state', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        $results = $locations->map(function ($location) {
            $vendorCount = User::where('user_type', 'vendor')
                ->where('local_government', $location->lga)
                ->count();

            return [
                'lga' => $location->lga,
                'state' => $location->state,
                'city' => $location->city,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'vendor_count' => $vendorCount
            ];
        });

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
}

@extends('layout.layout')
@section('title', 'Agii - Buy and Sell Anything in Nigeria')

@section('content')
    <!-- Location Notification -->
    <div id="locationNotification" class="alert alert-info location-notification" style="display: none;">
        <div class="d-flex align-items-center">
            <i class="bi bi-geo-alt-fill me-2"></i>
            <div class="flex-grow-1">
                <strong>Finding stores near you...</strong>
                <div class="small">We're detecting your location to show nearby vendors</div>
            </div>
            <button type="button" class="btn-close" onclick="locationService.hideLocationNotification()"></button>
        </div>
    </div>

    <!-- Banner Section -->
    <section class="banner-section py-5" style="background: linear-gradient(135deg, #90c74b 0%, #74a534 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h1 class="display-5 fw-bold mb-4">Buy and Sell Anything in Nigeria</h1>
                    <p class="lead mb-4">Discover the best deals on thousands of products. From electronics to vehicles
                        and properties, find everything you need in one place.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg text-success fw-bold px-4">Start Selling</a>
                        <a href="#nearby-stores" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-geo-alt me-2"></i>Nearby Stores
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-content text-center">
                        <img src="https://agii.ng/frontend/assets/images/demos/demo-14/slider/slide-1.png" alt="Banner"
                            class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Controls -->
    <section class="py-3 bg-light border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="location-info alert alert-light d-flex align-items-center mb-0">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        <div class="flex-grow-1">
                            <strong>Your Location:</strong>
                            <span class="user-location-display ms-1">
                                @if (isset($userLat) && isset($userLng))
                                    Lat: {{ number_format($userLat, 4) }}, Lng: {{ number_format($userLng, 4) }}
                                @else
                                    Detecting your location...
                                @endif
                            </span>
                        </div>
                        <small class="text-muted">Search Radius: {{ $radiusKm ?? 50 }}km</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="location-controls d-flex gap-2 justify-content-end">
                        <button id="refreshLocationBtn" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                        <button id="manualLocationBtn" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pin-map me-1"></i>Set Location
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nearby Stores Section -->
    @if (isset($nearbyVendors) && $nearbyVendors->count() > 0)
        <section id="nearby-stores" class="py-5 bg-white">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="section-title mb-2">Stores Near You</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-geo-alt text-primary me-1"></i>
                            Found {{ $nearbyVendors->count() }} vendors within {{ $radiusKm ?? 50 }}km of your location
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-sliders me-1"></i>Radius: {{ $radiusKm ?? 50 }}km
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['radius' => 10]) }}">10
                                        km</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['radius' => 25]) }}">25
                                        km</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['radius' => 50]) }}">50
                                        km</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['radius' => 100]) }}">100
                                        km</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="position-relative">
                    <div class="nearby-stores-slider swiper">
                        <div class="swiper-wrapper">
                            @foreach ($nearbyVendors->take(12) as $vendor)
                                <div class="swiper-slide">
                                    <div class="store-card card h-100 shadow-sm border-0">
                                        <div class="card-body">
                                            <div class="store-header mb-3">
                                                <div class="d-flex align-items-center">
                                                    @if ($vendor->profile_image)
                                                        <img src="{{ url($vendor->profile_image) }}"
                                                            class="store-logo rounded-circle me-3"
                                                            alt="{{ $vendor->business_name }}"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="store-logo-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="bi bi-shop"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <h6 class="store-name fw-bold text-dark mb-0">
                                                            {{ $vendor->business_name ?? $vendor->first_name . ' ' . $vendor->last_name }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $vendor->local_government }}, {{ $vendor->state }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="store-distance-badge mt-2">
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $vendor->distance }}km away
                                                    </span>
                                                </div>
                                            </div>

                                            @if ($vendor->business_category)
                                                <div class="store-category mb-3">
                                                    <span class="badge bg-light text-dark border">
                                                        {{ ucfirst($vendor->business_category) }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="store-stats">
                                                <div class="row text-center g-2">
                                                    <div class="col-4">
                                                        <div class="border rounded p-2">
                                                            <small class="text-muted d-block">Products</small>
                                                            <strong
                                                                class="text-dark">{{ $vendor->products_count ?? 0 }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="border rounded p-2">
                                                            <small class="text-muted d-block">Rating</small>
                                                            <strong class="text-warning small">
                                                                <i class="bi bi-star-fill"></i>
                                                                {{ $vendor->rating ?? 'N/A' }}
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="border rounded p-2">
                                                            <small class="text-muted d-block">Distance</small>
                                                            <strong
                                                                class="text-success">{{ $vendor->distance }}km</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer bg-transparent border-0 pt-0">
                                            <a href="{{ route('store.show', $vendor->id) }}"
                                                class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-shop me-1"></i>Visit Store
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next swiper-button-next-custom"></div>
                    <div class="swiper-button-prev swiper-button-prev-custom"></div>
                </div>
            </div>
        </section>
    @else
        <!-- Show this when no nearby vendors or location not detected -->
        <section id="nearby-stores" class="py-5 bg-white">
            <div class="container">
                <div class="text-center py-4">
                    <i class="bi bi-geo-alt display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">Enable Location for Nearby Stores</h3>
                    <p class="text-muted mb-4">Allow location access to see stores near you</p>
                    <button onclick="locationService.refreshLocation()" class="btn btn-primary">
                        <i class="bi bi-geo-alt me-2"></i>Enable Location
                    </button>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    View All <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="position-relative">
                <div class="featured-products-slider swiper">
                    <div class="swiper-wrapper">
                        @forelse($featuredProducts as $product)
                            <div class="swiper-slide">
                                <div class="product-card card h-100 border-0 shadow-sm">
                                    <div class="product-badges">
                                        @if ($product->condition == 'New')
                                            <span class="badge bg-success">New</span>
                                        @elseif($product->negotiable)
                                            <span class="badge bg-warning">Negotiable</span>
                                        @endif
                                        @if ($product->featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @endif
                                    </div>

                                    <div class="product-image">
                                        <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                            alt="{{ $product->title }}" class="card-img-top">
                                        <div class="image-overlay"></div>
                                    </div>

                                    <div class="card-body">
                                        <h6 class="product-title">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-dark text-decoration-none">
                                                {{ Str::limit($product->title, 50) }}
                                            </a>
                                        </h6>

                                        <div class="product-specs mb-2">
                                            <small class="text-muted">
                                                @if (isset($product->specifications['storage']))
                                                    {{ $product->specifications['storage'] }} •
                                                @endif
                                                {{ $product->condition }} Condition
                                            </small>
                                        </div>

                                        <div class="product-rating mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="rating-stars">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <span class="rating-value">{{ $product->rating }}</span>
                                                </div>
                                                <span
                                                    class="review-count text-muted ms-2">({{ $product->review_count }})</span>
                                            </div>
                                        </div>

                                        <div class="product-price mb-3">
                                            <span
                                                class="price fw-bold text-success">₦{{ number_format($product->price) }}</span>
                                            @if ($product->old_price)
                                                <span class="old-price text-muted text-decoration-line-through ms-2">
                                                    ₦{{ number_format($product->old_price) }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="product-location">
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $product->location }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-primary w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-box display-1 text-muted"></i>
                                <h4 class="mt-3 text-muted">No featured products</h4>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="swiper-button-next featured-next"></div>
                <div class="swiper-button-prev featured-prev"></div>
            </div>
        </div>
    </section>

    <!-- Featured Stores -->
    <section class="py-5 bg-white d-none">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Featured Stores</h2>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-primary">
                    View All <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredStores as $store)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="store-card card h-100 shadow-sm border-0">
                            @if ($store->banner)
                                <img src="{{ Storage::url($store->banner) }}" class="store-banner card-img-top"
                                    alt="{{ $store->store_name }}" style="height: 120px; object-fit: cover;">
                            @else
                                <div class="store-banner-placeholder bg-light d-flex align-items-center justify-content-center"
                                    style="height: 120px;">
                                    <i class="bi bi-shop display-4 text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body text-center">
                                <div class="store-logo mb-3">
                                    @if ($store->logo)
                                        <img src="{{ Storage::url($store->logo) }}"
                                            class="store-logo-img rounded-circle border border-3 border-white shadow-sm"
                                            alt="{{ $store->store_name }}"
                                            style="width: 80px; height: 80px; object-fit: cover; margin-top: -50px;">
                                    @else
                                        <div class="store-logo-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 80px; height: 80px; margin-top: -50px;">
                                            <i class="bi bi-shop fs-4"></i>
                                        </div>
                                    @endif
                                </div>

                                <h5 class="card-title store-name fw-bold text-dark mb-2">
                                    {{ Str::limit($store->store_name, 20) }}
                                </h5>

                                @if ($store->description)
                                    <p class="card-text store-description text-muted small mb-3">
                                        {{ Str::limit($store->description, 60) }}
                                    </p>
                                @endif

                                <div class="store-stats mb-3">
                                    <div class="d-flex justify-content-center gap-3">
                                        <small class="text-muted">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $store->products_count ?? $store->products->count() }} products
                                        </small>
                                        @if ($store->rating > 0)
                                            <small class="text-warning">
                                                <i class="bi bi-star-fill me-1"></i>
                                                {{ number_format($store->rating, 1) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="" class="btn btn-outline-primary btn-sm w-100">
                                    Visit Store
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-shop display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No featured stores</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- All Products Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="products-header d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h3 class="mb-3 mb-md-0">
                            @if ($category)
                                {{ $categories->where('slug', $category)->first()->name ?? 'Products' }}
                            @else
                                All Products
                            @endif
                            <small class="text-muted fs-6">({{ $products->total() }} found)</small>
                        </h3>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price:
                                    Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated
                                </option>
                                <option value="nearby" {{ request('sort') == 'nearby' ? 'selected' : '' }}
                                    {{ !isset($userLat) ? 'disabled' : '' }}>Nearest First</option>
                            </select>

                            @if (isset($nearbyVendors) && $nearbyVendors->count() > 0)
                                <div class="text-success small">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ $nearbyVendors->count() }} nearby stores
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                        @forelse($products as $product)
                            <div class="col">
                                <div class="product-card card h-100 border-0 shadow-sm">
                                    <div class="product-badges">
                                        @if ($product->condition == 'New')
                                            <span class="badge bg-success">New</span>
                                        @elseif($product->negotiable)
                                            <span class="badge bg-warning">Negotiable</span>
                                        @endif
                                    </div>

                                    <div class="product-image">
                                        <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                            alt="{{ $product->title }}" class="card-img-top">
                                        <div class="image-overlay"></div>
                                    </div>

                                    <div class="card-body">
                                        <h6 class="product-title">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-dark text-decoration-none">
                                                {{ Str::limit($product->title, 50) }}
                                            </a>
                                        </h6>

                                        <div class="product-specs mb-2">
                                            <small class="text-muted">
                                                @if (isset($product->specifications['storage']))
                                                    {{ $product->specifications['storage'] }} •
                                                @endif
                                                {{ $product->condition }} Condition
                                            </small>
                                        </div>

                                        <div class="product-rating mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="rating-stars">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <span class="rating-value">{{ $product->rating }}</span>
                                                </div>
                                                <span
                                                    class="review-count text-muted ms-2">({{ $product->review_count }})</span>
                                            </div>
                                        </div>

                                        <div class="product-price mb-3">
                                            <span
                                                class="price fw-bold text-success">₦{{ number_format($product->price) }}</span>
                                            @if ($product->old_price)
                                                <span class="old-price text-muted text-decoration-line-through ms-2">
                                                    ₦{{ number_format($product->old_price) }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="product-location">
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $product->location }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-primary btn-sm w-100">
                                            View Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-search display-1 text-muted"></i>
                                <h4 class="mt-3 text-muted">No products found</h4>
                                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Browse All Products</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="row mt-5">
                            <div class="col-12">
                                <nav aria-label="Product pagination">
                                    <ul class="pagination justify-content-center">
                                        {{ $products->links() }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @if (!View::hasSection('csrf-token'))
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #90c74b;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --border-color: #dee2e6;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .section-title {
            position: relative;
            padding-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        /* Product Cards */
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .product-badges .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .image-overlay {
            opacity: 1;
        }

        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.1rem;
        }

        .old-price {
            font-size: 0.9rem;
        }

        .rating-stars {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .rating-value {
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 4px;
        }

        /* Store Cards */
        .store-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .store-header {
            margin-bottom: 1rem;
        }

        .store-logo {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .store-distance-badge {
            margin-top: 0.5rem;
        }

        .store-name {
            font-size: 1rem;
            line-height: 1.3;
        }

        .store-stats .row>div {
            margin-bottom: 0.5rem;
        }

        /* Swiper Styles */
        .swiper {
            width: 100%;
            height: 100%;
            padding: 10px 5px 40px;
        }

        .swiper-slide {
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-color);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: var(--shadow);
            top: 50%;
            transform: translateY(-50%);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 16px;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--primary-color);
            color: white;
        }

        .swiper-button-next {
            right: -20px;
        }

        .swiper-button-prev {
            left: -20px;
        }

        /* Location Notification */
        .location-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: var(--shadow-hover);
            border: none;
            border-left: 4px solid #17a2b8;
            animation: slideInRight 0.3s ease;
        }

        .location-info {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Banner Section */
        .banner-section {
            position: relative;
            overflow: hidden;
        }

        .banner-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.1"><polygon fill="white" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .banner-content {
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .banner-section .display-5 {
                font-size: 2rem;
            }

            .swiper-button-next,
            .swiper-button-prev {
                display: none;
            }

            .products-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .products-header .d-flex {
                margin-top: 1rem;
                width: 100%;
            }

            .location-controls {
                justify-content: flex-start !important;
                margin-top: 1rem;
            }

            .location-info {
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .store-card .card-body {
                padding: 1rem;
            }

            .store-logo-img,
            .store-logo-placeholder {
                width: 60px !important;
                height: 60px !important;
                margin-top: -40px !important;
            }

            .product-image {
                height: 160px;
            }

            .banner-section .btn-lg {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }

        /* Custom scrollbar for swiper */
        .swiper::-webkit-scrollbar {
            height: 6px;
        }

        .swiper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .swiper::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        /* Loading animation */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Enhanced Location Service with proper URL handling
        class LocationService {
            constructor() {
                this.isLocating = false;
                this.maxRetries = 3;
                this.retryCount = 0;
                this.timeout = 10000;
            }

            // Get CSRF token safely
            getCsrfToken() {
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && metaTag.getAttribute('content')) {
                    return metaTag.getAttribute('content');
                }
                return null;
            }

            async getUserLocation() {
                if (this.isLocating) {
                    return;
                }

                // Always use URL parameters if available (most reliable)
                if (this.hasStoredLocationInURL()) {
                    console.log('Using location from URL parameters');
                    return this.useStoredLocationFromURL();
                }

                if (this.isLocationDenied()) {
                    console.log('Location access previously denied');
                    this.showLocationDeniedMessage();
                    return;
                }

                this.isLocating = true;
                this.showLocationNotification();

                try {
                    const position = await this.getCurrentPosition();
                    await this.handleLocationSuccess(position);
                } catch (error) {
                    await this.handleLocationError(error);
                } finally {
                    this.isLocating = false;
                }
            }

            getCurrentPosition() {
                return new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Geolocation is not supported'));
                        return;
                    }

                    const options = {
                        enableHighAccuracy: true,
                        timeout: this.timeout,
                        maximumAge: 5 * 60 * 1000
                    };

                    navigator.geolocation.getCurrentPosition(resolve, reject, options);
                });
            }

            async handleLocationSuccess(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                console.log(`Location found: ${lat}, ${lng} (Accuracy: ${accuracy}m)`);

                this.hideLocationNotification();

                // Update URL immediately (this is the key fix)
                this.updateURLWithLocation(lat, lng);

                // Update UI
                this.updateUIWithLocation(lat, lng);
                this.showLocationSuccessMessage(lat, lng, accuracy);

                // Store in session via AJAX (optional, non-blocking)
                this.storeLocationInSession(lat, lng);
            }

            async handleLocationError(error) {
                console.error('Location error:', error);
                this.hideLocationNotification();

                let message = 'Unable to detect your location. ';
                let type = 'warning';

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message += 'Location access was denied. Using approximate location.';
                        type = 'warning';
                        this.markLocationAsDenied();
                        this.useDefaultLocation();
                        return;
                    case error.POSITION_UNAVAILABLE:
                        message += 'Location information is unavailable. Using approximate location.';
                        this.useDefaultLocation();
                        return;
                    case error.TIMEOUT:
                        message += 'Location request timed out. Using approximate location.';
                        this.useDefaultLocation();
                        return;
                    default:
                        message += 'An unknown error occurred. Using approximate location.';
                        this.useDefaultLocation();
                        return;
                }
            }

            // Use default location (Lagos)
            useDefaultLocation() {
                const defaultLocation = {
                    lat: 6.5244,
                    lng: 3.3792
                };
                this.updateURLWithLocation(defaultLocation.lat, defaultLocation.lng);
                this.updateUIWithLocation(defaultLocation.lat, defaultLocation.lng);
                this.storeLocationInSession(defaultLocation.lat, defaultLocation.lng);
                this.showToast('Using default location (Lagos)', 'info');
            }

            // Update URL with location parameters (no page reload)
            updateURLWithLocation(lat, lng, radius = 50) {
                const newUrl = new URL(window.location.href);

                // Remove existing location parameters
                newUrl.searchParams.delete('latitude');
                newUrl.searchParams.delete('longitude');
                newUrl.searchParams.delete('radius');

                // Add new parameters
                newUrl.searchParams.set('latitude', lat);
                newUrl.searchParams.set('longitude', lng);
                newUrl.searchParams.set('radius', radius);

                // Update URL without reloading page
                window.history.replaceState({}, '', newUrl);

                console.log('URL updated with location parameters');
            }

            // Store location in session via AJAX (non-critical)
            async storeLocationInSession(lat, lng) {
                try {
                    const csrfToken = this.getCsrfToken();
                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    };

                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }

                    const response = await fetch('/store-location', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng,
                            source: 'browser_geolocation'
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            console.log('Location stored in session');
                        }
                    }
                } catch (error) {
                    console.log('Location session storage failed, but URL parameters are set');
                }
            }

            // Check if URL has location parameters
            hasStoredLocationInURL() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.has('latitude') && urlParams.has('longitude');
            }

            // Use location from URL parameters
            useStoredLocationFromURL() {
                const urlParams = new URLSearchParams(window.location.search);
                const lat = urlParams.get('latitude');
                const lng = urlParams.get('longitude');

                this.updateUIWithLocation(lat, lng);
                return {
                    latitude: lat,
                    longitude: lng
                };
            }

            // Store in session storage as backup
            storeInSessionStorage(lat, lng) {
                try {
                    sessionStorage.setItem('userLatitude', lat);
                    sessionStorage.setItem('userLongitude', lng);
                    sessionStorage.setItem('locationDetected', 'true');
                } catch (e) {
                    // Session storage not available
                }
            }

            markLocationAsDenied() {
                try {
                    sessionStorage.setItem('locationDenied', 'true');
                } catch (e) {
                    // Session storage not available
                }
            }

            isLocationDenied() {
                try {
                    return sessionStorage.getItem('locationDenied') === 'true';
                } catch (e) {
                    return false;
                }
            }

            updateUIWithLocation(lat, lng) {
                const locationElements = document.querySelectorAll('.user-location-display');
                locationElements.forEach(element => {
                    element.textContent =
                        `Lat: ${parseFloat(lat).toFixed(4)}, Lng: ${parseFloat(lng).toFixed(4)}`;
                    element.classList.remove('text-muted');
                    element.classList.add('text-success', 'fw-bold');
                });

                this.enableLocationFeatures();
            }

            enableLocationFeatures() {
                const nearbyStoresSection = document.getElementById('nearby-stores');
                if (nearbyStoresSection) {
                    nearbyStoresSection.style.display = 'block';
                }

                const sortSelect = document.getElementById('sortSelect');
                if (sortSelect) {
                    const nearbyOption = sortSelect.querySelector('option[value="nearby"]');
                    if (nearbyOption) {
                        nearbyOption.disabled = false;
                    }
                }
            }

            showManualLocationInput() {
                const city = prompt('Please enter your city or local government area:');
                if (city) {
                    this.geocodeCityName(city);
                }
            }

            async geocodeCityName(cityName) {
                const cityCoordinates = {
                    'lagos': {
                        lat: 6.5244,
                        lng: 3.3792
                    },
                    'abuja': {
                        lat: 9.0765,
                        lng: 7.3986
                    },
                    'kano': {
                        lat: 12.0022,
                        lng: 8.5927
                    },
                    'ibadan': {
                        lat: 7.3776,
                        lng: 3.9470
                    },
                    'port harcourt': {
                        lat: 4.8156,
                        lng: 7.0498
                    },
                    'benin': {
                        lat: 6.3350,
                        lng: 5.6037
                    },
                    'aba': {
                        lat: 5.1167,
                        lng: 7.3667
                    },
                    'damban': {
                        lat: 11.6833,
                        lng: 10.7333
                    },
                    'bauchi': {
                        lat: 10.3103,
                        lng: 9.8439
                    }
                };

                const normalizedCity = cityName.toLowerCase().trim();
                const coordinates = cityCoordinates[normalizedCity];

                if (coordinates) {
                    this.updateURLWithLocation(coordinates.lat, coordinates.lng);
                    this.updateUIWithLocation(coordinates.lat, coordinates.lng);
                    this.storeLocationInSession(coordinates.lat, coordinates.lng);
                    this.showToast(`Location set to ${cityName}`, 'success');
                } else {
                    this.showToast(`Could not find coordinates for ${cityName}`, 'warning');
                }
            }

            showLocationNotification() {
                const notification = document.getElementById('locationNotification');
                if (notification) {
                    notification.style.display = 'block';
                }
            }

            hideLocationNotification() {
                const notification = document.getElementById('locationNotification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }

            showLocationSuccessMessage(lat, lng, accuracy) {
                this.showToast('✓ Location detected! Showing nearby stores.', 'success');
            }

            showLocationDeniedMessage() {
                this.showToast('Location access denied. Using approximate location.', 'warning');
            }

            showToast(message, type = 'info') {
                const existingToasts = document.querySelectorAll('.location-toast');
                existingToasts.forEach(toast => toast.remove());

                const toast = document.createElement('div');
                toast.className = `alert alert-${type} alert-dismissible fade show location-toast position-fixed`;
                toast.style.cssText = `
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
                toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 5000);
            }

            refreshLocation() {
                try {
                    sessionStorage.removeItem('locationDenied');
                    sessionStorage.removeItem('locationDetected');
                    sessionStorage.removeItem('userLatitude');
                    sessionStorage.removeItem('userLongitude');
                } catch (e) {
                    // Ignore errors
                }

                this.retryCount = 0;

                // Clear URL parameters
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.delete('latitude');
                newUrl.searchParams.delete('longitude');
                newUrl.searchParams.delete('radius');
                window.history.replaceState({}, '', newUrl);

                // Reset UI
                const locationElements = document.querySelectorAll('.user-location-display');
                locationElements.forEach(element => {
                    element.textContent = 'Detecting your location...';
                    element.classList.remove('text-success', 'fw-bold');
                    element.classList.add('text-muted');
                });

                this.getUserLocation();
            }
        }

        // Initialize location service
        const locationService = new LocationService();
        window.locationService = locationService;

        // Auto-initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners
            document.getElementById('refreshLocationBtn')?.addEventListener('click', () => {
                locationService.refreshLocation();
            });

            document.getElementById('manualLocationBtn')?.addEventListener('click', () => {
                locationService.showManualLocationInput();
            });

            // Start location detection
            setTimeout(() => {
                locationService.getUserLocation();
            }, 1000);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Nearby Stores Slider
            const nearbyStoresSlider = new Swiper('.nearby-stores-slider', {
                slidesPerView: 1,
                spaceBetween: 15,
                navigation: {
                    nextEl: '.swiper-button-next-custom',
                    prevEl: '.swiper-button-prev-custom',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    },
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 25
                    }
                },
                loop: false,
                grabCursor: true,
                resistance: true,
                resistanceRatio: 0.85
            });

            // Initialize Featured Products Slider
            const featuredProductsSlider = new Swiper('.featured-products-slider', {
                slidesPerView: 1,
                spaceBetween: 15,
                navigation: {
                    nextEl: '.featured-next',
                    prevEl: '.featured-prev',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 15
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20
                    },
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 25
                    }
                },
                loop: false,
                grabCursor: true,
                resistance: true,
                resistanceRatio: 0.85
            });

            // Sort select functionality
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', this.value);
                    window.location.href = url.toString();
                });
            }

            // Handle location-based sorting
            const nearbyOption = document.querySelector('option[value="nearby"]');
            if (nearbyOption && nearbyOption.disabled) {
                nearbyOption.title = "Enable location access to sort by distance";
            }

            console.log('Sliders initialized successfully');
        });
    </script>
@endpush

@extends('layout.layout')
@section('title', 'Agii - Buy and Sell Anything in Nigeria')

@section('content')
    <!-- Jumia Style Hero Banner -->
    <section class="jumia-hero py-4">
        <div class="container-fluid">
            <div class="row g-3">
                <!-- Left Sidebar - Categories -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="categories-sidebar bg-white rounded shadow-sm">
                        {{-- <div class="categories-header bg-success text-white p-3 rounded-top"> --}}
                        <div style="background: #ff6a00" class="categories-header  text-white p-3 rounded-top">


                            <h5 class="mb-0"><i class="bi bi-list me-2"></i> ALL CATEGORIES</h5>
                        </div>
                        <div class="categories-body">
                            <ul class="list-unstyled mb-0">
                                <!-- Dynamic Categories from Database -->
                                @foreach ($categories->where('parent_id', null)->take(10) as $mainCategory)
                                    @php
                                        $hasProducts = \App\Models\Product::where('category_id', $mainCategory->id)
                                            ->where('status', 'active')
                                            ->whereHas('user', fn($q) => $q->whereHas('activeSubscription'))
                                            ->exists();

                                        $subCategories = $categories->where('parent_id', $mainCategory->id);
                                    @endphp

                                    @if ($hasProducts)
                                        <li class="border-bottom category-item position-relative"
                                            data-category="{{ $mainCategory->slug }}">

                                            <!-- MAIN CATEGORY LINK -->
                                            <a href="{{ route('home.category', ['category' => $mainCategory->slug]) }}"
                                                class="d-flex align-items-center justify-content-between p-3 text-dark text-decoration-none">
                                                <span>
                                                    @if ($mainCategory->icon)
                                                        <i class="{{ $mainCategory->icon }} me-2"></i>
                                                    @else
                                                        <i class="bi bi-grid me-2"></i>
                                                    @endif
                                                    {{ $mainCategory->name }}
                                                </span>

                                                @if ($subCategories->count() > 0)
                                                    <i class="bi bi-chevron-right"></i>
                                                @endif
                                            </a>

                                            <!-- SUBCATEGORIES DROPDOWN -->
                                            @if ($subCategories->count() > 0)
                                                <div class="subcategories-dropdown bg-white shadow-sm rounded p-3 position-absolute start-100 top-0 d-none"
                                                    style="min-width: 320px; z-index: 1000;">
                                                    <div class="row">
                                                        @foreach ($subCategories as $subCategory)
                                                            @php
                                                                $hasSubProducts = \App\Models\Product::where(
                                                                    'category_id',
                                                                    $subCategory->id,
                                                                )
                                                                    ->where('status', 'active')
                                                                    ->whereHas(
                                                                        'user',
                                                                        fn($q) => $q->whereHas('activeSubscription'),
                                                                    )
                                                                    ->exists();
                                                            @endphp

                                                            @if ($hasSubProducts)
                                                                <div class="col-6 mb-2">
                                                                    <!-- SUBCATEGORY LINK -->
                                                                    <a href="{{ route('home.category', ['category' => $subCategory->slug]) }}"
                                                                        class="text-dark text-decoration-none d-block p-2 rounded hover-bg">
                                                                        {{ $subCategory->name }}
                                                                        <small class="text-muted d-block">
                                                                            {{ $subCategory->products_count ?? 0 }} items
                                                                        </small>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach

                                <!-- VIEW ALL PRODUCTS -->
                                <li class="text-center p-3">
                                    <a href="{{ route('products.index') }}" class="text-success fw-bold">
                                        <i class="bi bi-grid-3x3-gap me-1"></i> View All Products
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

                <!-- Main Hero Section -->
                <div class="col-lg-9">
                    <div class="hero-main bg-white rounded shadow-sm overflow-hidden">
                        <!-- Welcome Banner - Only show if user is not logged in -->
                        @guest
                            <div class="welcome-banner bg-success p-4 text-white">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <h1 class="display-6 fw-bold mb-3">Buy and Sell Anything in Nigeria</h1>
                                        <p class="lead mb-3">Discover unbeatable deals on thousands of products - from
                                            household items and everyday essentials to electronics, fashion, vehicles, and
                                            prime properties. Whatever you need, you'll find it all in one convenient place.
                                        </p>
                                        <div class="d-flex gap-3">
                                            <a href="/register" class="btn btn-light btn-lg text-success fw-bold">Start
                                                Selling</a>
                                            <a href="/login" class="btn btn-outline-light btn-lg fw-bold">Sign In</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-center d-none d-lg-block">
                                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                                            alt="Shopping" class="img-fluid rounded-3">
                                    </div>
                                </div>
                            </div>
                        @endguest

                        @auth
                            <!-- Banner Slider -->
                            <div class="hero-banner">
                                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0"
                                            class="active"></button>
                                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                                    </div>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80"
                                                class="d-block w-100" alt="Tech Deals"
                                                style="height: 400px; object-fit: cover;">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80"
                                                class="d-block w-100" alt="Fashion Sale"
                                                style="height: 400px; object-fit: cover;">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80"
                                                class="d-block w-100" alt="Home Appliances"
                                                style="height: 400px; object-fit: cover;">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        @endauth

                        <!-- Quick Categories Grid -->
                        <div class="quick-categories p-3 border-top">
                            <div class="row g-2">
                                @foreach ($categories->where('parent_id', null)->where('is_featured', true)->take(6) as $featuredCategory)
                                    @php
                                        $hasProducts = \App\Models\Product::where('category_id', $featuredCategory->id)
                                            ->where('status', 'active')
                                            ->whereHas('user', fn($q) => $q->whereHas('activeSubscription'))
                                            ->exists();
                                    @endphp

                                    @if ($hasProducts)
                                        <div class="col-4 col-md-2">
                                            <a href="{{ route('home.category', ['category' => $featuredCategory->slug]) }}"
                                                class="quick-category-item text-center d-block p-2">
                                                <div class="icon-wrapper mb-2">
                                                    @if ($featuredCategory->icon)
                                                        <i class="{{ $featuredCategory->icon }} fs-4 text-success"></i>
                                                    @else
                                                        <i class="bi bi-grid fs-4 text-success"></i>
                                                    @endif
                                                </div>
                                                <span class="small">{{ Str::limit($featuredCategory->name, 12) }}</span>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- Flash Sales Section -->
    @if ($featuredProducts->count() > 0)
        <section class="flash-sales py-4">
            <div class="container-fluid">
                <div class="flash-header bg-white rounded shadow-sm p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0"><i class="bi bi-lightning-charge-fill text-danger me-2"></i> FLASH
                                SALES
                            </h4>
                            <p class="text-muted mb-0 small">Limited time offers. Ends soon!</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="countdown-timer me-3">
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>
                                    <span id="countdown-hours">12</span>:<span id="countdown-minutes">45</span>:<span
                                        id="countdown-seconds">30</span>
                                </span>
                            </div>
                            <a href="{{ route('home', ['sort' => 'featured']) }}"
                                class="btn btn-outline-success btn-sm">View
                                All</a>
                        </div>
                    </div>
                </div>

                <div class="flash-products">
                    <div class="row g-3">
                        @foreach ($featuredProducts->take(6) as $product)
                            <div class="col-6 col-md-4 col-lg-2">
                                <div
                                    class="flash-product-card bg-white rounded shadow-sm border border-2 border-danger position-relative">
                                    @if ($product->discount > 0)
                                        <div
                                            class="discount-badge bg-danger text-white position-absolute top-0 start-0 m-2 px-2 py-1 rounded">
                                            -{{ $product->discount }}%
                                        </div>
                                    @endif
                                    <div class="product-image p-3">
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <img src="{{ $product->images[0] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=200&q=80' }}"
                                                alt="{{ $product->title }}" class="img-fluid"
                                                style="height: 150px; width: 100%; object-fit: contain;">
                                        </a>
                                    </div>
                                    <div class="product-info p-1 border-top">
                                        <h6 class="product-title mb-2">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-dark text-decoration-none">
                                                {{ Str::limit($product->title, 35) }}
                                            </a>
                                        </h6>
                                        <div class="price-section ">
                                            @if ($product->original_price > $product->price)
                                                <div class="original-price text-muted text-decoration-line-through small">
                                                    ₦{{ number_format($product->original_price) }}
                                                </div>
                                            @endif
                                            <div class="current-price fw-bold text-danger">
                                                ₦{{ number_format($product->price) }}
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 5px;">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ min(100, ($product->sold_count / max($product->stock_count, 1)) * 100) }}%">
                                            </div>
                                        </div>
                                        <div class="sold-text text-muted small">
                                            {{ $product->sold_count }} sold of {{ max($product->stock_count, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Hotels Section -->
    @if (isset($featuredHotels) && $featuredHotels->count() > 0)
        <section class="hotels-section py-4">
            <div class="container-fluid">
                <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0"><i class="bi bi-building text-success me-2"></i> FEATURED HOTELS</h4>
                            <p class="text-muted mb-0 small">Best accommodations for your stay</p>
                        </div>
                        <a href="{{ route('home.category', ['category' => 'hotel']) }}"
                            class="btn btn-outline-success btn-sm">View All Hotels</a>
                    </div>
                </div>

                <div class="hotels-grid">
                    <div class="row g-3">
                        @foreach ($featuredHotels->take(4) as $hotel)
                            <div class="col-6 col-md-3">
                                <div class="hotel-card bg-white rounded shadow-sm border">
                                    <div class="hotel-image position-relative">
                                        <a href="{{ route('product.show', $hotel->slug) }}">
                                            <img src="{{ $hotel->images[0] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=180&q=80' }}"
                                                alt="{{ $hotel->name }}" class="img-fluid w-100 rounded-top"
                                                style="height: 180px; object-fit: cover;">
                                        </a>
                                        @if ($hotel->is_featured)
                                            <span
                                                class="badge bg-success position-absolute top-0 start-0 m-2">Featured</span>
                                        @endif
                                        @if ($hotel->discount > 0)
                                            <span
                                                class="badge bg-danger position-absolute top-0 end-0 m-2">-{{ $hotel->discount }}%</span>
                                        @endif
                                    </div>
                                    <div class="hotel-info p-3">
                                        <h6 class="hotel-title mb-2">{{ Str::limit($hotel->name, 25) }}</h6>

                                        <div class="hotel-location mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($hotel->city, 15) }},
                                                {{ $hotel->state }}
                                            </small>
                                        </div>

                                        <div class="hotel-price d-flex justify-content-between align-items-center">
                                            <div>
                                                <span
                                                    class="fw-bold text-dark">₦{{ number_format($hotel->price_per_night ?? $hotel->price) }}</span>
                                                <small class="text-muted d-block">per night</small>
                                            </div>
                                            <a href="{{ route('product.show', $hotel->slug) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-calendar-check me-1"></i> Book
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Stores - Jumia Mall Style -->
    @if ($featuredStores->count() > 0)
        <section class="featured-stores py-4">
            <div class="container-fluid">
                <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0"><i class="bi bi-shop text-success me-2"></i> OFFICIAL STORES</h4>
                            <p class="text-muted mb-0 small">Brands you love</p>
                        </div>
                        <a href="{{ route('stores.index') }}" class="btn btn-outline-success btn-sm">View All Stores</a>
                    </div>
                </div>

                <div class="stores-grid">
                    <div class="row g-3">
                        @foreach ($featuredStores->take(8) as $store)
                            <div class="col-6 col-md-3 col-lg-3">
                                <div class="store-card bg-white rounded shadow-sm border">
                                    <a href="{{ route('stores.show', $store->slug) }}" class="text-decoration-none">
                                        <div class="store-logo p-4 text-center">
                                            @if ($store->logo)
                                                <img src="{{ url($store->logo) }}" alt="{{ $store->store_name }}"
                                                    class="img-fluid"
                                                    style="height: 80px; width: auto; max-width: 100%; object-fit: contain;">
                                            @else
                                                <div class="store-icon bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="bi bi-shop fs-3 text-success"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="store-info p-3 border-top text-center">
                                            <h6 class="store-name mb-2 text-dark">{{ Str::limit($store->store_name, 20) }}
                                            </h6>
                                            @if ($store->rating > 0)
                                                <div class="store-rating mb-2">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <span class="small">{{ number_format($store->rating, 1) }}</span>
                                                    <span class="text-muted small">({{ $store->review_count }})</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Services Slider Section -->
    @if (isset($featuredServices) && $featuredServices->count() > 0)
        <section class="services-slider-section py-4">
            <div class="container-fluid">
                <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0"><i class="bi bi-gear text-success me-2"></i> POPULAR SERVICES</h4>
                            <p class="text-muted mb-0 small">Professional services near you</p>
                        </div>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-success btn-sm">View All
                            Services</a>
                    </div>
                </div>

                <div class="position-relative">
                    <!-- Move navigation buttons OUTSIDE the swiper container -->
                    <div class="services-slider swiper">
                        <div class="swiper-wrapper">
                            @foreach ($featuredServices as $service)
                                <div class="swiper-slide">
                                    <div class="service-card bg-white rounded shadow-sm border">
                                        <div class="service-image position-relative">
                                            <a href="{{ route('product.show', $service->slug) }}">
                                                <img src="{{ $service->images[0] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=200&q=80' }}"
                                                    alt="{{ $service->title }}" class="img-fluid w-100 rounded-top"
                                                    style="height: 200px; object-fit: cover;">
                                            </a>
                                            @if ($service->is_featured)
                                                <span
                                                    class="featured-badge position-absolute top-0 start-0 bg-success text-white small px-2 py-1 m-2 rounded">
                                                    <i class="bi bi-star-fill me-1"></i> Featured
                                                </span>
                                            @endif
                                        </div>
                                        <div class="service-info p-1">
                                            <h6 class="service-title mb-2">{{ Str::limit($service->title, 35) }}</h6>

                                            <div class="service-category mb-2 d-none">
                                                <span class="badge bg-light text-dark small">
                                                    {{ $service->category->name ?? 'Service' }}
                                                </span>
                                            </div>

                                            <div class="service-rating mb-2 d-flex align-items-center d-none">
                                                <div class="stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($service->rating))
                                                            <i class="bi bi-star-fill text-warning small"></i>
                                                        @elseif($i == ceil($service->rating) && $service->rating - floor($service->rating) > 0)
                                                            <i class="bi bi-star-half text-warning small"></i>
                                                        @else
                                                            <i class="bi bi-star text-warning small"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ms-2 small text-muted">({{ $service->review_count }})</span>
                                            </div>

                                            <div class="price-section ">
                                                <div class="current-price fw-bold text-dark">
                                                    ₦{{ number_format($service->price) }}
                                                </div>
                                                @if ($service->original_price > $service->price)
                                                    <div
                                                        class="original-price text-muted text-decoration-line-through small">
                                                        ₦{{ number_format($service->original_price) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div
                                                class="service-meta d-flex justify-content-between align-items-center mb-3">
                                                <div class="location small text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ Str::limit($service->location, 15) }}
                                                </div>
                                                @if ($service->negotiable)
                                                    <span
                                                        class="negotiable-badge bg-success bg-opacity-10 text-success small px-2 py-1 rounded">
                                                        Negotiable
                                                    </span>
                                                @endif
                                            </div>

                                            <a href="{{ route('product.show', $service->slug) }}"
                                                class="btn btn-success btn-sm w-100">
                                                <i class="bi bi-calendar-check me-1"></i> Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination should stay inside -->
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- Navigation buttons moved OUTSIDE the swiper container -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>
    @endif

    <!-- All Products Section -->
    <section class="products-grid py-4">
        <div class="container-fluid">
            <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">
                        @if (request()->has('search') && request('search') != '')
                            Search results for: "{{ request('search') }}"
                        @elseif($category)
                            {{ $categories->where('slug', $category)->first()->name ?? 'Products' }}
                            <small class="text-muted">({{ $products->total() }} items)</small>
                        @else
                            RECOMMENDED FOR YOU
                        @endif
                    </h4>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-3 small d-none d-md-block">Sort by:</span>
                        <select class="form-select form-select-sm border-success" id="sortSelect" style="width: auto;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to
                                High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High
                                to
                                Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                            <!--<option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>-->
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Filters (Desktop) -->
                <div class="col-lg-3 d-none d-lg-block">
                    <!-- Replace your filter form section with this: -->
                    <form id="filterForm" method="GET"
                        action="{{ request()->routeIs('home.category')
                            ? route('home.category', ['category' => request()->route('category')])
                            : (request()->routeIs('home')
                                ? route('home')
                                : route('products.index')) }}">

                        <!-- Preserve category parameter if exists -->
                        @if (request()->routeIs('home.category'))
                            <input type="hidden" name="category" value="{{ request()->route('category') }}">
                        @elseif(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <!-- Preserve search parameter if exists -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="filter-sidebar bg-white rounded shadow-sm p-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-filter me-2"></i> FILTERS
                            </h6>

                            <!-- PRICE RANGE -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold mb-2">Price Range (₦)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" placeholder="Min"
                                            class="form-control form-control-sm" value="{{ request('min_price') }}"
                                            min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" placeholder="Max"
                                            class="form-control form-control-sm" value="{{ request('max_price') }}"
                                            min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- CONDITION -->
                            <!-- Replace the condition checkboxes with a select dropdown -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold mb-2">Condition</label>
                                <select class="form-select form-select-sm" name="condition">
                                    <option value="">Any Condition</option>
                                    <option value="New" {{ request('condition') == 'New' ? 'selected' : '' }}>New
                                    </option>
                                    <option value="Used" {{ request('condition') == 'Used' ? 'selected' : '' }}>Used
                                    </option>
                                    <option value="Refurbished"
                                        {{ request('condition') == 'Refurbished' ? 'selected' : '' }}>
                                        Refurbished</option>
                                </select>
                            </div>

                            <!-- LOCATION -->
                            <div class="mb-4 d-none">
                                <label class="form-label small fw-bold mb-2">Location</label>
                                <select class="form-select form-select-sm" name="location">
                                    <option value="">All Nigeria</option>
                                    @php
                                        $states = \App\Models\Location::select('state')
                                            ->distinct()
                                            ->orderBy('state')
                                            ->get();
                                    @endphp
                                    @foreach ($states as $stateItem)
                                        <option value="{{ $stateItem->state }}"
                                            {{ request('location') === $stateItem->state ? 'selected' : '' }}>
                                            {{ $stateItem->state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- NEGOTIABLE -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="negotiable" value="1"
                                        id="negotiableSwitch" {{ request('negotiable') ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold" for="negotiableSwitch">
                                        Negotiable Only
                                    </label>
                                </div>
                            </div>

                            <!-- FILTER BUTTONS -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-filter me-1"></i> Apply Filters
                                </button>

                                <!-- Clear Filter URL -->
                                @php
                                    // Determine the clear URL based on current route
                                    if (request()->routeIs('home.category')) {
                                        $clearUrl = route('home.category', [
                                            'category' => request()->route('category'),
                                        ]);
                                    } elseif (request()->routeIs('home')) {
                                        $clearUrl = route('home');
                                    } else {
                                        $clearUrl = route('products.index');
                                    }

                                    // Preserve search if exists
                                    if (request('search')) {
                                        $clearUrl .= '?search=' . request('search');
                                    }
                                @endphp

                                <a href="{{ $clearUrl }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i> Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Mobile Filter Button -->
                    <div class="d-block d-lg-none mb-3">
                        <button class="btn btn-success w-100" onclick="toggleMobileFilters()">
                            <i class="bi bi-filter me-2"></i> Filters & Categories
                        </button>
                    </div>

                    <!-- Mobile Filters (Hidden by default) -->
                    <div class="mobile-filters bg-white rounded shadow-sm p-3 mb-3 d-none" id="mobileFilters">

                        <form id="mobileFilterForm" method="GET"
                            action="{{ request()->routeIs('home.category')
                                ? route('home.category', ['category' => request()->route('category')])
                                : (request()->routeIs('home')
                                    ? route('home')
                                    : route('products.index')) }}">

                            <!-- Preserve category parameter if exists -->
                            @if (request()->routeIs('home.category'))
                                <input type="hidden" name="category" value="{{ request()->route('category') }}">
                            @elseif(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif

                            <!-- Preserve search parameter if exists -->
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="filter-sidebar bg-white rounded shadow-sm p-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-filter me-2"></i> FILTERS
                                </h6>

                                <!-- PRICE RANGE -->
                                <div class="mb-4">
                                    <label class="form-label small fw-bold mb-2">Price Range (₦)</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_price" placeholder="Min"
                                                class="form-control form-control-sm" value="{{ request('min_price') }}"
                                                min="0">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_price" placeholder="Max"
                                                class="form-control form-control-sm" value="{{ request('max_price') }}"
                                                min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- CONDITION -->
                                <!-- Replace the condition checkboxes with a select dropdown -->
                                <div class="mb-4">
                                    <label class="form-label small fw-bold mb-2">Condition</label>
                                    <select class="form-select form-select-sm" name="condition">
                                        <option value="">Any Condition</option>
                                        <option value="New" {{ request('condition') == 'New' ? 'selected' : '' }}>New
                                        </option>
                                        <option value="Used" {{ request('condition') == 'Used' ? 'selected' : '' }}>Used
                                        </option>
                                        <option value="Refurbished"
                                            {{ request('condition') == 'Refurbished' ? 'selected' : '' }}>Refurbished
                                        </option>
                                    </select>
                                </div>

                                <!-- LOCATION -->
                                <div class="mb-4 d-none">
                                    <label class="form-label small fw-bold mb-2">Location</label>
                                    <select class="form-select form-select-sm" name="location">
                                        <option value="">All Nigeria</option>
                                        @php
                                            $states = \App\Models\Location::select('state')
                                                ->distinct()
                                                ->orderBy('state')
                                                ->get();
                                        @endphp
                                        @foreach ($states as $stateItem)
                                            <option value="{{ $stateItem->state }}"
                                                {{ request('location') === $stateItem->state ? 'selected' : '' }}>
                                                {{ $stateItem->state }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- NEGOTIABLE -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="negotiable" value="1"
                                            id="negotiableSwitch" {{ request('negotiable') ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="negotiableSwitch">
                                            Negotiable Only
                                        </label>
                                    </div>
                                </div>

                                <!-- FILTER BUTTONS -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-filter me-1"></i> Apply Filters
                                    </button>

                                    <!-- Clear Filter URL -->
                                    @php
                                        // Determine the clear URL based on current route
                                        if (request()->routeIs('home.category')) {
                                            $clearUrl = route('home.category', [
                                                'category' => request()->route('category'),
                                            ]);
                                        } elseif (request()->routeIs('home')) {
                                            $clearUrl = route('home');
                                        } else {
                                            $clearUrl = route('products.index');
                                        }

                                        // Preserve search if exists
                                        if (request('search')) {
                                            $clearUrl .= '?search=' . request('search');
                                        }
                                    @endphp

                                    <a href="{{ $clearUrl }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-x-circle me-1"></i> Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="products-listing">
                        @if ($products->count() > 0)
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                @foreach ($products as $product)
                                    <div class="col">
                                        <div class="product-card bg-white rounded shadow-sm border">
                                            <div class="product-image position-relative">
                                                <a href="{{ route('product.show', $product->slug) }}">
                                                    <img src="{{ $product->images[0] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=200&q=80' }}"
                                                        alt="{{ $product->title }}" class="img-fluid w-100"
                                                        style="height: 200px; object-fit: cover;">
                                                </a>
                                                @if ($product->is_featured)
                                                    <span
                                                        class="featured-badge position-absolute top-0 start-0 bg-success text-white small px-2 py-1 m-2 rounded">
                                                        <i class="bi bi-star-fill me-1"></i> Featured
                                                    </span>
                                                @endif
                                                @if ($product->discount > 0)
                                                    <span
                                                        class="discount-badge position-absolute top-0 end-0 bg-danger text-white small px-2 py-1 m-2 rounded">
                                                        -{{ $product->discount }}%
                                                    </span>
                                                @endif
                                                <button
                                                    class="wishlist-btn position-absolute top-0 end-0 bg-white rounded-circle border-0 m-2"
                                                    style="width: 32px; height: 32px;"
                                                    onclick="toggleWishlist({{ $product->id }})">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            </div>
                                            <div class="product-info p-1">
                                                <h6 class="product-title mb-2">
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="text-dark text-decoration-none">
                                                        {{ Str::limit($product->title, 30) }}
                                                    </a>
                                                </h6>

                                                <div class="product-category mb-2 d-none">
                                                    <span class="badge bg-light text-dark small">
                                                        {{ $product->category->name ?? 'Uncategorized' }}
                                                    </span>
                                                </div>

                                                <div class="price-section ">
                                                    <div class="current-price fw-bold text-dark fs-5">
                                                        ₦{{ number_format($product->price) }}
                                                    </div>
                                                    @if ($product->original_price > $product->price)
                                                        <div
                                                            class="original-price text-muted text-decoration-line-through small">
                                                            ₦{{ number_format($product->original_price) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div
                                                    class="product-meta d-flex justify-content-between align-items-center">
                                                    <div class="location small text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ Str::limit($product->location, 12) }}
                                                    </div>
                                                    @if ($product->negotiable)
                                                        <span
                                                            class="negotiable-badge bg-success bg-opacity-10 text-success small px-2 py-1 rounded">
                                                            Negotiable
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product-actions p-3 border-top">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="btn btn-success btn-sm w-100">
                                                    <i class="bi bi-eye me-1"></i> View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if ($products->hasPages())
                                <div class="mt-5">
                                    <nav aria-label="Products pagination">
                                        <ul class="pagination justify-content-center">
                                            @if ($products->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $products->previousPageUrl() }}">&laquo;</a>
                                                </li>
                                            @endif

                                            @foreach ($products->links()->elements[0] as $page => $url)
                                                @if ($page == $products->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            @if ($products->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $products->nextPageUrl() }}">&raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">&raquo;</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5 bg-white rounded shadow-sm">
                                <i class="bi bi-search display-1 text-muted mb-3"></i>
                                <h4 class="text-muted mb-2">No products found</h4>
                                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                                <a href="{{ route('home') }}" class="btn btn-success">
                                    <i class="bi bi-house-door me-1"></i> Browse All Products
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        :root {
            /* --jumia-green: #8bc34a;
            --jumia-green-dark: #689f38; */
            --jumia-green: #ff6a00;
            --jumia-green-dark: #ff6a00;
            --jumia-orange: #ff9800;
            --jumia-red: #f44336;
            --jumia-blue: #2196f3;
            --jumia-yellow: #ffeb3b;
            --jumia-gray: #f5f5f5;
            --jumia-dark: #333333;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Welcome Banner for Guests */
        .welcome-banner {
            background: linear-gradient(135deg, var(--jumia-green) 0%, var(--jumia-green-dark) 100%);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .welcome-banner h1 {
            font-size: 2.5rem;
            font-weight: 800;
        }

        .welcome-banner .lead {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.95;
        }

        /* Categories Sidebar */
        .jumia-hero .categories-sidebar {
            height: 400px;
            overflow-y: auto;
        }

        .category-item {
            position: relative;
        }

        .category-item:hover {
            background-color: #f8f9fa;
        }

        .category-item:hover .subcategories-dropdown {
            display: block !important;
        }

        .subcategories-dropdown {
            min-width: 300px;
            z-index: 1000;
            border: 1px solid #dee2e6;
        }

        .hover-bg:hover {
            background-color: #f8f9fa;
        }

        /* Quick Categories */
        .quick-category-item {
            color: var(--jumia-dark);
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .quick-category-item:hover {
            background-color: var(--jumia-green);
            color: white;
            text-decoration: none;
        }

        .quick-category-item:hover .icon-wrapper i {
            color: white !important;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* Flash Sales */
        .flash-product-card {
            transition: transform 0.3s ease;
        }

        .flash-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .discount-badge {
            font-size: 12px;
            font-weight: bold;
            z-index: 2;
        }

        .progress {
            background-color: #e9ecef;
        }

        /* Hotel Cards */
        .hotel-card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: var(--jumia-green) !important;
        }

        /* Product Cards */
        .product-card {
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15) !important;
            border-color: var(--jumia-green) !important;
        }

        .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .wishlist-btn {
            transition: all 0.3s ease;
        }

        .wishlist-btn:hover {
            background-color: var(--jumia-red) !important;
            color: white;
        }

        .wishlist-btn.active {
            background-color: var(--jumia-red) !important;
            color: white;
        }

        .product-title,
        .service-title {
            font-size: 13px !important;
            line-height: 1.4;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            position: sticky;
            top: 20px;
        }

        .form-check-input:checked {
            background-color: var(--jumia-green);
            border-color: var(--jumia-green);
        }

        .form-switch .form-check-input:checked {
            background-color: var(--jumia-green);
            border-color: var(--jumia-green);
        }

        /* Store Cards */
        .store-card {
            transition: all 0.3s ease;
        }

        .store-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: var(--jumia-green) !important;
            text-decoration: none;
        }

        /* Service Cards & Slider */
        .service-card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .services-slider {
            padding: 20px 10px;
        }

        .services-slider .swiper-slide {
            height: auto;
        }

        .services-slider .swiper-button-next,
        .services-slider .swiper-button-prev {
            color: var(--jumia-green);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .services-slider .swiper-button-next:after,
        .services-slider .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }

        .services-slider .swiper-button-next:hover,
        .services-slider .swiper-button-prev:hover {
            background: var(--jumia-green);
            color: white;
        }

        .services-slider .swiper-pagination-bullet {
            background: var(--jumia-green);
            opacity: 0.5;
            width: 8px;
            height: 8px;
        }

        .services-slider .swiper-pagination-bullet-active {
            opacity: 1;
            width: 20px;
            border-radius: 4px;
        }

        /* Pagination */
        .pagination .page-link {
            color: var(--jumia-green);
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--jumia-green);
            border-color: var(--jumia-green);
            color: white;
        }

        .pagination .page-link:hover {
            color: var(--jumia-green-dark);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .welcome-banner {
                padding: 20px !important;
            }

            .welcome-banner h1 {
                font-size: 1.8rem !important;
            }

            .welcome-banner .lead {
                font-size: 1rem !important;
            }

            .welcome-banner .btn {
                padding: 8px 16px !important;
                font-size: 14px !important;
            }

            .welcome-banner .col-lg-6:last-child {
                display: none;
            }

            .jumia-hero .carousel-item img {
                height: 200px !important;
            }

            .hero-banner {
                height: 200px;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .icon-wrapper i {
                font-size: 1.5rem !important;
            }

            .quick-category-item {
                padding: 8px 4px !important;
            }

            .product-card .product-image img,
            .service-card .service-image img {
                height: 150px !important;
            }

            .hotel-card .hotel-image img {
                height: 150px !important;
            }

            .flash-product-card .product-image img {
                height: 100px !important;
            }

            .product-title,
            .hotel-title,
            .service-title {
                font-size: 13px !important;
                height: 36px;
            }

            .section-header {
                padding: 15px !important;
            }

            .mobile-filters {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1050;
                overflow-y: auto;
                display: none;
            }

            .mobile-filters.show {
                display: block;
            }

            .countdown-timer .badge {
                padding: 5px 10px !important;
                font-size: 12px;
            }

            .services-slider {
                padding: 10px 5px;
            }

            .services-slider .swiper-button-next,
            .services-slider .swiper-button-prev {
                display: none;
            }
        }

        @media (max-width: 576px) {

            .products-listing .row-cols-2>.col,
            .hotels-grid .row-cols-2>.col {
                padding: 6px;
            }

            .product-card,
            .hotel-card,
            .service-card {
                padding: 0;
            }

            .product-info,
            .hotel-info,
            .service-info {
                padding: 12px !important;
            }

            .product-actions {
                padding: 12px !important;
            }

            .btn {
                padding: 5px 10px;
                font-size: 12px;
            }

            .current-price {
                font-size: 16px !important;
            }
        }

        /* Loading animation */
        .product-card.loading,
        .hotel-card.loading,
        .service-card.loading {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        /* Add to your CSS section */
        .services-slider-section .swiper-button-next,
        .services-slider-section .swiper-button-prev {
            color: var(--jumia-green);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .services-slider-section .swiper-button-next:after,
        .services-slider-section .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }

        .services-slider-section .swiper-button-next:hover,
        .services-slider-section .swiper-button-prev:hover {
            background: var(--jumia-green);
            color: white;
        }

        /* Position the buttons properly */
        .services-slider-section .swiper-button-next {
            right: -20px;
        }

        .services-slider-section .swiper-button-prev {
            left: -20px;
        }

        /* Hide navigation buttons on mobile */
        @media (max-width: 768px) {

            .services-slider-section .swiper-button-next,
            .services-slider-section .swiper-button-prev {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize countdown timer for flash sales
            function updateCountdown() {
                const now = new Date();
                const endTime = new Date();
                endTime.setHours(23, 59, 59, 999);

                const diff = endTime - now;

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                const hoursEl = document.getElementById('countdown-hours');
                const minutesEl = document.getElementById('countdown-minutes');
                const secondsEl = document.getElementById('countdown-seconds');

                if (hoursEl && minutesEl && secondsEl) {
                    hoursEl.textContent = hours.toString().padStart(2, '0');
                    minutesEl.textContent = minutes.toString().padStart(2, '0');
                    secondsEl.textContent = seconds.toString().padStart(2, '0');
                }
            }

            setInterval(updateCountdown, 1000);
            updateCountdown();

            // Sort functionality - update to preserve all query params
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', this.value);
                    window.location.href = url.toString();
                });
            }

            // Initialize Services Slider
            const servicesSlider = document.querySelector('.services-slider');
            if (servicesSlider) {
                const servicesSwiper = new Swiper('.services-slider', {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.services-slider-section .swiper-button-next',
                        prevEl: '.services-slider-section .swiper-button-prev',
                    },
                    pagination: {
                        el: '.services-slider .swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 15
                        },
                        768: {
                            slidesPerView: 3,
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 20
                        }
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    loop: false,
                });
            }

            // Mobile filter toggle
            window.toggleMobileFilters = function() {
                const mobileFilters = document.getElementById('mobileFilters');
                mobileFilters.classList.toggle('show');
                mobileFilters.classList.toggle('d-none');
            };


            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    // Prevent default to handle form manually
                    e.preventDefault();

                    // Create a new FormData object
                    const formData = new FormData(this);
                    const params = new URLSearchParams();

                    // Add all form data, filtering out empty values
                    for (const [key, value] of formData.entries()) {
                        // Skip empty values
                        if (value === '' || value === null || value === 'undefined') {
                            continue;
                        }

                        // For condition checkboxes, only add checked ones
                        if (key === 'condition[]') {
                            if (value !== '') {
                                params.append(key, value);
                            }
                        } else {
                            params.append(key, value);
                        }
                    }

                    // Build the URL
                    let url = this.action;
                    const queryString = params.toString();

                    if (queryString) {
                        url += (url.includes('?') ? '&' : '?') + queryString;
                    }

                    // Redirect to the filtered URL
                    window.location.href = url;
                });
            }

            // Also update mobile filter form
            const mobileFilterForm = document.getElementById('mobileFilterForm');
            if (mobileFilterForm) {
                mobileFilterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const params = new URLSearchParams();

                    for (const [key, value] of formData.entries()) {
                        if (value === '' || value === null || value === 'undefined') {
                            continue;
                        }

                        if (key === 'condition[]' && value !== '') {
                            params.append(key, value);
                        } else if (value !== '') {
                            params.append(key, value);
                        }
                    }

                    let url = this.action;
                    const queryString = params.toString();

                    if (queryString) {
                        url += (url.includes('?') ? '&' : '?') + queryString;
                    }

                    window.location.href = url;
                });
            }

            // Function to clean up current URL (remove empty parameters)
            function cleanCurrentURL() {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);
                let hasChanges = false;

                // Remove empty parameters
                for (const [key, value] of params.entries()) {
                    if (value === '' || value === 'null' || value === 'undefined') {
                        params.delete(key);
                        hasChanges = true;
                    }
                }

                // Update URL in browser if changes were made
                if (hasChanges) {
                    const newUrl = params.toString() ? `${url.pathname}?${params.toString()}` : url.pathname;
                    window.history.replaceState({}, '', newUrl);
                }
            }

            // Clean URL on page load
            cleanCurrentURL();











            // Initialize carousel
            const heroCarousel = document.getElementById('heroCarousel');
            if (heroCarousel) {
                const carousel = new bootstrap.Carousel(heroCarousel, {
                    interval: 5000,
                    wrap: true
                });
            }

            // Category hover effects
            const categoryItems = document.querySelectorAll('.category-item');
            categoryItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const dropdown = this.querySelector('.subcategories-dropdown');
                    if (dropdown) {
                        dropdown.classList.remove('d-none');
                    }
                });

                item.addEventListener('mouseleave', function() {
                    const dropdown = this.querySelector('.subcategories-dropdown');
                    if (dropdown) {
                        dropdown.classList.add('d-none');
                    }
                });
            });

            // Price range validation
            const minPriceInputs = document.querySelectorAll('input[name="min_price"]');
            const maxPriceInputs = document.querySelectorAll('input[name="max_price"]');

            minPriceInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const maxInput = this.closest('form').querySelector('input[name="max_price"]');
                    if (this.value && maxInput.value && parseInt(this.value) > parseInt(maxInput
                            .value)) {
                        alert('Minimum price cannot be greater than maximum price');
                        this.value = '';
                    }
                });
            });

            maxPriceInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const minInput = this.closest('form').querySelector('input[name="min_price"]');
                    if (this.value && minInput.value && parseInt(this.value) < parseInt(minInput
                            .value)) {
                        alert('Maximum price cannot be less than minimum price');
                        this.value = '';
                    }
                });
            });

            // Wishlist toggle (placeholder function)
            window.toggleWishlist = function(productId) {
                const button = event.currentTarget;
                const icon = button.querySelector('i');

                icon.classList.toggle('bi-heart');
                icon.classList.toggle('bi-heart-fill');
                button.classList.toggle('active');

                // TODO: Implement AJAX call to add/remove from wishlist
                console.log('Toggle wishlist for product:', productId);
            };
        });
    </script>
@endpush

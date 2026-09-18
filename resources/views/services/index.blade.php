@extends('layout.layout')
@section('title', 'Agii Services - Professional Services in Nigeria')

@section('content')
    <!-- Jumia Style Hero Banner for Services -->
    <section class="services-hero py-5" style="background: linear-gradient(135deg, #90c74b 0%, #74a534 100%);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">Professional Services in Nigeria</h1>
                    <p class="lead mb-4">Discover skilled professionals for all your needs. From home repairs and tutoring to event planning and digital services, find trusted experts ready to help.</p>
                    <div class="d-flex gap-3">
                        @auth
                            <!--<a href="{{ url('login') }}" class="btn btn-light btn-lg text-success fw-bold">-->
                            <!--    <i class="bi bi-plus-circle me-2"></i> List a Service-->
                            <!--</a>-->
                        @else
                            <a href="/register" class="btn btn-light btn-lg text-success fw-bold">Start Offering Services</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&h=400&q=80" 
                             alt="Professional Services" class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Categories Grid -->
    <section class="service-categories py-4 d-none">
        <div class="container-fluid">
            <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0"><i class="bi bi-grid text-success me-2"></i> SERVICE CATEGORIES</h4>
                        <p class="text-muted mb-0 small">Browse services by category</p>
                    </div>
                   
                </div>
            </div>

            <div class="categories-grid">
                <div class="row g-3">
                    @foreach($serviceCategories as $category)
                        @php
                            $serviceCount = \App\Models\Product::where('category_id', $category->id)
                                ->where('status', 'active')
                                ->whereHas('user', fn($q) => $q->whereHas('activeSubscription'))
                                ->count();
                        @endphp
                        
                        @if($serviceCount > 0)
                            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                <a href="{{ route('services.index', ['category' => $category->slug]) }}" 
                                   class="category-card bg-white rounded shadow-sm border p-3 text-center text-decoration-none d-block">
                                    <div class="category-icon mb-3">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} fs-1 text-success"></i>
                                        @else
                                            <i class="bi bi-gear fs-1 text-success"></i>
                                        @endif
                                    </div>
                                    <h6 class="category-title mb-2 text-dark">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $serviceCount }} services</small>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Services Search & Filter Section -->
    <section class="services-main py-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="filter-sidebar bg-white rounded shadow-sm p-3 sticky-top" style="top: 20px;">
                        <form id="serviceFilterForm" method="GET" action="{{ route('services.index') }}">
                            @if($category)
                                <input type="hidden" name="category" value="{{ $category }}">
                            @endif
                            
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-funnel me-2"></i> FILTER SERVICES</h6>
                            
                            <!-- Search -->
                            <div class="filter-group mb-4">
                                <label class="form-label small fw-bold">Search Services</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="What service do you need?"
                                           value="{{ request('search') }}">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="filter-group mb-4">
                                <label class="form-label small fw-bold mb-2">Price Range (₦)</label>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="number" 
                                               name="min_price" 
                                               class="form-control form-control-sm" 
                                               placeholder="Min" 
                                               value="{{ request('min_price') }}"
                                               min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" 
                                               name="max_price" 
                                               class="form-control form-control-sm" 
                                               placeholder="Max" 
                                               value="{{ request('max_price') }}"
                                               min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="filter-group mb-4 d-none">
                                <label class="form-label small fw-bold">Service Location</label>
                                <select class="form-select form-select-sm" name="location">
                                    <option value="">All Locations</option>
                                    <option value="Lagos" {{ request('location') == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                                    <option value="Abuja" {{ request('location') == 'Abuja' ? 'selected' : '' }}>Abuja</option>
                                    <option value="Port Harcourt" {{ request('location') == 'Port Harcourt' ? 'selected' : '' }}>Port Harcourt</option>
                                    <option value="Ibadan" {{ request('location') == 'Ibadan' ? 'selected' : '' }}>Ibadan</option>
                                    <option value="Online" {{ request('location') == 'Online' ? 'selected' : '' }}>Online/Remote</option>
                                </select>
                            </div>

                            <!-- Service Type -->
                            <div class="filter-group mb-4 d-none">
                                <label class="form-label small fw-bold">Service Type</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="service_type[]" 
                                           value="on_site" 
                                           id="onSite"
                                           {{ in_array('on_site', (array)request('service_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="onSite">On-site Services</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="service_type[]" 
                                           value="remote" 
                                           id="remote"
                                           {{ in_array('remote', (array)request('service_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="remote">Remote/Online</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="service_type[]" 
                                           value="flexible" 
                                           id="flexible"
                                           {{ in_array('flexible', (array)request('service_type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="flexible">Flexible</label>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="filter-group mb-4 d-none">
                                <label class="form-label small fw-bold">Minimum Rating</label>
                                <select class="form-select form-select-sm" name="min_rating">
                                    <option value="">Any Rating</option>
                                    <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ Stars</option>
                                    <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                </select>
                            </div>

                            <!-- Availability -->
                            <div class="filter-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="available_now" 
                                           value="1" 
                                           id="availableNow"
                                           {{ request('available_now') ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold" for="availableNow">Available Now</label>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-filter me-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('services.index', ['category' => $category]) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i> Clear Filters
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="col-lg-9">
                    <!-- Header with Sort -->
                    <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-0">
                                    @if(request()->has('search') && request('search') != '')
                                        <i class="bi bi-search text-success me-2"></i> 
                                        Search: "{{ request('search') }}"
                                    @elseif($category)
                                        <i class="bi bi-folder text-success me-2"></i>
                                        {{ $serviceCategories->where('slug', $category)->first()->name ?? 'All Services' }}
                                    @else
                                        <i class="bi bi-gear text-success me-2"></i> 
                                        ALL SERVICES
                                    @endif
                                    <small class="text-muted">({{ $featuredServices->total() }} services)</small>
                                </h4>
                                <p class="text-muted mb-0 small">
                                    @if($category && $categoryDescription = $serviceCategories->where('slug', $category)->first()->description ?? '')
                                        {{ $categoryDescription }}
                                    @else
                                        Professional services for all your needs
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted small d-none d-md-block">Sort by:</span>
                                <select class="form-select form-select-sm border-success" id="sortSelect" style="width: auto;">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                    <!--<option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>-->
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Filter Button -->
                    <div class="d-block d-lg-none mb-3">
                        <button class="btn btn-success w-100" onclick="toggleServiceFilters()">
                            <i class="bi bi-funnel me-2"></i> Filters & Categories
                        </button>
                    </div>

                    <!-- Services Grid -->
                    <div class="services-grid">
                        @if($featuredServices->count() > 0)
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-3">
                                @foreach($featuredServices as $service)
                                    <div class="col">
                                        <div class="service-card bg-white rounded shadow-sm border">
                                            <div class="service-image position-relative">
                                                <a href="{{ route('product.show', $service->slug) }}">
                                                    <img src="{{ $service->images[0] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=200&q=80' }}" 
                                                         alt="{{ $service->title }}" 
                                                         class="img-fluid w-100 rounded-top" 
                                                         style="height: 180px; object-fit: cover;">
                                                </a>
                                                @if($service->is_featured)
                                                    <span class="featured-badge position-absolute top-0 start-0 bg-success text-white small px-2 py-1 m-2 rounded">
                                                        <i class="bi bi-star-fill me-1"></i> Featured
                                                    </span>
                                                @endif
                                                @if($service->discount > 0)
                                                    <span class="discount-badge position-absolute top-0 end-0 bg-danger text-white small px-2 py-1 m-2 rounded">
                                                        -{{ $service->discount }}%
                                                    </span>
                                                @endif
                                                <button class="wishlist-btn position-absolute top-0 end-0 bg-white rounded-circle border-0 m-2" 
                                                        style="width: 32px; height: 32px;"
                                                        onclick="toggleWishlist({{ $service->id }})">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="service-info p-3">
                                                <div class="service-header mb-1">
                                                    <h6 class="service-title mb-1">
                                                        <a href="{{ route('product.show', $service->slug) }}" 
                                                           class="text-dark text-decoration-none">
                                                            {{ Str::limit($service->title, 40) }}
                                                        </a>
                                                    </h6>
                                                    <div class="service-category">
                                                        <span class="badge bg-light text-dark small">
                                                            {{ $service->category->name ?? 'Service' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="service-description mb-3 d-none d-md-block">
                                                    <p class="text-muted small mb-0">
                                                        {{ Str::limit($service->description, 70) }}
                                                    </p>
                                                </div>

                                                <div class="service-rating mb-2 d-flex align-items-center d-none">
                                                    <div class="stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= floor($service->rating))
                                                                <i class="bi bi-star-fill text-warning small"></i>
                                                            @elseif($i == ceil($service->rating) && $service->rating - floor($service->rating) > 0)
                                                                <i class="bi bi-star-half text-warning small"></i>
                                                            @else
                                                                <i class="bi bi-star text-warning small"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="ms-2 small">{{ number_format($service->rating, 1) }}</span>
                                                    <span class="text-muted small ms-1">({{ $service->review_count }})</span>
                                                </div>

                                                <div class="service-meta d-flex justify-content-between align-items-center mb-3">
                                                    <div class="location small text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i> 
                                                        @if(str_contains(strtolower($service->location), 'online') || str_contains(strtolower($service->location), 'remote'))
                                                            <span class="text-success">Online/Remote</span>
                                                        @else
                                                            {{ Str::limit($service->location, 15) }}
                                                        @endif
                                                    </div>
                                                    @if($service->negotiable)
                                                        <span class="negotiable-badge bg-success bg-opacity-10 text-success small px-2 py-1 rounded">
                                                            Negotiable
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="service-price-action d-flex justify-content-between align-items-center">
                                                    <div class="price-section">
                                                        <div class="current-price fw-bold text-dark fs-5">
                                                            ₦{{ number_format($service->price) }}
                                                        </div>
                                                        @if($service->original_price > $service->price)
                                                            <div class="original-price text-muted text-decoration-line-through small">
                                                                ₦{{ number_format($service->original_price) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('product.show', $service->slug) }}" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="bi bi-calendar-check me-1"></i> Book Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($featuredServices->hasPages())
                                <div class="mt-4">
                                    <nav aria-label="Services pagination">
                                        <ul class="pagination justify-content-center">
                                            <!-- Previous Page Link -->
                                            @if($featuredServices->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $featuredServices->previousPageUrl() }}">&laquo;</a>
                                                </li>
                                            @endif
                                            
                                            <!-- Pagination Elements -->
                                            @php
                                                $elements = $featuredServices->links()->elements;
                                                $firstElement = $elements[0] ?? [];
                                            @endphp
                                            
                                            @if(is_array($firstElement))
                                                @foreach($firstElement as $page => $url)
                                                    @if($page == $featuredServices->currentPage())
                                                        <li class="page-item active">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                            
                                            <!-- Next Page Link -->
                                            @if($featuredServices->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $featuredServices->nextPageUrl() }}">&raquo;</a>
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
                                <h4 class="text-muted mb-2">No services found</h4>
                                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                                <a href="{{ route('services.index') }}" class="btn btn-success">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Service Provider CTA -->
                    @guest
                    <div class="service-provider-cta bg-success rounded shadow-sm p-4 text-white mt-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-3">Are you a service provider?</h4>
                                <p class="mb-0">Join thousands of professionals offering their services on Agii. Reach new customers and grow your business.</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="/register" class="btn btn-light btn-lg text-success fw-bold">
                                    <i class="bi bi-plus-circle me-2"></i> Get Started
                                </a>
                            </div>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Service Categories -->
    @if($serviceCategories->count() > 0)
    <section class="popular-categories py-4 d-none">
        <div class="container-fluid">
            <div class="section-header bg-white rounded shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0"><i class="bi bi-fire text-success me-2"></i> POPULAR SERVICE CATEGORIES</h4>
                        <p class="text-muted mb-0 small">Browse our most requested services</p>
                    </div>
                </div>
            </div>

            <div class="categories-slider position-relative">
                <div class="swiper popular-categories-slider">
                    <div class="swiper-wrapper">
                        @foreach($serviceCategories->take(10) as $category)
                            @php
                                $serviceCount = \App\Models\Product::where('category_id', $category->id)
                                    ->where('status', 'active')
                                    ->whereHas('user', fn($q) => $q->whereHas('activeSubscription'))
                                    ->count();
                            @endphp
                            
                            @if($serviceCount > 0)
                                <div class="swiper-slide">
                                    <a href="{{ route('services.index', ['category' => $category->slug]) }}" 
                                       class="popular-category-card bg-white rounded shadow-sm border p-4 text-center text-decoration-none d-block h-100">
                                        <div class="category-icon mb-3">
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} fs-2 text-success"></i>
                                            @else
                                                <i class="bi bi-gear fs-2 text-success"></i>
                                            @endif
                                        </div>
                                        <h6 class="category-title mb-2 text-dark">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $serviceCount }} services</small>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <div class="swiper-button-next popular-categories-next"></div>
                <div class="swiper-button-prev popular-categories-prev"></div>
            </div>
        </div>
    </section>
    @endif
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    :root {
        --jumia-green: #8bc34a;
        --jumia-green-dark: #689f38;
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

    /* Services Hero */
    .services-hero {
        background: linear-gradient(135deg, var(--jumia-green) 0%, var(--jumia-green-dark) 100%) !important;
    }

    .services-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
    }

    .services-hero .lead {
        font-size: 1.2rem;
        line-height: 1.6;
        opacity: 0.95;
    }

    .hero-image img {
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Category Cards */
    .category-card {
        transition: all 0.3s ease;
        height: 100%;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1) !important;
        border-color: var(--jumia-green) !important;
        text-decoration: none;
    }

    .category-icon {
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1);
    }

    .category-title {
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Service Cards */
    .service-card {
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        border-color: var(--jumia-green) !important;
    }

    .service-image {
        position: relative;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }

    .service-image img {
        transition: transform 0.3s ease;
    }

    .service-card:hover .service-image img {
        transform: scale(1.05);
    }

    .service-title {
        font-size: 0.95rem;
        line-height: 1.4;
        font-weight: 600;
        /*min-height: 40px;*/
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .service-description {
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .wishlist-btn {
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wishlist-btn:hover {
        background-color: var(--jumia-red) !important;
        color: white;
    }

    .wishlist-btn.active {
        background-color: var(--jumia-red) !important;
        color: white;
    }

    .featured-badge, .discount-badge {
        font-size: 11px;
        font-weight: 600;
        z-index: 1;
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

    /* Service Provider CTA */
    .service-provider-cta {
        background: linear-gradient(135deg, var(--jumia-green) 0%, var(--jumia-green-dark) 100%);
    }

    /* Popular Categories Slider */
    .popular-categories-slider {
        padding: 20px 10px;
    }

    .popular-categories-slider .swiper-slide {
        height: auto;
    }

    .popular-categories-next,
    .popular-categories-prev {
        color: var(--jumia-green);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .popular-categories-next:after,
    .popular-categories-prev:after {
        font-size: 18px;
        font-weight: bold;
    }

    .popular-categories-next:hover,
    .popular-categories-prev:hover {
        background: var(--jumia-green);
        color: white;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .services-hero h1 {
            font-size: 2rem !important;
        }
        
        .services-hero .lead {
            font-size: 1rem !important;
        }
        
        .services-hero .btn {
            padding: 10px 20px !important;
            font-size: 14px !important;
        }

        .service-image img {
            height: 150px !important;
        }

        .service-title {
            font-size: 0.9rem;
            min-height: 36px;
        }

        .section-header {
            padding: 15px !important;
        }

        .popular-categories-next,
        .popular-categories-prev {
            display: none;
        }

        .service-provider-cta {
            text-align: center;
        }
        
        .service-provider-cta .btn {
            margin-top: 15px;
        }
    }

    @media (max-width: 576px) {
        .category-card {
            padding: 15px !important;
        }
        
        .category-icon i {
            font-size: 1.5rem !important;
        }
        
        .service-card {
            padding: 0;
        }
        
        .service-info {
            padding: 15px !important;
        }
        
        .btn {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sort functionality
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.value);
                window.location.href = url.toString();
            });
        }
        
        // Initialize Categories Slider
        const categoriesSlider = document.querySelector('.popular-categories-slider');
        if (categoriesSlider) {
            const categoriesSwiper = new Swiper('.popular-categories-slider', {
                slidesPerView: 1,
                spaceBetween: 15,
                navigation: {
                    nextEl: '.popular-categories-next',
                    prevEl: '.popular-categories-prev',
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
                        spaceBetween: 20
                    }
                }
            });
        }
        
        // Mobile filter toggle
        window.toggleServiceFilters = function() {
            const filterSidebar = document.querySelector('.filter-sidebar');
            if (filterSidebar) {
                filterSidebar.classList.toggle('d-block');
                filterSidebar.classList.toggle('d-none');
            }
        };
        
        // Filter form validation
        const filterForm = document.getElementById('serviceFilterForm');
        if (filterForm) {
            const minPriceInput = filterForm.querySelector('input[name="min_price"]');
            const maxPriceInput = filterForm.querySelector('input[name="max_price"]');
            
            if (minPriceInput && maxPriceInput) {
                minPriceInput.addEventListener('change', function() {
                    if (this.value && maxPriceInput.value && parseInt(this.value) > parseInt(maxPriceInput.value)) {
                        alert('Minimum price cannot be greater than maximum price');
                        this.value = '';
                    }
                });
                
                maxPriceInput.addEventListener('change', function() {
                    if (this.value && minPriceInput.value && parseInt(this.value) < parseInt(minPriceInput.value)) {
                        alert('Maximum price cannot be less than minimum price');
                        this.value = '';
                    }
                });
            }
            
            filterForm.addEventListener('submit', function(e) {
                // Remove empty checkbox values
                const checkboxes = this.querySelectorAll('input[type="checkbox"]:not(:checked)');
                checkboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                });
            });
        }
        
        // Wishlist toggle (placeholder function)
        window.toggleWishlist = function(serviceId) {
            const button = event.currentTarget;
            const icon = button.querySelector('i');
            
            icon.classList.toggle('bi-heart');
            icon.classList.toggle('bi-heart-fill');
            button.classList.toggle('active');
            
            // TODO: Implement AJAX call to add/remove from wishlist
            console.log('Toggle wishlist for service:', serviceId);
            
            // Show toast notification
            showToast('Service added to wishlist');
        };
        
        // Toast notification function
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded shadow-lg';
            toast.style.zIndex = '1060';
            toast.innerHTML = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
        
        // Initialize tooltips for wishlist buttons
        const wishlistButtons = document.querySelectorAll('.wishlist-btn');
        wishlistButtons.forEach(button => {
            button.setAttribute('title', 'Add to wishlist');
            button.setAttribute('data-bs-toggle', 'tooltip');
        });
        
        // Initialize Bootstrap tooltips if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endpush
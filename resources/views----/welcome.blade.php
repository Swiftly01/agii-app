@extends('layout.layout')
@section('title', 'Agii - Buy and Sell Anything in Nigeria')

@section('content')
    <!-- Banner Section -->
    <section class="banner-section py-5" style="background: linear-gradient(135deg, #90c74b 0%, #74a534 100%);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h1 class="display-4 fw-bold mb-4">Buy and Sell Anything in Nigeria</h1>
                    <p class="lead mb-4">Discover the best deals on thousands of products. From electronics to vehicles
                        and properties, find everything you need in one place.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-light btn-lg text-success fw-bold">Start Selling</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-content text-center">
                        <img src="https://agii.ng/frontend/assets/images/demos/demo-14/slider/slide-1.png" alt="Banner"
                            class="img-fluid rounded-4">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Slider -->
    <section class="py-5 bg-white d-none">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="position-relative">
                <div class="products-slider swiper">
                    <div class="swiper-wrapper">
                        @forelse($featuredProducts as $product)
                            <div class="swiper-slide">
                                <div class="product-item position-relative">
                                    @if ($product->condition == 'New')
                                        <span class="badge bg-success position-absolute m-3">New</span>
                                    @elseif($product->negotiable)
                                        <span class="badge bg-warning position-absolute m-3">Negotiable</span>
                                    @endif

                                    <figure>
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                                alt="{{ $product->title }}" class="tab-image">
                                        </a>
                                    </figure>

                                    <h3>
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="text-decoration-none text-dark">
                                            {{ Str::limit($product->title, 50) }}
                                        </a>
                                    </h3>

                                    <span class="qty">
                                        @if (isset($product->specifications['storage']))
                                            {{ $product->specifications['storage'] }} •
                                        @endif
                                        {{ $product->condition }} Condition
                                    </span>

                                    <div class="rating-block">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="rating-text">{{ $product->rating }}</span>
                                        <span class="text-muted">({{ $product->review_count }})</span>
                                    </div>

                                    <span class="price">₦{{ number_format($product->price) }}</span>

                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <span class="location text-muted small">
                                            <i class="bi bi-geo-alt"></i> {{ $product->location }}
                                        </span>
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-primary btn-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="text-center py-5">
                                    <i class="bi bi-box display-1 text-muted"></i>
                                    <h4 class="mt-3 text-muted">No featured products</h4>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Featured Stores Slider -->
    <section class="py-5 bg-light">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Featured Stores</h2>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-primary">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="position-relative">
                <div class="stores-slider swiper">
                    <div class="swiper-wrapper">
                        @forelse($featuredStores as $store)
                            <div class="swiper-slide">
                                <div class="store-card card h-100 shadow-sm border-0">
                                    @if ($store->banner)
                                        <img src="{{ Storage::url($store->banner) }}" class="card-img-top store-banner"
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
                                            <small class="text-muted">
                                                <i class="bi bi-box"></i>
                                                {{ $store->products_count ?? $store->products->count() }} products
                                            </small>
                                            @if ($store->rating > 0)
                                                <small class="text-warning ms-2">
                                                    <i class="bi bi-star-fill"></i>
                                                    {{ number_format($store->rating, 1) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent border-0 pb-3">
                                        <a href="{{ route('stores.show', $store->slug) }}"
                                            class="btn btn-outline-primary btn-sm w-100">
                                            Visit Store
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="text-center py-5">
                                    <i class="bi bi-shop display-1 text-muted"></i>
                                    <h4 class="mt-3 text-muted">No stores available</h4>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Services Slider -->
    @if ($featuredServices->count() > 0)
        <section class="py-5 bg-white">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Popular Services</h2>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-primary">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="position-relative">
                    <div class="services-slider swiper">
                        <div class="swiper-wrapper">
                            @foreach ($featuredServices as $service)
                                <div class="swiper-slide">
                                    <div class="service-card card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="service-icon mb-3">
                                                @php
                                                    $serviceIcons = [
                                                        'electronics' => 'phone',
                                                        'fashion' => 'bag',
                                                        'home' => 'house',
                                                        'vehicles' => 'car-front',
                                                        'properties' => 'building',
                                                        'services' => 'tools',
                                                        'repair' => 'tools',
                                                        'beauty' => 'scissors',
                                                        'education' => 'book',
                                                        'health' => 'heart-pulse',
                                                        'event' => 'calendar-event',
                                                        'other' => 'tools',
                                                    ];
                                                    $icon = $serviceIcons[$service->category->slug] ?? 'tools';
                                                @endphp
                                                <i class="bi bi-{{ $icon }} fs-1 text-primary"></i>
                                            </div>
                                            <h5 class="card-title">{{ Str::limit($service->title, 40) }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($service->description, 80) }}
                                            </p>
                                            <div class="service-meta mb-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> Flexible
                                                </small>
                                                @if ($service->rating > 0)
                                                    <small class="text-warning ms-2">
                                                        <i class="bi bi-star-fill"></i>
                                                        {{ number_format($service->rating, 1) }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="price-block">
                                                <span
                                                    class="price fw-bold text-primary">₦{{ number_format($service->price) }}</span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0">
                                            <a href="{{ route('product.show', $service->slug) }}"
                                                class="btn btn-primary btn-sm w-100">
                                                Book Service
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>
    @endif

    <!-- All Products Section -->
    <section class="py-5 bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bootstrap-tabs product-tabs">
                        <div class="tabs-header d-flex flex-wrap justify-content-between border-bottom my-5">
                            <h3>
                                @if ($category)
                                    {{ $categories->where('slug', $category)->first()->name ?? 'Products' }}
                                @else
                                    All Products
                                @endif
                            </h3>
                            <div class="d-flex align-items-center gap-3">
                                <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest
                                    </option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                        Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                        Price: High to Low</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated
                                    </option>
                                    <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>
                                        Featured</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div
                            class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                            @forelse($products as $product)
                                <div class="col">
                                    <div class="product-item position-relative">
                                        @if ($product->condition == 'New')
                                            <span class="badge bg-success position-absolute m-3">New</span>
                                        @elseif($product->negotiable)
                                            <span class="badge bg-warning position-absolute m-3">Negotiable</span>
                                        @endif

                                        <figure>
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                                    alt="{{ $product->title }}" class="tab-image">
                                            </a>
                                        </figure>

                                        <h3>
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $product->title }}
                                            </a>
                                        </h3>

                                        <span class="qty">
                                            @if (isset($product->specifications['storage']))
                                                {{ $product->specifications['storage'] }} •
                                            @endif
                                            {{ $product->condition }} Condition
                                        </span>

                                        <div class="rating-block">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <span class="rating-text">{{ $product->rating }}</span>
                                            <span class="text-muted">({{ $product->review_count }})</span>
                                        </div>

                                        <span class="price">₦{{ number_format($product->price) }}</span>

                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="location text-muted small">
                                                <i class="bi bi-geo-alt"></i> {{ $product->location }}
                                            </span>
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="btn btn-primary btn-sm">
                                                View Product
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-search display-1 text-muted"></i>
                                    <h4 class="mt-3 text-muted">No products found</h4>
                                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">Browse All Products</a>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="row mt-5">
                                <div class="col-md-12">
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
        </div>
    </section>
@endsection

@push('styles')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        :root {
            --primary-color: #8fc74a;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
            --accent-color: #8fc74a;
            --success-color: #8fc74a;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --dark-color: #2c3e50;
            --light-dark-color: #7f8c8d;
            --light-grey-color: #f8f9fa;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }


        .section-title {
            position: relative;
            padding-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
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

        .store-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .store-banner-placeholder {
            border-radius: 12px 12px 0 0;
        }

        .store-logo-img {
            border: 4px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .store-name {
            font-size: 1.1rem;
        }

        .store-description {
            line-height: 1.4;
            min-height: 40px;
        }

        @media (max-width: 768px) {
            .store-card .card-body {
                padding: 1rem;
            }

            .store-logo-img,
            .store-logo-placeholder {
                width: 60px !important;
                height: 60px !important;
                margin-top: -40px !important;
            }
        }


        .product-item {
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-item figure {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-item figure img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-item:hover figure img {
            transform: scale(1.05);
        }

        .rating-block {
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 8px 0;
        }

        .rating-text {
            font-size: 14px;
            font-weight: 600;
            color: #222222;
        }

        .product-item h3 {
            min-height: 48px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .product-item .qty {
            min-height: 20px;
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .product-item .price {
            margin-bottom: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #2e7d32;
        }

        .location {
            font-size: 12px;
            color: #888;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .btn-wishlist {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .btn-wishlist:hover {
            background: white;
            transform: scale(1.1);
        }

        .category-item {
            text-align: center;
            padding: 20px;
            border-radius: 12px;
            background: white;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .category-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: inherit;
            text-decoration: none;
        }

        .category-item img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .category-title {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 500;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            color: #90c74b;
            border-bottom: 2px solid #90c74b;
            background: transparent;
        }

        .pagination .page-link {
            color: #90c74b;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #90c74b;
            border-color: #90c74b;
            color: white;
        }

        /* Swiper slider styles */
        .swiper {
            width: 100%;
            height: 100%;
            padding: 20px 10px;
        }

        .swiper-slide {
            text-align: center;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-color);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Service card styles */
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            color: var(--primary-color);
        }

        .price-block {
            margin: 15px 0;
        }

        /* Section titles */
        .section-title {
            position: relative;
            padding-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 0;
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
    </style>
@endpush

@push('scripts')
    <!-- Include Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Initialize Swiper sliders
        document.addEventListener('DOMContentLoaded', function() {
            // Products Slider
            const productsSwiper = new Swiper('.products-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.products-slider ~ .swiper-button-next',
                    prevEl: '.products-slider ~ .swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    },
                    1200: {
                        slidesPerView: 5
                    }
                }
            });

            // Stores Slider
            const storesSwiper = new Swiper('.stores-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.stores-slider ~ .swiper-button-next',
                    prevEl: '.stores-slider ~ .swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    }
                }
            });

            // Services Slider
            const servicesSwiper = new Swiper('.services-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.services-slider ~ .swiper-button-next',
                    prevEl: '.services-slider ~ .swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    }
                }
            });

            // Your existing functionality
            document.getElementById('sortSelect')?.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.value);
                window.location.href = url.toString();
            });
        });
    </script>
@endpush

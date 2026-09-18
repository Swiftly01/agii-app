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

        /* Swiper slider container fixes */
        .swiper {
            width: 100%;
            height: 100%;
            padding: 10px 5px 30px;
        }

        .swiper-slide {
            height: auto;
            display: flex;
        }

        .swiper-slide>div {
            width: 100%;
        }

        /* Navigation buttons */
        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-color);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            top: 50%;
            transform: translateY(-50%);
            margin-top: 0;
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

        /* Store card styles */
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

        /* Product item styles */
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
            z-index: 1;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .swiper-button-next,
            .swiper-button-prev {
                display: none;
            }

            .store-card .card-body {
                padding: 1rem;
            }

            .store-logo-img,
            .store-logo-placeholder {
                width: 60px !important;
                height: 60px !important;
                margin-top: -40px !important;
            }

            .product-item figure {
                height: 150px;
            }
        }

        @media (max-width: 576px) {
            .swiper {
                padding: 5px 0 25px;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Include Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Featured Products Slider
            const featuredProductsSwiper = new Swiper('.featured-products-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.featured-products-next',
                    prevEl: '.featured-products-prev',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    992: {
                        slidesPerView: 4
                    },
                    1200: {
                        slidesPerView: 5
                    }
                }
            });

            // Initialize Featured Stores Slider
            const featuredStoresSwiper = new Swiper('.featured-stores-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.featured-stores-next',
                    prevEl: '.featured-stores-prev',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    992: {
                        slidesPerView: 4
                    }
                }
            });

            // Initialize Featured Services Slider
            @if ($featuredServices->count() > 0)
                const featuredServicesSwiper = new Swiper('.featured-services-slider', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.featured-services-next',
                        prevEl: '.featured-services-prev',
                    },
                    breakpoints: {
                        576: {
                            slidesPerView: 2
                        },
                        768: {
                            slidesPerView: 3
                        },
                        992: {
                            slidesPerView: 4
                        }
                    }
                });
            @endif

            // Sort functionality
            document.getElementById('sortSelect')?.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.value);
                window.location.href = url.toString();
            });
        });
    </script>
@endpush

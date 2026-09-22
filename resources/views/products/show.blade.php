@extends('layout.layout')
@section('title', $product->title . ' - Product Details')
@section('content')

    <div class="container-fluid py-5">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-gallery">
                    <!-- Main Image Swiper -->
                    <div class="swiper main-image-slider rounded-3">
                        <div class="swiper-wrapper">
                            @foreach ($product->images ?? ['https://via.placeholder.com/600x400'] as $image)
                                <div class="swiper-slide">
                                    <img src="{{ url($image) }}" alt="{{ $product->title }}" class="img-fluid w-100">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <!-- Thumbnail Swiper -->
                    <div class="swiper swiper-thumbs mt-3">
                        <div class="swiper-wrapper">
                            @foreach ($product->images ?? ['https://via.placeholder.com/600x400'] as $image)
                                <div class="swiper-slide">
                                    <img src="{{ url($image) }}" class="img-fluid rounded-2 cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('products.index', $product->category->slug) }}">{{ $product->category->name }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $product->title }}</li>
                    </ol>
                </nav>
                <button class="btn btn-bookmark" id="bookmarkBtn"
                    onclick="window.location='{{ route('vendor.dashboard') }}'">
                    <i class="far fa-bookmark"></i> Back to dashboard
                </button>
                <h1 class="display-5 fw-bold">{{ $product->title }}</h1>

                <!-- Product Stats -->
                <div class="product-stats mb-4">
                    <div class="stat-item">
                        <div class="stat-value" id="views-count">{{ $product->views ?? 0 }}</div>
                        <div class="stat-label">Views</div>
                    </div>
                    <div class="stat-item d-none">
                        <div class="stat-value" id="inquiries-count">{{ $product->inquiries_count ?? 0 }}</div>
                        <div class="stat-label">Inquiries</div>
                    </div>
                    <div class="stat-item d-none d-none">
                        <div class="stat-value">{{ $product->quantity }}</div>
                        <div class="stat-label">In Stock</div>
                    </div>
                </div>

                <div class="rating-block mb-3">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span class="fs-5 fw-bold">{{ $product->rating }}</span>
                    <span class="text-muted">({{ $product->review_count }} reviews)</span>
                </div>

                <div class="price-section mb-4">
                    <h2 class="text-success">₦{{ number_format($product->price) }}</h2>
                    @if ($product->old_price)
                        <del class="text-muted h5">₦{{ number_format($product->old_price) }}</del>
                        @php
                            $discount = (($product->old_price - $product->price) / $product->old_price) * 100;
                        @endphp
                        <span class="badge bg-danger ms-2">-{{ number_format($discount, 0) }}%</span>
                    @endif
                </div>

                <!-- Product Meta -->
                <div class="product-meta mb-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <strong>Condition:</strong>
                            <span class="text-success">{{ ucfirst(str_replace('_', ' ', $product->condition)) }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Quantity:</strong>
                            <span>{{ $product->quantity }} available</span>
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Location:</strong>
                            <span>{{ $product->location }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                        @if ($product->negotiable)
                            <div class="col-12 mb-3">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-handshake me-1"></i> Price Negotiable
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons d-grid gap-2 d-md-flex mb-4">
                    <button class="btn btn-whatsapp flex-fill contact-seller" data-vendor-id="{{ $product->user_id }}"
                        data-product-id="{{ $product->id }}" data-contact-method="whatsapp">
                        <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                    </button>
                    <button class="btn btn-call flex-fill contact-seller" data-vendor-id="{{ $product->user_id }}"
                        data-product-id="{{ $product->id }}" data-contact-method="phone">
                        <i class="fas fa-phone me-2"></i>Call Vendor
                    </button>
                </div>

                <!-- Seller Information -->
                <div class="seller-info">
                    <h5 class="fw-bold mb-3">Seller Information</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="seller-avatar me-3">
                            {{ substr($product->user->first_name, 0, 1) }}{{ substr($product->user->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-1">
                                @if ($product->user->business_name)
                                    {{ $product->user->business_name }}
                                @else
                                    {{ $product->user->first_name }} {{ $product->user->last_name }}
                                @endif
                            </h6>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                {{ $product->user->state ?? 'Nigeria' }}
                            </p>
                        </div>
                    </div>

                    <!-- Seller Stats -->
                    <div class="seller-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fw-bold">
                                    @php
                                        $vendorProducts = App\Models\Product::where(
                                            'user_id',
                                            $product->user_id,
                                        )->count();
                                    @endphp
                                    {{ $vendorProducts }}
                                </div>
                                <small class="text-muted">Products</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold">
                                    @php
                                        $vendorViews = App\Models\Product::where('user_id', $product->user_id)->sum(
                                            'views',
                                        );
                                    @endphp
                                    {{ number_format($vendorViews) }}
                                </div>
                                <small class="text-muted">Total Views</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold">
                                    {{ $product->user->created_at->format('M Y') }}
                                </div>
                                <small class="text-muted">Member Since</small>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    @if ($product->user->facebook_url || $product->user->instagram_url || $product->user->twitter_url)
                        <div class="seller-social-links mb-3">
                            <p class="small text-muted mb-2">Follow Seller:</p>
                            <div class="d-flex gap-2">
                                @if ($product->user->facebook_url)
                                    <a href="{{ $product->user->facebook_url }}" target="_blank"
                                        class="social-link facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if ($product->user->instagram_url)
                                    <a href="{{ $product->user->instagram_url }}" target="_blank"
                                        class="social-link instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if ($product->user->twitter_url)
                                    <a href="{{ $product->user->twitter_url }}" target="_blank"
                                        class="social-link twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Specifications -->
                @if ($product->specifications && count($product->specifications) > 0)
                    <div class="specifications mt-4">
                        <h6 class="fw-bold mb-3">Key Specifications</h6>
                        <div class="row">
                            @foreach ($product->specifications as $key => $value)
                                <div class="col-6 mb-2">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                    <span>{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Tabs Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="product-tabs">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                data-bs-target="#description" type="button" role="tab">
                                Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                                type="button" role="tab">
                                Specifications
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                type="button" role="tab">
                                Safty Tips
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <h4 class="fw-bold mb-4">Product Description</h4>
                            <div class="description-content">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            @if ($product->specifications && count($product->specifications) > 0)
                                <div class="mt-5">
                                    <h5 class="fw-bold mb-3">Key Features</h5>
                                    <ul class="feature-list">
                                        @foreach ($product->specifications as $key => $value)
                                            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" id="specs" role="tabpanel">
                            <h4 class="fw-bold mb-4">Technical Specifications</h4>
                            @if ($product->specifications && count($product->specifications) > 0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                                @foreach (array_slice($product->specifications, 0, ceil(count($product->specifications) / 2)) as $key => $value)
                                                    <tr>
                                                        <td class="fw-bold" style="width: 40%;">
                                                            {{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                                @foreach (array_slice($product->specifications, ceil(count($product->specifications) / 2)) as $key => $value)
                                                    <tr>
                                                        <td class="fw-bold" style="width: 40%;">
                                                            {{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No specifications provided for this product.</p>
                            @endif
                        </div>

                        <!-- Shipping Tab -->
                        <div class="tab-pane fade" id="shipping" role="tabpanel">
                            <h4 class="fw-bold mb-4">Shipping & Return Policy</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Shipping Information</h5>
                                    <ul class="feature-list">
                                        <li>Meet with the seller at a safe public place</li>
                                        <li>Check all the docs and only pay if you're satisfied</li>
                                        <li>Avoid sending any prepayments</li>
                                     
                                        <li>Inspect what you're going to buy to make sure it's what you need</li>
                                    </ul>
                                </div>
                                
                                



                                <div class="col-md-6 d-none">
                                    <h5 class="fw-bold mb-3">Return Policy</h5>
                                    <ul class="feature-list">
                                        <li>3-day return policy for defective products</li>
                                        <li>Buyer responsible for return shipping costs</li>
                                        <li>Product must be in original condition</li>
                                        <li>Refund processed within 3-5 business days</li>
                                        <li>No returns for change of mind</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if ($relatedProducts->count() > 0)
            <div class="row related-products mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">You May Also Like</h3>
                    <div class="row">
                        @foreach ($relatedProducts as $related)
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card product-card h-100 border-0 shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ url($related->images[0]) ?? 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                            class="card-img-top" alt="{{ $related->title }}"
                                            style="height: 200px; object-fit: cover;">
                                        @if ($related->old_price && $related->old_price > $related->price)
                                            <span class="badge bg-success product-badge">
                                                -{{ number_format((($related->old_price - $related->price) / $related->old_price) * 100, 0) }}%
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($related->title, 50) }}</h5>
                                        <p class="card-text text-muted small">
                                            {{ ucfirst(str_replace('_', ' ', $related->condition)) }} •
                                            {{ $related->location }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span
                                                class="fw-bold text-primary">₦{{ number_format($related->price) }}</span>
                                            @if ($related->old_price)
                                                <del
                                                    class="text-muted small">₦{{ number_format($related->old_price) }}</del>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <a href="{{ route('product.show', $related->slug) }}"
                                            class="btn btn-primary btn-sm w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #90c74b;
            --accent-color: #90c74b;
            --light-primary-color: rgba(144, 199, 75, 0.1);
            --card-bg: #ffffff;
            --border-color: #e9ecef;
            --light-bg: #f8f9fa;
        }

        /* Product Gallery Styles */
        .product-gallery img {
            border-radius: 12px;
        }

        .swiper {
            width: 100%;
            height: 500px;
        }

        .swiper-slide {
            text-align: center;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .swiper-thumbs {
            height: 100px;
            box-sizing: border-box;
            padding: 10px 0;
        }

        .swiper-thumbs .swiper-slide {
            opacity: 0.6;
            transition: opacity 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 10px;
            overflow: hidden;
        }

        .swiper-thumbs .swiper-slide-thumb-active {
            opacity: 1;
            border-color: var(--primary-color);
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-color);
            background: rgba(255, 255, 255, 0.9);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px;
            font-weight: bold;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Product Stats */
        .product-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 20px;
            background: var(--light-bg);
            border-radius: 12px;
            margin: 20px 0;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Price Section */
        .price-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        /* Product Meta */
        .product-meta {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        /* Action Buttons */
        .action-buttons .btn {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-whatsapp {
            background: #25D366;
            color: white;
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }

        .btn-call {
            background: #007bff;
            color: white;
        }

        .btn-call:hover {
            background: #0056b3;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        }

        /* Seller Information */
        .seller-info {
            background: var(--light-primary-color);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .seller-avatar {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .seller-stats {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
        }

        .seller-social-links .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            color: white;
        }

        .social-link.facebook {
            background: #3b5998;
        }

        .social-link.instagram {
            background: #e4405f;
        }

        .social-link.twitter {
            background: #1da1f2;
        }

        .social-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Product Tabs */
        .product-tabs {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: 1px solid var(--border-color);
            padding: 0 20px;
            background: var(--light-bg);
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 15px 25px;
            font-weight: 600;
            color: #6c757d;
            background: transparent;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border-bottom-color: var(--primary-color);
        }

        .tab-content {
            padding: 30px;
        }

        .description-content {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #495057;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Tables */
        .table-bordered {
            border-radius: 8px;
            overflow: hidden;
        }

        .table-bordered td {
            padding: 12px 15px;
            border-color: var(--border-color);
        }

        /* Related Products */
        .product-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 30px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #7ab436;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .product-stats {
                grid-template-columns: 1fr;
                gap: 10px;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px !important;
            }

            .action-buttons .btn {
                width: 100%;
            }

            .price-section h2 {
                font-size: 2rem;
            }

            .swiper {
                height: 300px;
            }

            .swiper-thumbs {
                height: 80px;
            }

            .tab-content {
                padding: 20px 15px;
            }

            .nav-tabs .nav-link {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .product-meta .row .col-6 {
                width: 100%;
                margin-bottom: 10px;
            }

            .specifications .row .col-6 {
                width: 100%;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Include Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Track product view
            trackProductView();

            // Initialize Swiper
            const thumbsSwiper = new Swiper('.swiper-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });

            const mainSwiper = new Swiper('.main-image-slider', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: thumbsSwiper,
                },
            });

            // Contact Seller functionality
            document.querySelectorAll('.contact-seller').forEach(button => {
                button.addEventListener('click', function() {
                    const vendorId = this.getAttribute('data-vendor-id');
                    const productId = this.getAttribute('data-product-id');
                    const contactMethod = this.getAttribute('data-contact-method');

                    // Track the contact first
                    trackContact(vendorId, productId, contactMethod)
                        .then(() => {
                            // Update inquiries count
                            updateInquiriesCount();
                            // After tracking, redirect to the appropriate contact method
                            redirectToContact(vendorId, contactMethod);
                        })
                        .catch(error => {
                            console.error('Error tracking contact:', error);
                            // Still redirect even if tracking fails
                            redirectToContact(vendorId, contactMethod);
                        });
                });
            });

            function trackProductView() {
                const productId = {{ $product->id }};

                fetch('{{ route('products.track-view') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update views count
                            document.getElementById('views-count').textContent = data.views_count;
                        }
                    })
                    .catch(error => {
                        console.error('Error tracking view:', error);
                    });
            }

            function trackContact(vendorId, productId, contactMethod) {
                return fetch('{{ route('contacts.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            vendor_id: vendorId,
                            product_id: productId,
                            contact_method: contactMethod,
                            notes: 'Contact initiated from product page'
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Failed to track contact');
                        }
                        console.log('Contact tracked successfully');
                        return data;
                    });
            }

            function updateInquiriesCount() {
                const productId = {{ $product->id }};

                fetch(`/products/${productId}/inquiries-count`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('inquiries-count').textContent = data.inquiries_count;
                        }
                    })
                    .catch(error => {
                        console.error('Error updating inquiries count:', error);
                    });
            }

            function redirectToContact(vendorId, contactMethod) {
                // In a real implementation, you would fetch vendor contact info from your backend
                const vendorData = {
                    whatsapp_number: '{{ $product->user->whatsapp_number }}',
                    phone: '{{ $product->user->phone }}'
                };

                if (contactMethod === 'whatsapp' && vendorData.whatsapp_number) {
                    const message = `Hello! I'm interested in your product: {{ $product->title }}`;
                    const whatsappUrl =
                        `https://wa.me/${vendorData.whatsapp_number}?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                } else if (contactMethod === 'phone' && vendorData.phone) {
                    window.location.href = `tel:${vendorData.phone}`;
                } else {
                    alert('Contact information not available for this seller.');
                }
            }
        });
    </script>
@endpush

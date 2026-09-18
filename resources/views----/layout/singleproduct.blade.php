@extends('layout.layout')
@section('title', $product->title . ' - Product Details')
@section('content')

    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index', $product->category->slug) }}"
                                class="text-decoration-none">{{ $product->category->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $product->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Product Images Section -->
            <div class="col-lg-6">
                <div class="product-hero">
                    <div class="product-image-slider p-4">
                        <!-- Main Image Slider -->
                        <div class="swiper main-image-slider mb-3">
                            <div class="swiper-wrapper">
                                @foreach ($product->images ?? ['https://via.placeholder.com/600x400'] as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ $image }}" alt="{{ $product->title }}"
                                            class="img-fluid rounded-3">
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>

                        <!-- Thumbnail Slider -->
                        <div class="swiper swiper-thumbs">
                            <div class="swiper-wrapper">
                                @foreach ($product->images ?? ['https://via.placeholder.com/600x400'] as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ $image }}" class="img-fluid rounded-2" alt="Thumbnail">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="view-count">
                            <i class="fas fa-eye me-1"></i>
                            <span id="views-count">{{ $product->views ?? 0 }}</span> views
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="col-lg-6">
                <div class="product-info">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-success product-badge">Available</span>
                            <h1 class="h2 fw-bold mb-2">{{ $product->title }}</h1>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating-stars me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product->rating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product->rating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-muted">({{ $product->rating }} • {{ $product->review_count }}
                                    reviews)</span>
                            </div>
                        </div>
                        <button class="btn btn-bookmark" id="bookmarkBtn">
                            <i class="far fa-bookmark"></i> Save
                        </button>
                    </div>

                    <div class="product-price mb-4">₦{{ number_format($product->price) }}</div>
                    @if ($product->old_price)
                        <del class="text-muted h5">₦{{ number_format($product->old_price) }}</del>
                    @endif

                    <!-- Product Stats -->
                    <div class="product-stats mb-4">
                        <div class="stat-item">
                            <div class="stat-value" id="views-count-stat">{{ $product->views ?? 0 }}</div>
                            <div class="stat-label">Views</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">28</div>
                            <div class="stat-label">Watchers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="inquiries-count">{{ $product->inquiries_count ?? 0 }}</div>
                            <div class="stat-label">Inquiries</div>
                        </div>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <h5 class="fw-bold mb-3">Product Details</h5>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <strong>Condition:</strong>
                                <span class="text-success">{{ ucfirst($product->condition) }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <strong>Quantity:</strong>
                                <span>{{ $product->quantity ?? 1 }} available</span>
                            </div>
                            @if ($product->specifications)
                                @foreach (array_slice($product->specifications, 0, 4) as $key => $value)
                                    <div class="col-6 mb-3">
                                        <strong>{{ ucfirst($key) }}:</strong>
                                        <span>{{ $value }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-3">
                            <span class="location-badge">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ $product->location }}
                            </span>
                            <span class="location-badge ms-2">
                                <i class="fas fa-clock text-warning"></i>
                                Posted {{ $product->created_at->diffForHumans() }}
                            </span>
                            @if ($product->negotiable)
                                <span class="location-badge ms-2">
                                    <i class="fas fa-handshake text-success"></i>
                                    Price Negotiable
                                </span>
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
                                <div class="d-flex align-items-center">
                                    <div class="rating-stars me-2">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <small class="text-muted">(4.0 • 45 reviews)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Stats -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="fw-bold">98%</div>
                                <small class="text-muted">Response Rate</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold">1 hr</div>
                                <small class="text-muted">Avg. Response</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold">2 yrs</div>
                                <small class="text-muted">On Platform</small>
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
                </div>
            </div>
        </div>

        <!-- Product Tabs Section -->
        <div class="row">
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
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                type="button" role="tab">
                                Reviews ({{ $product->review_count }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                type="button" role="tab">
                                Shipping & Returns
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <h4 class="fw-bold mb-4">Product Description</h4>
                            <p class="mb-4">{{ $product->description }}</p>

                            @if ($product->specifications && count($product->specifications) > 0)
                                <h5 class="fw-bold mb-3">Key Features:</h5>
                                <ul class="feature-list">
                                    @foreach ($product->specifications as $key => $value)
                                        <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" id="specs" role="tabpanel">
                            <h4 class="fw-bold mb-4">Technical Specifications</h4>
                            @if ($product->specifications && count($product->specifications) > 0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            @foreach (array_slice($product->specifications, 0, ceil(count($product->specifications) / 2)) as $key => $value)
                                                <tr>
                                                    <td><strong>{{ ucfirst($key) }}</strong></td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            @foreach (array_slice($product->specifications, ceil(count($product->specifications) / 2)) as $key => $value)
                                                <tr>
                                                    <td><strong>{{ ucfirst($key) }}</strong></td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No specifications provided.</p>
                            @endif
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <h4 class="fw-bold mb-4">Customer Reviews</h4>
                            <!-- Reviews content would go here -->
                            <p class="text-muted">Reviews functionality coming soon.</p>
                        </div>

                        <!-- Shipping Tab -->
                        <div class="tab-pane fade" id="shipping" role="tabpanel">
                            <h4 class="fw-bold mb-4">Shipping & Return Policy</h4>
                            <p class="text-muted">Shipping and return policies are set by individual sellers. Please
                                contact the seller for specific details.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if ($relatedProducts->count() > 0)
            <div class="row related-products">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">You May Also Like</h3>
                    <div class="row">
                        @foreach ($relatedProducts as $related)
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card product-card h-100 border-0 shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ $related->images[0] ?? 'https://via.placeholder.com/300x200' }}"
                                            class="card-img-top" alt="{{ $related->title }}">
                                        @if ($related->old_price && $related->old_price > $related->price)
                                            <span class="badge bg-success product-badge">
                                                -{{ number_format((($related->old_price - $related->price) / $related->old_price) * 100, 0) }}%
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($related->title, 50) }}</h5>
                                        <p class="card-text text-muted small">{{ $related->condition }} •
                                            {{ $related->location }}</p>
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
    <style>
        :root {
            --primary-color: #90c74b;
            --dark-color: #222222;
            --light-bg: #f8f9fa;
        }

        body {
            background: var(--light-bg);
            font-family: 'Open Sans', sans-serif;
        }

        .product-hero {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin: 20px 0;
            overflow: hidden;
        }

        .product-image-slider {
            position: relative;
            background: var(--light-bg);
            border-radius: 15px;
            overflow: hidden;
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

        .product-info {
            padding: 30px;
        }

        .product-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-meta {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .action-buttons .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-whatsapp {
            background: #25D366;
            border-color: #25D366;
            color: white;
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            border-color: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }

        .btn-call {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-call:hover {
            background: #7ab436;
            border-color: #7ab436;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(144, 199, 75, 0.3);
        }

        .btn-bookmark {
            border: 2px solid var(--dark-color);
            color: var(--dark-color);
            border-radius: 10px;
            padding: 8px 16px;
        }

        .btn-bookmark.active {
            background: var(--dark-color);
            color: white;
        }

        .seller-info {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .seller-avatar {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .rating-stars {
            color: #ffc107;
        }

        .product-tabs {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin: 30px 0;
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            padding: 0 25px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 15px 25px;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            background: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            background: transparent;
        }

        .tab-content {
            padding: 30px;
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

        .related-products {
            margin: 50px 0;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 2;
            font-size: 0.8rem;
            padding: 5px 10px;
        }

        .view-count {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            position: absolute;
            bottom: 15px;
            left: 15px;
            z-index: 2;
        }

        .location-badge {
            background: var(--light-bg);
            color: var(--dark-color);
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .product-stats {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 10px;
            flex: 1;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        .seller-social-links .social-link {
            width: 40px;
            height: 40px;
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
            background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
        }

        .social-link.twitter {
            background: #1da1f2;
        }

        .social-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush

@push('scripts')
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

            // Bookmark functionality
            document.getElementById('bookmarkBtn').addEventListener('click', function() {
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                } else {
                    this.innerHTML = '<i class="far fa-bookmark"></i> Save';
                }
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
                            document.getElementById('views-count-stat').textContent = data.views_count;
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

            // Add loading states for buttons
            document.querySelectorAll('.contact-seller').forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Connecting...';
                    this.disabled = true;

                    // Reset button after 3 seconds if something goes wrong
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 3000);
                });
            });
        });
    </script>
@endpush

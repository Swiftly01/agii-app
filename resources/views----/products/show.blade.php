@extends('layout.layout')
@section('title', $product->title . ' - Product Details')
@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Breadcrumb -->
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}"
                                class="text-decoration-none">Products</a></li>
                        @if ($product->category)
                            <li class="breadcrumb-item"><a
                                    href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                                    class="text-decoration-none">{{ $product->category->name }}</a></li>
                            @if ($product->category->parent)
                                <li class="breadcrumb-item"><a
                                        href="{{ route('products.index', ['category' => $product->category->parent->slug]) }}"
                                        class="text-decoration-none">{{ $product->category->parent->name }}</a></li>
                            @endif
                        @endif
                        <li class="breadcrumb-item active">{{ Str::limit($product->title, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Product Images Section -->
            <div class="col-lg-6">
                <div class="product-hero">
                    <div class="product-image-slider p-4">
                        <!-- Main Image -->
                        <div class="main-image-container mb-3">
                            <img src="{{ url($product->images[0]) ?? 'https://via.placeholder.com/600x400?text=No+Image' }}"
                                alt="{{ $product->title }}" class="img-fluid rounded-3 w-100" id="mainProductImage"
                                style="height: 500px; object-fit: cover;">
                        </div>

                        <!-- Thumbnail Images -->
                        @if (count($product->images) > 1)
                            <div class="thumbnail-container">
                                <div class="row g-2">
                                    @foreach ($product->images as $index => $image)
                                        <div class="col-3">
                                            <img src="{{ url($image) }}"
                                                alt="{{ $product->title }} - Image {{ $index + 1 }}"
                                                class="img-fluid rounded-2 thumbnail-image cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                                style="height: 100px; object-fit: cover;" data-image="{{ $image }}"
                                                onclick="changeMainImage(this)">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="view-count mt-3">
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
                            <span class="badge bg-success product-badge">
                                @if ($product->quantity > 0)
                                    Available
                                @else
                                    Out of Stock
                                @endif
                            </span>
                            <h1 class="h2 fw-bold mb-2">{{ $product->title }}</h1>

                            @if ($product->category)
                                <div class="category-badge mb-3">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-tag me-1"></i>
                                        {{ $product->category->name }}
                                        @if ($product->category->parent)
                                            > {{ $product->category->parent->name }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                        <button class="btn btn-bookmark" id="bookmarkBtn">
                            <i class="far fa-bookmark"></i> Save
                        </button>
                    </div>

                    <!-- Price Section -->
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

                    <!-- Product Stats -->
                    <div class="product-stats mb-4">
                        <div class="stat-item">
                            <div class="stat-value" id="views-count-stat">{{ $product->views ?? 0 }}</div>
                            <div class="stat-label">Views</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->inquiries_count ?? 0 }}</div>
                            <div class="stat-label">Inquiries</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->quantity }}</div>
                            <div class="stat-label">In Stock</div>
                        </div>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <h5 class="fw-bold mb-3">Product Details</h5>
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

                        <!-- Specifications -->
                        @if ($product->specifications && count($product->specifications) > 0)
                            <div class="specifications mt-4">
                                <h6 class="fw-bold mb-3">Specifications</h6>
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

                        <div class="mt-3">
                            <span class="location-badge">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $product->location }}
                            </span>
                            <span class="location-badge ms-2">
                                <i class="fas fa-clock text-warning me-1"></i>
                                Posted {{ $product->created_at->diffForHumans() }}
                            </span>
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
                        <div class="row text-center mb-3">
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
                                    @php
                                        $memberSince = $product->user->created_at->diffForHumans();
                                    @endphp
                                    {{ $product->user->created_at->format('M Y') }}
                                </div>
                                <small class="text-muted">Member Since</small>
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
                                Shipping & Returns
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
                                        <li>Meet-up available at designated locations</li>
                                        <li>Delivery within the same city/state</li>
                                        <li>Nationwide shipping available (2-5 business days)</li>
                                        <li>Shipping costs vary by location</li>
                                        <li>Contact seller for exact shipping charges</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
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
        /* Color Variables */
        :root {
            --primary-color: #90c74b;
            --accent-color: #90c74b;
            --light-primary-color: rgba(144, 199, 75, 0.1);
            --card-bg: #ffffff;
            --border-color: #e9ecef;
            --light-bg: #f8f9fa;
        }

        /* Main Product Layout */
        .product-hero {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .product-image-slider {
            position: relative;
            background: var(--light-bg);
            border-radius: 15px;
            overflow: hidden;
        }

        .main-image-container {
            border-radius: 12px;
            overflow: hidden;
            background: var(--light-bg);
        }

        .thumbnail-image {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .thumbnail-image:hover,
        .thumbnail-image.active {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        /* Product Info Styling */
        .product-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .price-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .category-badge .badge {
            font-size: 0.9rem;
            padding: 8px 16px;
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

        /* Product Meta */
        .product-meta {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .location-badge {
            background: rgba(108, 117, 125, 0.1);
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #6c757d;
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

        .btn-bookmark {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-bookmark:hover,
        .btn-bookmark.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
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
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
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

        /* View Count */
        .view-count {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            backdrop-filter: blur(10px);
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

            .seller-info .row .col-4 {
                margin-bottom: 15px;
            }

            .tab-content {
                padding: 20px 15px;
            }

            .nav-tabs .nav-link {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .main-image-container img {
                height: 300px !important;
            }

            .thumbnail-image {
                height: 80px !important;
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

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Utility Classes */
        .cursor-pointer {
            cursor: pointer;
        }

        .text-decoration-none {
            text-decoration: none;
        }

        .border-radius-12 {
            border-radius: 12px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Image gallery functionality
        function changeMainImage(element) {
            const mainImage = document.getElementById('mainProductImage');
            mainImage.src = element.getAttribute('data-image');

            // Update active thumbnail
            document.querySelectorAll('.thumbnail-image').forEach(img => {
                img.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Bookmark functionality
        document.getElementById('bookmarkBtn').addEventListener('click', function() {
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
            } else {
                this.innerHTML = '<i class="far fa-bookmark"></i> Save';
            }
        });

        // Contact seller functionality
        document.querySelectorAll('.contact-seller').forEach(button => {
            button.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                const productId = this.getAttribute('data-product-id');
                const contactMethod = this.getAttribute('data-contact-method');

                // Track contact
                trackContact(vendorId, productId, contactMethod)
                    .then(() => {
                        redirectToContact(vendorId, contactMethod);
                    })
                    .catch(error => {
                        console.error('Error tracking contact:', error);
                        redirectToContact(vendorId, contactMethod);
                    });
            });
        });

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
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to track contact');
                    }
                    return data;
                });
        }

        function redirectToContact(vendorId, contactMethod) {
            // This would typically fetch vendor contact info from your backend
            // For now, using placeholder data
            if (contactMethod === 'whatsapp') {
                const message = `Hello! I'm interested in your product: {{ $product->title }}`;
                const whatsappUrl = `https://wa.me/2348012345678?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            } else if (contactMethod === 'phone') {
                window.location.href = `tel:2348012345678`;
            }
        }

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabTriggers = [].slice.call(document.querySelectorAll('#productTabs button'));
            tabTriggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(event) {
                    event.preventDefault();
                    const target = document.querySelector(this.getAttribute('data-bs-target'));
                    if (target) {
                        // Remove active class from all tabs and panes
                        document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove(
                            'active'));
                        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList
                            .remove('show', 'active'));

                        // Add active class to current tab and pane
                        this.classList.add('active');
                        target.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>
@endpush

@extends('layout.layout')
@section('title', 'My Products - Vendor Dashboard')
@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold">My Products</h1>
                    <a href="{{ route('boost.plans') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-rocket-takeoff-fill me-1"></i>Boost Your Products
                    </a>
                </div>

                @if ($boostUsage['limit'] > 0)
                    <div class="alert alert-light border d-flex align-items-center mb-4">
                        <i class="bi bi-rocket-takeoff-fill text-success me-2"></i>
                        <span>
                            <strong>{{ $boostUsage['used'] }} of {{ $boostUsage['limit'] }}</strong>
                            products boosted
                            @if ($boostUsage['used'] >= $boostUsage['limit'])
                                — unboost a product below to free up a slot.
                            @endif
                        </span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Products Grid -->
                <div class="row">
                    @forelse($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ isset($product->images[0]) ? asset($product->images[0]) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                        class="card-img-top" alt="{{ $product->title }}"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span
                                            class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                    @if ($product->old_price && $product->old_price > $product->price)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                            -{{ number_format((($product->old_price - $product->price) / $product->old_price) * 100, 0) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($product->title, 50) }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                                    </p>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $product->location }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-primary">₦{{ number_format($product->price) }}</span>
                                        @if ($product->old_price)
                                            <del class="text-muted small">₦{{ number_format($product->old_price) }}</del>
                                        @endif
                                    </div>
                                    <div class="product-stats small text-muted mb-3">
                                        <span class="me-3"><i class="bi bi-eye me-1"></i>{{ $product->views }}
                                            views</span>
                                        <span><i class="bi bi-chat me-1"></i>{{ $product->inquiries_count }}
                                            inquiries</span>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-top-0">
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-outline-primary btn-sm flex-fill" target="_blank">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route('products.edit', ['slug' => $product->slug]) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('products.destroy', $product->slug) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="d-flex gap-2">
                                        @if ($product->isBoosted())
                                            <span class="badge bg-success flex-fill py-2">
                                                <i class="bi bi-rocket-takeoff-fill me-1"></i>Boosted until
                                                {{ $product->boost_expires_at->format('M j') }}
                                            </span>
                                            <form action="{{ route('products.unboost', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary btn-sm"
                                                    title="Remove boost">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                            @if (! $product->is_boost_carousel_pick)
                                                <form action="{{ route('products.boost.carousel-pick', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm"
                                                        title="Feature in home page carousel">
                                                        <i class="bi bi-star"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="btn btn-success btn-sm disabled" title="Featured in carousel">
                                                    <i class="bi bi-star-fill"></i>
                                                </span>
                                            @endif
                                        @else
                                            @if ($boostUsage['used'] >= $boostUsage['limit'])
                                                <span class="btn btn-outline-secondary btn-sm w-100 disabled"
                                                    title="You've reached your boost limit ({{ $boostUsage['limit'] }})">
                                                    <i class="bi bi-rocket-takeoff me-1"></i>Boost limit reached
                                                </span>
                                            @else
                                                <form action="{{ route('products.boost', $product->id) }}" method="POST"
                                                    class="flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                                        <i class="bi bi-rocket-takeoff me-1"></i>Boost this product
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h3 class="mt-3">No Products Found</h3>
                                <p class="text-muted">You haven't added any products yet.</p>

                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($products)
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .product-stats {
            font-size: 0.8rem;
        }

        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-danger {
            border-radius: 8px;
        }
    </style>
@endpush

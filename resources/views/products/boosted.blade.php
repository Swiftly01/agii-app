@extends('layout.layout')
@section('title', 'Boosted Products - Agii')

@section('content')

    <div class="bg-white">
        <div class="container py-4">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-rocket-takeoff-fill text-success me-2 fs-3"></i>
                <h1 class="h3 fw-bold mb-0">Boosted Products</h1>
            </div>
            <p class="text-muted">Vendors putting their best listings forward — updated as boosts start and end.</p>
        </div>
    </div>

    <div class="bg-light-2 py-4">
        <div class="container">
            @if ($products->count() > 0)
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="product-card bg-white rounded shadow-sm border">
                                <div class="product-image position-relative">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        <img src="{{ $product->images[0] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&h=200&q=80' }}"
                                            alt="{{ $product->title }}" class="img-fluid w-100"
                                            style="height: 200px; object-fit: cover;">
                                    </a>
                                    <span class="featured-badge position-absolute top-0 start-0 bg-success text-white small px-2 py-1 m-2 rounded">
                                        <i class="bi bi-rocket-takeoff-fill me-1"></i> Boosted
                                    </span>
                                </div>
                                <div class="product-info p-1">
                                    <h6 class="product-title mb-2">
                                        <a href="{{ route('product.show', $product->slug) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($product->title, 30) }}
                                        </a>
                                    </h6>

                                    <div class="product-rating mb-2 d-flex align-items-center">
                                        <div class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->rating))
                                                    <i class="bi bi-star-fill text-lemon small"></i>
                                                @elseif ($i == ceil($product->rating) && $product->rating - floor($product->rating) > 0)
                                                    <i class="bi bi-star-half text-lemon small"></i>
                                                @else
                                                    <i class="bi bi-star text-lemon small"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ms-2 small text-muted">({{ $product->review_count }})</span>
                                    </div>

                                    <div class="price-section">
                                        <div class="current-price fw-bold text-dark fs-5">
                                            ₦{{ number_format($product->price) }}
                                        </div>
                                        @if ($product->old_price && $product->old_price > $product->price)
                                            <div class="original-price text-muted text-decoration-line-through small">
                                                ₦{{ number_format($product->old_price) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-meta d-flex justify-content-between align-items-center">
                                        <div class="location small text-muted">
                                            <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($product->location, 12) }}
                                        </div>
                                        @if ($product->negotiable)
                                            <span class="negotiable-badge bg-success bg-opacity-10 text-success small px-2 py-1 rounded">
                                                Negotiable
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="product-actions p-3 border-top">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-success btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($products->hasPages())
                    <div class="mt-5">
                        <nav aria-label="Boosted products pagination">
                            <ul class="pagination justify-content-center">
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}">&laquo;</a>
                                    </li>
                                @endif

                                @foreach ($products->links()->elements[0] as $page => $url)
                                    @if ($page == $products->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                @if ($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="bi bi-rocket-takeoff display-1 text-muted mb-3"></i>
                    <h4 class="text-muted mb-2">No boosted products right now</h4>
                    <p class="text-muted mb-4">Check back soon, or boost one of your own listings to be featured here.</p>
                    <a href="{{ route('boost.plans') }}" class="btn btn-success">
                        <i class="bi bi-rocket-takeoff-fill me-1"></i> Boost Your Products
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection

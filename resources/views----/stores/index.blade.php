@extends('layout.layout')
@section('title', 'Browse Stores - Agii')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Browse Stores</h1>
                    <div class="d-flex gap-2">
                        <form action="{{ route('stores.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search stores..."
                                value="{{ request('search') }}">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated
                                </option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="products" {{ request('sort') == 'products' ? 'selected' : '' }}>Most Products
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                @if ($stores->count() > 0)
                    <div class="row">
                        @foreach ($stores as $store)
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card store-card h-100">
                                    @if ($store->banner)
                                        <img src="{{ Storage::url($store->banner) }}" class="card-img-top"
                                            alt="{{ $store->store_name }}" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                            style="height: 120px;">
                                            <i class="bi bi-shop fs-1 text-muted"></i>
                                        </div>
                                    @endif

                                    <div class="card-body text-center">
                                        @if ($store->logo)
                                            <img src="{{ Storage::url($store->logo) }}" class="rounded-circle mb-3"
                                                alt="{{ $store->store_name }}"
                                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white; margin-top: -60px;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width: 80px; height: 80px; margin-top: -60px;">
                                                <i class="bi bi-shop fs-4"></i>
                                            </div>
                                        @endif

                                        <h5 class="card-title">{{ $store->store_name }}</h5>

                                        @if ($store->description)
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($store->description, 80) }}
                                            </p>
                                        @endif

                                        <div class="store-stats small text-muted mb-3">
                                            <span><i class="bi bi-box"></i>
                                                {{ $store->products_count ?? $store->products->count() }} products</span>
                                            @if ($store->rating > 0)
                                                <span class="ms-2"><i class="bi bi-star-fill text-warning"></i>
                                                    {{ number_format($store->rating, 1) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-footer bg-transparent">
                                        <a href="{{ route('stores.show', $store->slug) }}"
                                            class="btn btn-primary btn-sm w-100">
                                            Visit Store
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $stores->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-shop display-1 text-muted"></i>
                        <h3 class="mt-3 text-muted">No stores found</h3>
                        <p class="text-muted">Try adjusting your search criteria</p>
                        <a href="{{ route('stores.index') }}" class="btn btn-primary">View All Stores</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

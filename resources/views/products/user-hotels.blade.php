@extends('layout.layout')
@section('title', $user->name . "'s Hotels")

@section('content')
<section class="py-4 py-md-5 bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- User Profile Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-person fs-3"></i>
                                </div>
                            @endif
                            
                            <div>
                                <h1 class="h3 mb-1">{{ $user->name }}'s Hotels</h1>
                                <div class="d-flex flex-wrap align-items-center text-muted">
                                    <span class="me-3">
                                        <i class="bi bi-geo-alt"></i> {{ $user->location ?? 'N/A' }}
                                    </span>
                                    <span class="me-3">
                                        <i class="bi bi-building"></i> {{ $hotels->total() }} Hotels
                                    </span>
                                    @if($user->rating > 0)
                                        <span class="text-warning">
                                            <i class="bi bi-star-fill"></i>
                                            {{ number_format($user->rating, 1) }} Rating
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hotels Grid -->
                <div class="product-grid row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                    @forelse($hotels as $hotel)
                        <div class="col">
                            <div class="product-item position-relative h-100">
                                @if ($hotel->condition == 'New')
                                    <span class="badge bg-success position-absolute m-2 m-md-3">New</span>
                                @endif

                                <figure class="mb-3">
                                    <a href="{{ route('product.show', $hotel->slug) }}">
                                        <img src="{{ $hotel->images[0] ?? 'https://via.placeholder.com/400x300?text=Hotel' }}"
                                            alt="{{ $hotel->title }}" class="tab-image w-100 h-100">
                                    </a>
                                </figure>

                                <h3 class="product-title">
                                    <a href="{{ route('product.show', $hotel->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ Str::limit($hotel->title, 50) }}
                                    </a>
                                </h3>

                                <span class="qty text-muted small d-block mb-2">
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    {{ $hotel->rating ?? 'No ratings' }}
                                    @isset($hotel->specifications['rooms'])
                                        • {{ $hotel->specifications['rooms'] }} Rooms
                                    @endisset
                                </span>

                                <div class="price-block mb-3">
                                    <span class="price fw-bold text-success">₦{{ number_format($hotel->price) }}/night</span>
                                </div>

                                <div class="product-footer mt-auto">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="location text-muted small">
                                            <i class="bi bi-geo-alt"></i> {{ Str::limit($hotel->location, 15) }}
                                        </span>
                                        <a href="{{ route('product.show', $hotel->slug) }}"
                                            class="btn btn-primary btn-sm">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-building display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">No hotels found</h4>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($hotels->hasPages())
                    <div class="mt-4">
                        {{ $hotels->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
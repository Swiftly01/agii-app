{{-- Hero carousel: paid vendor spotlights first, stock banners only when asked for via $houseBanners and nobody has paid --}}
@if ($spotlightProducts->isNotEmpty() || ($houseBanners ?? false))
    <div class="hero-banner">
        @if ($spotlightProducts->isNotEmpty())
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                @if ($spotlightProducts->count() > 1)
                    <div class="carousel-indicators">
                        @foreach ($spotlightProducts as $product)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}" @class(['active' => $loop->first])></button>
                        @endforeach
                    </div>
                @endif

                <div class="carousel-inner">
                    @foreach ($spotlightProducts as $product)
                        <div @class(['carousel-item', 'active' => $loop->first])>
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->image_urls[0] ?? asset(\App\Models\Product::VIDEO_PLACEHOLDER) }}"
                                     class="d-block w-100" alt="{{ $product->title }}" style="height: 400px; object-fit: cover;">
                            </a>

                            <div class="spotlight-caption">
                                <span class="badge bg-warning text-dark mb-1">Featured</span>
                                <h5 class="spotlight-title mb-1">{{ Str::limit($product->title, 60) }}</h5>

                                {{-- Services carry no price, buyers talk to the seller instead --}}
                                <div class="spotlight-price fw-bold mb-2">
                                    {{ $product->isService() ? 'Contact seller for price' : '₦'.number_format($product->price) }}
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-light btn-sm">View</a>
                                    <x-chat-with-seller :product="$product" class="spotlight-chat" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($spotlightProducts->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        @else
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80" 
                             class="d-block w-100" alt="Tech Deals" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80" 
                             class="d-block w-100" alt="Fashion Sale" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80" 
                             class="d-block w-100" alt="Home Appliances" style="height: 400px; object-fit: cover;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @endif
    </div>
@endif

@push('styles')
    <style>
        .spotlight-caption {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 2;
            padding: 2.5rem 3.5rem 1.25rem;
            color: #fff;
            background: linear-gradient(transparent, rgba(0, 0, 0, .78));
        }

        .spotlight-chat .btn {
            padding: .25rem .5rem;
            font-size: .875rem;
        }

        @media (max-width: 767.98px) {
            .spotlight-caption { padding: 1.5rem 2.5rem .75rem; }
            .spotlight-title { font-size: 1rem; }
            .spotlight-price { font-size: .875rem; }
        }
    </style>
@endpush

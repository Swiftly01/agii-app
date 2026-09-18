<div class="store-card">
    <div class="card h-100 shadow-sm">
        @if ($store->banner)
            <img src="{{ Storage::url($store->banner) }}" class="card-img-top store-banner" alt="{{ $store->store_name }}">
        @else
            <div class="store-banner-placeholder bg-light d-flex align-items-center justify-content-center">
                <i class="bi bi-shop display-4 text-muted"></i>
            </div>
        @endif

        <div class="card-body text-center">
            <div class="store-logo mb-3">
                @if ($store->logo)
                    <img src="{{ Storage::url($store->logo) }}" class="store-logo-img" alt="{{ $store->store_name }}">
                @else
                    <div
                        class="store-logo-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto">
                        <i class="bi bi-shop"></i>
                    </div>
                @endif
            </div>

            <h5 class="card-title store-name">{{ $store->store_name }}</h5>

            @if ($store->description)
                <p class="card-text store-description text-muted small">
                    {{ Str::limit($store->description, 80) }}
                </p>
            @endif

            <div class="store-stats">
                <small class="text-muted">
                    <i class="bi bi-box"></i> {{ $store->products_count }} products
                </small>
                @if ($store->rating > 0)
                    <small class="text-warning ms-2">
                        <i class="bi bi-star-fill"></i> {{ number_format($store->rating, 1) }}
                    </small>
                @endif
            </div>
        </div>

        <div class="card-footer bg-transparent">
            <a href="{{ route('stores.show', $store->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                Visit Store
            </a>
        </div>
    </div>
</div>

<style>
    .store-banner {
        height: 120px;
        object-fit: cover;
    }

    .store-banner-placeholder {
        height: 120px;
    }

    .store-logo {
        margin-top: -50px;
    }

    .store-logo-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .store-logo-placeholder {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
    }

    .store-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }

    .store-name {
        color: var(--dark-color);
        font-weight: 600;
    }

    .store-description {
        line-height: 1.4;
    }
</style>

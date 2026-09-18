@extends('layout.layout')
@section('title', $vendor->business_name . ' - Agii')

@section('content')
    <!-- Store Header -->
    <section class="store-header py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if ($vendor->profile_image)
                            <img src="{{ url($vendor->profile_image) }}" class="store-logo rounded-circle me-4"
                                alt="{{ $vendor->business_name }}" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="store-logo-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-4"
                                style="width: 100px; height: 100px;">
                                <i class="bi bi-shop fs-1"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="display-6 fw-bold text-dark mb-2">{{ $vendor->business_name ?? $vendor->name }}</h1>
                            <div class="store-meta">
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <div class="store-rating">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold">{{ $vendor->rating ?? 'N/A' }}</span>
                                        <span class="text-muted ms-1">({{ $vendor->review_count ?? 0 }} reviews)</span>
                                    </div>
                                    <div class="store-products-count">
                                        <i class="bi bi-box text-primary"></i>
                                        <span class="fw-bold">{{ $vendor->products_count ?? 0 }}</span>
                                        <span class="text-muted ms-1">products</span>
                                    </div>
                                    <div class="store-location">
                                        <i class="bi bi-geo-alt text-success"></i>
                                        <span class="text-muted">{{ $vendor->local_government }},
                                            {{ $vendor->state }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($vendor->business_description)
                                <p class="store-description text-muted mt-3 mb-0">
                                    {{ $vendor->business_description }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="store-actions mt-3 mt-md-0">
                        <button class="btn btn-primary contact-vendor" data-vendor-id="{{ $vendor->id }}"
                            data-vendor-name="{{ $vendor->business_name ?? $vendor->first_name . ' ' . $vendor->last_name }}"
                            data-vendor-avatar="{{ $vendor->profile_image ? Storage::url($vendor->profile_image) : '' }}"
                            data-vendor-location="{{ $vendor->local_government }}, {{ $vendor->state }}"
                            data-vendor-phone="{{ $vendor->phone }}">
                            <i class="bi bi-chat me-2"></i>Contact Vendor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Products -->
    <section class="store-products py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="section-title mb-0">All Products ({{ $products->total() }})</h3>
                <div class="d-flex gap-2 align-items-center">
                    <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to
                            Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
            </div>

            @if ($products->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="product-card card h-100 border-0 shadow-sm">
                                <div class="product-badges">
                                    @if ($product->condition == 'New')
                                        <span class="badge bg-success">New</span>
                                    @elseif($product->negotiable)
                                        <span class="badge bg-warning">Negotiable</span>
                                    @endif
                                    @if ($product->featured)
                                        <span class="badge bg-primary">Featured</span>
                                    @endif
                                </div>

                                <div class="product-image">
                                    <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                        alt="{{ $product->title }}" class="card-img-top">
                                    <div class="image-overlay"></div>
                                </div>

                                <div class="card-body">
                                    <h6 class="product-title">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="text-dark text-decoration-none">
                                            {{ Str::limit($product->title, 50) }}
                                        </a>
                                    </h6>

                                    <div class="product-specs mb-2">
                                        <small class="text-muted">
                                            @if (isset($product->specifications['storage']))
                                                {{ $product->specifications['storage'] }} •
                                            @endif
                                            {{ $product->condition }} Condition
                                        </small>
                                    </div>

                                    <div class="product-rating mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="rating-stars">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <span class="rating-value">{{ $product->rating ?? 'N/A' }}</span>
                                            </div>
                                            <span
                                                class="review-count text-muted ms-2">({{ $product->review_count ?? 0 }})</span>
                                        </div>
                                    </div>

                                    <div class="product-price mb-3">
                                        <span
                                            class="price fw-bold text-success">₦{{ number_format($product->price) }}</span>
                                        @if ($product->old_price)
                                            <span class="old-price text-muted text-decoration-line-through ms-2">
                                                ₦{{ number_format($product->old_price) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-location">
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $product->location }}
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="action-buttons d-grid gap-2">
                                        <button class="btn btn-success contact-vendor" data-vendor-id="{{ $vendor->id }}"
                                            data-vendor-name="{{ $vendor->business_name ?? $vendor->first_name . ' ' . $vendor->last_name }}"
                                            data-vendor-avatar="{{ $vendor->profile_image ? Storage::url($vendor->profile_image) : '' }}"
                                            data-vendor-location="{{ $vendor->local_government }}, {{ $vendor->state }}"
                                            data-vendor-phone="{{ $vendor->phone }}" data-product-id="{{ $product->id }}"
                                            data-product-title="{{ $product->title }}"
                                            data-product-price="₦{{ number_format($product->price) }}">
                                            <i class="fab fa-whatsapp me-2"></i>Contact
                                        </button>
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Products pagination">
                                <ul class="pagination justify-content-center">
                                    {{ $products->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No Products Available</h4>
                    <p class="text-muted mb-4">This store hasn't listed any products yet.</p>
                    <button class="btn btn-primary contact-vendor" data-vendor-id="{{ $vendor->id }}"
                        data-vendor-name="{{ $vendor->business_name ?? $vendor->first_name . ' ' . $vendor->last_name }}"
                        data-vendor-avatar="{{ $vendor->profile_image ? Storage::url($vendor->profile_image) : '' }}"
                        data-vendor-location="{{ $vendor->local_government }}, {{ $vendor->state }}"
                        data-vendor-phone="{{ $vendor->phone }}">
                        <i class="bi bi-chat me-2"></i>Contact Vendor
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- Contact Vendor Modal -->
    <div class="modal fade" id="contactVendorModal" tabindex="-1" aria-labelledby="contactVendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactVendorModalLabel">Contact Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="vendor-info mb-4 text-center">
                        <div class="vendor-avatar mb-3">
                            <img id="vendorAvatar" src="" alt="Vendor" class="rounded-circle"
                                style="width: 80px; height: 80px; object-fit: cover; display: none;">
                            <div id="vendorAvatarPlaceholder"
                                class="vendor-avatar-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-person fs-4"></i>
                            </div>
                        </div>
                        <h6 id="vendorName" class="fw-bold mb-1"></h6>
                        <small class="text-muted" id="vendorLocation"></small>
                    </div>

                    <div class="product-info bg-light rounded p-3 mb-4" id="productInfo" style="display: none;">
                        <h6 class="fw-bold mb-2">About the Product</h6>
                        <p class="mb-1" id="productTitle"></p>
                        <p class="fw-bold text-success mb-0" id="productPrice"></p>
                    </div>

                    <div class="contact-options">
                        <div class="row g-3">
                            <!-- WhatsApp Option -->
                            <div class="col-12">
                                <button
                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center py-3 contact-option"
                                    data-method="whatsapp">
                                    <i class="fab fa-whatsapp fa-lg me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-bold">Chat on WhatsApp</div>
                                        <small class="opacity-75">Instant messaging</small>
                                    </div>
                                </button>
                            </div>

                            <!-- Phone Call Option -->
                            <div class="col-12">
                                <button
                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-3 contact-option"
                                    data-method="phone">
                                    <i class="bi bi-telephone fa-lg me-3"></i>
                                    <div class="text-start">
                                        <div class="fw-bold">Call Vendor</div>
                                        <small class="opacity-75">Direct phone call</small>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phone Numbers Modal -->
    <div class="modal fade" id="phoneNumbersModal" tabindex="-1" aria-labelledby="phoneNumbersModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneNumbersModalLabel">Call Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="phone-numbers" id="phoneNumbersList">
                        <!-- Phone numbers will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .store-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .store-logo {
            border: 4px solid white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .store-logo-placeholder {
            border: 4px solid white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .section-title {
            position: relative;
            padding-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1rem;
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

        /* Product card styles */
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .product-badges .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .image-overlay {
            opacity: 1;
        }

        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Contact Vendor Modal Styles */
        .contact-option {
            border: 2px solid transparent;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .contact-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .vendor-avatar-placeholder {
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .phone-number-item a {
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Action buttons in product cards */
        .action-buttons .btn {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .contact-option {
                padding: 12px !important;
            }

            .contact-option .fa-lg {
                font-size: 1.2rem !important;
            }

            .store-header .display-6 {
                font-size: 1.5rem;
            }

            .store-logo,
            .store-logo-placeholder {
                width: 70px !important;
                height: 70px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactVendorModal = new bootstrap.Modal(document.getElementById('contactVendorModal'));
            const phoneNumbersModal = new bootstrap.Modal(document.getElementById('phoneNumbersModal'));

            let currentVendorData = {};
            let currentProductData = {};

            // Contact Vendor Button Click
            document.querySelectorAll('.contact-vendor').forEach(button => {
                button.addEventListener('click', function() {
                    currentVendorData = {
                        id: this.dataset.vendorId,
                        name: this.dataset.vendorName,
                        avatar: this.dataset.vendorAvatar,
                        location: this.dataset.vendorLocation,
                        phone: this.dataset.vendorPhone
                    };

                    currentProductData = {
                        id: this.dataset.productId,
                        title: this.dataset.productTitle,
                        price: this.dataset.productPrice
                    };

                    // Update modal content
                    updateModalContent();

                    // Show modal
                    contactVendorModal.show();
                });
            });

            // Update modal content
            function updateModalContent() {
                // Vendor info
                document.getElementById('vendorName').textContent = currentVendorData.name;
                document.getElementById('vendorLocation').textContent = currentVendorData.location;

                // Vendor avatar
                const vendorAvatar = document.getElementById('vendorAvatar');
                const vendorAvatarPlaceholder = document.getElementById('vendorAvatarPlaceholder');

                if (currentVendorData.avatar) {
                    vendorAvatar.src = currentVendorData.avatar;
                    vendorAvatar.style.display = 'block';
                    vendorAvatarPlaceholder.style.display = 'none';
                } else {
                    vendorAvatar.style.display = 'none';
                    vendorAvatarPlaceholder.style.display = 'flex';
                }

                // Product info (if available)
                const productInfo = document.getElementById('productInfo');
                if (currentProductData.title && currentProductData.title !== '') {
                    document.getElementById('productTitle').textContent = currentProductData.title;
                    document.getElementById('productPrice').textContent = currentProductData.price;
                    productInfo.style.display = 'block';
                } else {
                    productInfo.style.display = 'none';
                }
            }

            // Contact option selection
            document.querySelectorAll('.contact-option').forEach(option => {
                option.addEventListener('click', function() {
                    const method = this.dataset.method;

                    switch (method) {
                        case 'whatsapp':
                            openWhatsApp();
                            break;
                        case 'phone':
                            showPhoneNumbers();
                            break;
                    }
                });
            });

            // WhatsApp Integration
            function openWhatsApp() {
                const phoneNumber = currentVendorData.phone;

                if (!phoneNumber) {
                    alert('Phone number not available for this vendor.');
                    return;
                }

                // Clean phone number (remove spaces, dashes, etc.)
                const cleanPhone = phoneNumber.replace(/\D/g, '');

                // Create WhatsApp message with product details
                let message = `Hello ${currentVendorData.name}! `;

                if (currentProductData.title && currentProductData.title !== '') {
                    message += `I'm interested in your product: *${currentProductData.title}* `;
                    message += `priced at *${currentProductData.price}*. `;
                    message += `Can you please provide more details about this item?`;
                } else {
                    message += `I'm interested in your products on Agii. `;
                    message += `Can you please share more information about what you have available?`;
                }

                // Encode message for URL
                const encodedMessage = encodeURIComponent(message);

                // Create WhatsApp URL
                const whatsappUrl = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;

                // Open in new tab
                window.open(whatsappUrl, '_blank');

                // Close modal
                contactVendorModal.hide();

                // Track contact event (optional)
                trackContactEvent('whatsapp');
            }

            // Show Phone Numbers
            function showPhoneNumbers() {
                const phoneNumber = currentVendorData.phone;

                if (!phoneNumber) {
                    alert('Phone number not available for this vendor.');
                    return;
                }

                const phoneNumbersList = document.getElementById('phoneNumbersList');
                phoneNumbersList.innerHTML = `
            <div class="text-center">
                <i class="bi bi-telephone text-primary display-4 mb-3"></i>
                <h6 class="mb-3">Call ${currentVendorData.name}</h6>
                <div class="phone-number-item mb-3">
                    <a href="tel:${phoneNumber}" class="btn btn-primary btn-lg w-100 py-3">
                        <i class="bi bi-telephone me-2"></i>
                        ${formatPhoneNumber(phoneNumber)}
                    </a>
                </div>
                <small class="text-muted">Tap to call directly</small>
            </div>
        `;

                contactVendorModal.hide();
                phoneNumbersModal.show();

                // Track contact event (optional)
                trackContactEvent('phone');
            }

            // Format phone number for display
            function formatPhoneNumber(phone) {
                // Remove all non-numeric characters
                const cleaned = phone.replace(/\D/g, '');

                // Format for Nigerian numbers (assuming 11 digits starting with 0)
                if (cleaned.length === 11 && cleaned.startsWith('0')) {
                    return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                }

                // Default formatting
                return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            }

            // Track contact events (optional - for analytics)
            function trackContactEvent(method) {
                // You can integrate with Google Analytics or your analytics platform here
                console.log(`Contact method used: ${method}`, {
                    vendorId: currentVendorData.id,
                    productId: currentProductData.id,
                    method: method
                });

                // Example with gtag (if you have Google Analytics)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'contact_vendor', {
                        'event_category': 'engagement',
                        'event_label': method,
                        'vendor_id': currentVendorData.id,
                        'product_id': currentProductData.id
                    });
                }
            }

            // Sort select functionality
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', this.value);
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endpush

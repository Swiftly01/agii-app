<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $store->store_name }} - Agii Store</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $store->description }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #8fc74a;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
            --accent-color: #8fc74a;
            --success-color: #8fc74a;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --dark-color: #2c3e50;
            --light-dark-color: #7f8c8d;
            --light-grey-color: #f8f9fa;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }

        /* Store Banner */
        .store-banner {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .banner-content {
            position: relative;
            z-index: 2;
        }

        .vendor-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .vendor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--dark-color);
            border: 3px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .vendor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vendor-details h1 {
            margin: 0 0 5px 0;
            font-size: 1.75rem;
        }

        .vendor-description {
            opacity: 0.9;
            margin: 0;
        }

        .vendor-stats {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .stat-label {
            opacity: 0.8;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Store Navigation */
        .store-nav {
            background: var(--card-bg);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .store-nav .nav-link {
            color: var(--dark-color);
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .store-nav .nav-link:hover,
        .store-nav .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .product-image {
            height: 200px;
            background: var(--light-grey-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light-dark-color);
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 2;
        }

        .badge-new {
            background: var(--primary-color);
            color: white;
        }

        .badge-discount {
            background: var(--danger-color);
            color: white;
        }

        .badge-negotiable {
            background: var(--warning-color);
            color: white;
        }

        .product-content {
            padding: 15px;
        }

        .product-category {
            color: var(--light-dark-color);
            font-size: 0.875rem;
            margin-bottom: 5px;
        }

        .product-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.4;
            height: 2.8em;
            overflow: hidden;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 8px;
        }

        .rating-stars {
            color: #ffc107;
        }

        .rating-count {
            color: var(--light-dark-color);
            font-size: 0.875rem;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .current-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .original-price {
            font-size: 0.9rem;
            color: var(--light-dark-color);
            text-decoration: line-through;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: var(--light-dark-color);
            margin-bottom: 12px;
        }

        .product-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
            transition: var(--transition);
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--light-dark-color);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 20px;
        }

        /* Filter Section */
        .filter-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .vendor-info {
                flex-direction: column;
                text-align: center;
            }

            .vendor-stats {
                justify-content: center;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .product-actions {
                flex-direction: column;
            }

            .product-title {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Store Banner -->
    <section class="store-banner">
        <div class="container">
            <div class="banner-content">
                <div class="vendor-info">
                    <div class="vendor-avatar">
                        @if ($store->logo)
                            <img src="{{ Storage::url($store->logo) }}" alt="{{ $store->store_name }}">
                        @else
                            <i class="bi bi-shop"></i>
                        @endif
                    </div>
                    <div class="vendor-details">
                        <h1>{{ $store->store_name }}</h1>
                        <p class="vendor-description">
                            {{ $store->description ?? 'Quality products and services you can trust' }}
                        </p>
                    </div>
                </div>

                <div class="vendor-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['total_products'] }}</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($store->rating, 1) }}</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-item d-none">
                        <div class="stat-value">{{ $stats['total_sales'] ?? 0 }}</div>
                        <div class="stat-label">Sales</div>
                    </div>
                    <div class="stat-item d-none">
                        <div class="stat-value">{{ $store->user->created_at->diffInYears(now()) }}+</div>
                        <div class="stat-label">Years</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Navigation -->
    <nav class="store-nav">
        <div class="container">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#products">Products ({{ $stats['total_products'] }})</a>
                </li>
                @if ($stats['total_services'] ?? 0 > 0)
                    <li class="nav-item d-none">
                        <a class="nav-link" href="#services">Services ({{ $stats['total_services'] }})</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#reviews">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="store-content" style="padding: 30px 0;">
        <div class="container">
            <!-- Products Section -->
            <section id="products">
                <!-- Filters -->
                <div class="filter-section">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">All Products</h4>
                            <p class="text-muted mb-0 small">Showing {{ $store->products->count() }} products</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <select class="form-select form-select-sm" style="width: auto;" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        @if ($category)
                                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <select class="form-select form-select-sm" style="width: auto;" id="sortFilter">
                                    <option value="newest">Newest First</option>
                                    <option value="price_low">Price: Low to High</option>
                                    <option value="price_high">Price: High to Low</option>
                                    <option value="rating">Top Rated</option>
                                    <option value="popular">Most Popular</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @forelse($store->products as $product)
                        <div class="product-card" data-category="{{ $product->category->slug ?? 'uncategorized' }}"
                            data-price="{{ $product->price }}" data-rating="{{ $product->rating }}"
                            data-views="{{ $product->views }}">
                            <div class="product-image">
                                @if (isset($product->images[0]))
                                    <img src="{{ $product->images[0] }}" alt="{{ $product->title }}"
                                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                @else
                                    <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                @endif

                                <!-- Product Badges -->
                                @if ($product->condition == 'New')
                                    <span class="product-badge badge-new">New</span>
                                @elseif($product->negotiable)
                                    <span class="product-badge badge-negotiable">Negotiable</span>
                                @endif

                                @if ($product->old_price && $product->old_price > $product->price)
                                    @php
                                        $discount = round(
                                            (($product->old_price - $product->price) / $product->old_price) * 100,
                                        );
                                    @endphp
                                    <span class="product-badge badge-discount">-{{ $discount }}%</span>
                                @endif
                            </div>

                            <div class="product-content">
                                <div class="product-category">
                                    {{ $product->category->name ?? 'Uncategorized' }} • {{ $product->condition }}
                                </div>

                                <h3 class="product-title">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $product->title }}
                                    </a>
                                </h3>

                                <!-- Rating -->
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->rating))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i == ceil($product->rating) && $product->rating != floor($product->rating))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->review_count }})</span>
                                </div>

                                <!-- Price -->
                                <div class="product-price">
                                    <span class="current-price">₦{{ number_format($product->price) }}</span>
                                    @if ($product->old_price && $product->old_price > $product->price)
                                        <span class="original-price">₦{{ number_format($product->old_price) }}</span>
                                    @endif
                                </div>

                                <!-- Meta Information -->
                                <div class="product-meta">
                                    <span><i class="bi bi-eye"></i> {{ number_format($product->views) }}</span>
                                    <span><i class="bi bi-cart"></i> {{ $product->sold_count ?? 0 }} sold</span>
                                </div>

                                <!-- Actions -->
                                <div class="product-actions">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                        class="btn btn-primary flex-fill">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                    <button class="btn btn-outline-primary add-to-cart"
                                        data-product-id="{{ $product->id }}" title="Add to Cart">
                                        <i class="bi bi-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-box"></i>
                                </div>
                                <h3>No Products Available</h3>
                                <p class="text-muted">This store hasn't added any products yet.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-arrow-left"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Services Section (if any) -->
            @if ($stats['total_services'] ?? 0 > 0)
                <section id="services" class="mt-5 d-none">
                    <h3 class="mb-4">Services</h3>
                    <div class="services-grid">
                        <!-- Services would go here -->
                    </div>
                </section>
            @endif

            <!-- Contact Section -->
            <section id="contact" class="mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Contact Store</h4>
                        <div class="row">
                            @if ($store->store_address)
                                <div class="col-md-6 mb-3">
                                    <strong><i class="bi bi-geo-alt"></i> Address</strong>
                                    <p class="mb-0">{{ $store->store_address }}</p>
                                </div>
                            @endif

                            @if ($store->store_phone)
                                <div class="col-md-6 mb-3">
                                    <strong><i class="bi bi-telephone"></i> Phone</strong>
                                    <p class="mb-0">{{ $store->store_phone }}</p>
                                </div>
                            @endif

                            @if ($store->store_email)
                                <div class="col-md-6 mb-3">
                                    <strong><i class="bi bi-envelope"></i> Email</strong>
                                    <p class="mb-0">{{ $store->store_email }}</p>
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-clock"></i> Business Hours</strong>
                                <p class="mb-0">Mon - Sat: 9:00 AM - 7:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('.store-nav .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    // Update active nav link
                    document.querySelectorAll('.store-nav .nav-link').forEach(nav => nav.classList.remove(
                        'active'));
                    this.classList.add('active');

                    // Scroll to section
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Filter functionality
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('sortFilter').addEventListener('change', sortProducts);

        function filterProducts() {
            const category = document.getElementById('categoryFilter').value;
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');

                if (!category || cardCategory === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function sortProducts() {
            const sortBy = document.getElementById('sortFilter').value;
            const productsGrid = document.getElementById('productsGrid');
            const productCards = Array.from(document.querySelectorAll('.product-card'));

            productCards.sort((a, b) => {
                switch (sortBy) {
                    case 'price_low':
                        return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                    case 'price_high':
                        return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                    case 'rating':
                        return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute(
                            'data-rating'));
                    case 'popular':
                        return parseFloat(b.getAttribute('data-views')) - parseFloat(a.getAttribute('data-views'));
                    default: // newest
                        return 0; // Already sorted by newest from controller
                }
            });

            // Reappend sorted cards
            productCards.forEach(card => productsGrid.appendChild(card));
        }

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const button = this;
                const originalHTML = button.innerHTML;

                // Show loading state
                button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i>';
                button.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    showNotification('Product added to cart successfully!', 'success');
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 1000);
            });
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `position-fixed bottom-0 end-0 m-3`;
            notification.style.zIndex = '9999';

            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

            notification.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="min-width: 300px;">
                    <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        // Update stats counter animation
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                element.textContent = Math.floor(current).toLocaleString();
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                }
            }, 30);
        }

        // Animate stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            const stats = document.querySelectorAll('.stat-value');
            stats.forEach(stat => {
                const text = stat.textContent;
                const target = parseInt(text.replace(/[^\d]/g, ''));
                if (!isNaN(target)) {
                    animateCounter(stat, target);
                }
            });
        });
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TechElite Gadgets - Agii Store</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

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

        /* Agii Header */
        .agii-header {
            background: var(--card-bg);
            box-shadow: var(--shadow);
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .agii-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
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

        /* Main Content */
        .store-content {
            padding: 30px 0;
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
        }

        .badge-new {
            background: var(--primary-color);
            color: white;
        }

        .badge-discount {
            background: var(--danger-color);
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
            justify-content: between;
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

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .service-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
        }

        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 15px;
        }

        .service-meta {
            display: flex;
            gap: 15px;
            margin: 12px 0;
            font-size: 0.875rem;
            color: var(--light-dark-color);
        }

        /* Contact Section */
        .contact-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--light-dark-color);
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 15px;
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

            .services-grid {
                grid-template-columns: 1fr;
            }

            .product-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Agii Header -->
    <header class="agii-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="agii-logo">
                    <i class="bi bi-shop"></i> Agii
                </a>
                <div class="header-actions">
                    <a href="/search" class="text-decoration-none text-dark">
                        <i class="bi bi-search"></i>
                    </a>
                    <a href="/cart" class="text-decoration-none text-dark">
                        <i class="bi bi-cart"></i>
                    </a>
                    <a href="/login" class="btn btn-outline-primary btn-sm">Sign In</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Store Banner -->
    <section class="store-banner">
        <div class="container">
            <div class="banner-content">
                <div class="vendor-info">
                    <div class="vendor-avatar">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="vendor-details">
                        <h1>TechElite Gadgets</h1>
                        <p class="vendor-description">Premium electronics and tech solutions with guaranteed quality and
                            reliable service</p>
                    </div>
                </div>

                <div class="vendor-stats">
                    <div class="stat-item">
                        <div class="stat-value">4.9</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">2.4K+</div>
                        <div class="stat-label">Sales</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">98%</div>
                        <div class="stat-label">Positive Reviews</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">5+</div>
                        <div class="stat-label">Years on Agii</div>
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
                    <a class="nav-link active" href="#products">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Services</a>
                </li>
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
    <main class="store-content">
        <div class="container">
            <!-- Products Section -->
            <section id="products" class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Products</h3>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>All Categories</option>
                            <option>Phones</option>
                            <option>Laptops</option>
                            <option>Accessories</option>
                        </select>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Sort by: Newest</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Most Popular</option>
                        </select>
                    </div>
                </div>

                <div class="products-grid">
                    <!-- Product 1 -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="bi bi-phone" style="font-size: 2.5rem;"></i>
                            <span class="product-badge badge-new">New</span>
                        </div>
                        <div class="product-content">
                            <div class="product-category">Electronics • Phones</div>
                            <h3 class="product-title">iPhone 15 Pro Max 256GB</h3>

                            <div class="product-rating">
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="rating-count">(128)</span>
                            </div>

                            <div class="product-price">
                                <span class="current-price">₦850,000</span>
                            </div>

                            <div class="product-meta">
                                <span><i class="bi bi-eye"></i> 2.4K</span>
                                <span><i class="bi bi-cart"></i> 48 sold</span>
                            </div>

                            <div class="product-actions">
                                <button class="btn btn-primary flex-fill">
                                    <i class="bi bi-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="bi bi-laptop" style="font-size: 2.5rem;"></i>
                            <span class="product-badge badge-discount">-15%</span>
                        </div>
                        <div class="product-content">
                            <div class="product-category">Electronics • Laptops</div>
                            <h3 class="product-title">MacBook Pro 16" M3 Max</h3>

                            <div class="product-rating">
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <span class="rating-count">(89)</span>
                            </div>

                            <div class="product-price">
                                <span class="current-price">₦1,250,000</span>
                                <span class="original-price">₦1,450,000</span>
                            </div>

                            <div class="product-meta">
                                <span><i class="bi bi-eye"></i> 1.8K</span>
                                <span><i class="bi bi-cart"></i> 32 sold</span>
                            </div>

                            <div class="product-actions">
                                <button class="btn btn-primary flex-fill">
                                    <i class="bi bi-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="bi bi-watch" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="product-content">
                            <div class="product-category">Electronics • Wearables</div>
                            <h3 class="product-title">Apple Watch Ultra 2</h3>

                            <div class="product-rating">
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="rating-count">(67)</span>
                            </div>

                            <div class="product-price">
                                <span class="current-price">₦320,000</span>
                            </div>

                            <div class="product-meta">
                                <span><i class="bi bi-eye"></i> 956</span>
                                <span><i class="bi bi-cart"></i> 28 sold</span>
                            </div>

                            <div class="product-actions">
                                <button class="btn btn-primary flex-fill">
                                    <i class="bi bi-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="bi bi-headphones" style="font-size: 2.5rem;"></i>
                        </div>
                        <div class="product-content">
                            <div class="product-category">Electronics • Audio</div>
                            <h3 class="product-title">Sony WH-1000XM5 Headphones</h3>

                            <div class="product-rating">
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="rating-count">(142)</span>
                            </div>

                            <div class="product-price">
                                <span class="current-price">₦185,000</span>
                                <span class="original-price">₦210,000</span>
                            </div>

                            <div class="product-meta">
                                <span><i class="bi bi-eye"></i> 1.2K</span>
                                <span><i class="bi bi-cart"></i> 56 sold</span>
                            </div>

                            <div class="product-actions">
                                <button class="btn btn-primary flex-fill">
                                    <i class="bi bi-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Services Section -->
            <section id="services" class="mb-5">
                <h3 class="mb-4">Services</h3>
                <div class="services-grid">
                    <!-- Service 1 -->
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5>Device Repair & Maintenance</h5>
                        <p class="text-muted mb-3">Professional repair services for all your electronic devices with
                            warranty</p>

                        <div class="service-meta">
                            <span><i class="bi bi-clock"></i> 1-3 hours</span>
                            <span><i class="bi bi-star-fill text-warning"></i> 4.9/5</span>
                        </div>

                        <div class="product-price mb-3">
                            <span class="current-price">From ₦15,000</span>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check"></i> Book Service
                        </button>
                    </div>

                    <!-- Service 2 -->
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <h5>Software Installation & Setup</h5>
                        <p class="text-muted mb-3">Complete software installation, system setup, and optimization
                            services</p>

                        <div class="service-meta">
                            <span><i class="bi bi-clock"></i> 30 mins - 2 hours</span>
                            <span><i class="bi bi-star-fill text-warning"></i> 4.8/5</span>
                        </div>

                        <div class="product-price mb-3">
                            <span class="current-price">From ₦8,000</span>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check"></i> Book Service
                        </button>
                    </div>

                    <!-- Service 3 -->
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Data Recovery & Backup</h5>
                        <p class="text-muted mb-3">Professional data recovery services with secure backup solutions</p>

                        <div class="service-meta">
                            <span><i class="bi bi-clock"></i> 2-6 hours</span>
                            <span><i class="bi bi-star-fill text-warning"></i> 4.9/5</span>
                        </div>

                        <div class="product-price mb-3">
                            <span class="current-price">From ₦25,000</span>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check"></i> Book Service
                        </button>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="contact-section">
                <h4 class="mb-4">Contact Information</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <strong>Store Location</strong>
                            <div class="text-muted">123 Tech Street, Victoria Island, Lagos</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <strong>Phone Number</strong>
                            <div class="text-muted">+234 901 234 5678</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <strong>Email Address</strong>
                            <div class="text-muted">contact@techelite.ng</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <strong>Business Hours</strong>
                            <div class="text-muted">Mon - Sat: 9AM - 7PM</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Agii Footer -->
    <footer style="background: var(--dark-color); color: white; padding: 30px 0; margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Agii Marketplace</h5>
                    <p class="text-white-50">Connecting buyers and sellers across Nigeria with trusted transactions and
                        reliable service.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Browse Categories</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Become a Seller</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Terms of Service</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Return Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0 text-white-50">&copy; 2024 Agii. All rights reserved.</p>
            </div>
        </div>
    </footer>

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

        // Add to cart functionality
        document.querySelectorAll('.btn-primary').forEach(button => {
            if (button.textContent.includes('Add to Cart')) {
                button.addEventListener('click', function() {
                    const productCard = this.closest('.product-card');
                    const productTitle = productCard.querySelector('.product-title').textContent;
                    const productPrice = productCard.querySelector('.current-price').textContent;

                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check"></i> Added';
                    this.disabled = true;

                    // Show notification
                    showNotification(`Added ${productTitle} to cart - ${productPrice}`);

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                });
            }
        });

        // Book service functionality
        document.querySelectorAll('.btn-primary').forEach(button => {
            if (button.textContent.includes('Book Service')) {
                button.addEventListener('click', function() {
                    const serviceCard = this.closest('.service-card');
                    const serviceTitle = serviceCard.querySelector('h5').textContent;
                    const servicePrice = serviceCard.querySelector('.current-price').textContent;

                    showNotification(`Booking request sent for ${serviceTitle} - ${servicePrice}`);
                });
            }
        });

        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'position-fixed bottom-0 end-0 m-3';
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="min-width: 300px;">
                    <i class="bi bi-check-circle me-2"></i>
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

        // Update stats counter (for demo)
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                element.textContent = Math.floor(current) + '+';
                if (current >= target) {
                    element.textContent = target + '+';
                    clearInterval(timer);
                }
            }, 30);
        }

        // Animate stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            const stats = document.querySelectorAll('.stat-value');
            stats.forEach(stat => {
                const text = stat.textContent;
                const target = parseInt(text);
                if (!isNaN(target)) {
                    animateCounter(stat, target);
                }
            });
        });
    </script>
</body>

</html>

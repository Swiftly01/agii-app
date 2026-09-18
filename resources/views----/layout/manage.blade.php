<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Products & Services - Agii Vendor</title>
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

        /* Sidebar */
        .sidebar {
            background: var(--card-bg);
            min-height: 100vh;
            box-shadow: var(--shadow);
            position: fixed;
            width: 280px;
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            background: var(--primary-color);
            color: white;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary-color);
            border-right: 3px solid var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: var(--transition);
        }

        /* Top Navigation */
        .top-nav {
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            border-radius: 20px;
            padding-left: 40px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-dark-color);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            margin: 0;
        }

        .user-role {
            color: var(--light-dark-color);
            font-size: 0.875rem;
            margin: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 15px;
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 25px;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 12px 25px;
            color: var(--light-dark-color);
            font-weight: 600;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: var(--primary-color);
        }

        /* Filter Bar */
        .filter-bar {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stat-label {
            color: var(--light-dark-color);
            margin: 0;
        }

        /* Products/Services Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .item-card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .item-image {
            height: 200px;
            background: var(--light-grey-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light-dark-color);
            position: relative;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-product {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .badge-service {
            background: #e3f2fd;
            color: #1976d2;
        }

        .item-content {
            padding: 20px;
        }

        .item-category {
            color: var(--light-dark-color);
            font-size: 0.875rem;
            margin-bottom: 5px;
        }

        .item-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .item-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .item-meta {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.875rem;
            color: var(--light-dark-color);
        }

        .item-actions {
            display: flex;
            gap: 10px;
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
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            color: var(--light-dark-color);
            border-color: var(--border-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--light-grey-color);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: var(--primary-light);
            color: var(--success-color);
        }

        .status-draft {
            background: #fff3cd;
            color: var(--warning-color);
        }

        .status-out-of-stock {
            background: #f8d7da;
            color: var(--danger-color);
        }

        .status-inactive {
            background: #e2e3e5;
            color: var(--light-dark-color);
        }

        /* Table Styles */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table {
            margin: 0;
            background: var(--card-bg);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-grey-color);
            padding: 15px;
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
        }

        .table-hover tbody tr:hover {
            background-color: var(--primary-light);
        }

        /* Bulk Actions */
        .bulk-actions {
            background: var(--light-grey-color);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .bulk-actions.show {
            display: flex;
            align-items: center;
            gap: 15px;
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

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 20px 25px;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 20px 25px;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .items-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: start;
            }

            .page-actions {
                width: 100%;
                justify-content: space-between;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo">Agii Vendor</h1>
        </div>

        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="vendor-dashboard.html">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="bi bi-box"></i>
                        Products & Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-cart"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people"></i>
                        Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-graph-up"></i>
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-wallet2"></i>
                        Earnings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-chat-left"></i>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-star"></i>
                        Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search products, services...">
            </div>

            <div class="user-menu">
                <div class="user-info">
                    <p class="user-name">John Doe</p>
                    <p class="user-role">Electronics Vendor</p>
                </div>
                <div class="user-avatar">
                    JD
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Manage Products & Services</h1>
            <div class="page-actions">
                <button class="btn btn-outline-primary">
                    <i class="bi bi-download"></i> Export
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-circle"></i> Add New
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="itemsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products"
                    type="button" role="tab">
                    <i class="bi bi-box"></i> Products
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services"
                    type="button" role="tab">
                    <i class="bi bi-tools"></i> Services
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="drafts-tab" data-bs-toggle="tab" data-bs-target="#drafts"
                    type="button" role="tab">
                    <i class="bi bi-file-earmark"></i> Drafts
                </button>
            </li>
        </ul>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
                <h3 class="stat-value">23</h3>
                <p class="stat-label">Active Products</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <h3 class="stat-value">8</h3>
                <p class="stat-label">Active Services</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3 class="stat-value">1,248</h3>
                <p class="stat-label">Total Views</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cart"></i>
                </div>
                <h3 class="stat-value">156</h3>
                <p class="stat-label">Total Orders</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-row">
                <div>
                    <label class="form-label fw-semibold">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="fashion">Fashion</option>
                        <option value="home">Home & Garden</option>
                        <option value="vehicles">Vehicles</option>
                    </select>
                </div>
                <div>
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="out-of-stock">Out of Stock</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="form-label fw-semibold">Sort By</label>
                    <select class="form-select" id="sortFilter">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label fw-semibold" for="selectAll" id="selectedCount">
                    0 items selected
                </label>
            </div>
            <select class="form-select form-select-sm" style="width: auto;" id="bulkAction">
                <option>Bulk Actions</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
                <option value="duplicate">Duplicate</option>
            </select>
            <button class="btn btn-primary btn-sm" onclick="applyBulkAction()">Apply</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="itemsTabContent">
            <!-- Products Tab -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <div class="items-grid" id="productsGrid">
                    <!-- Product cards will be loaded here -->
                </div>
            </div>

            <!-- Services Tab -->
            <div class="tab-pane fade" id="services" role="tabpanel">
                <div class="items-grid" id="servicesGrid">
                    <!-- Service cards will be loaded here -->
                </div>
            </div>

            <!-- Drafts Tab -->
            <div class="tab-pane fade" id="drafts" role="tabpanel">
                <div class="items-grid" id="draftsGrid">
                    <!-- Draft cards will be loaded here -->
                </div>
            </div>
        </div>

        <!-- List View Toggle -->
        <div class="text-center mt-4">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="gridView">
                    <i class="bi bi-grid-3x3-gap"></i> Grid View
                </label>

                <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                <label class="btn btn-outline-primary" for="listView">
                    <i class="bi bi-list-ul"></i> List View
                </label>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="itemType" id="productType"
                                autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="productType">
                                <i class="bi bi-box"></i> Product
                            </label>

                            <input type="radio" class="btn-check" name="itemType" id="serviceType"
                                autocomplete="off">
                            <label class="btn btn-outline-primary" for="serviceType">
                                <i class="bi bi-tools"></i> Service
                            </label>
                        </div>
                    </div>
                    <p class="text-muted text-center">Choose whether you want to add a product or service</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="redirectToAddPage()">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                    <p class="text-muted" id="deleteItemName"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for products and services
        const sampleData = {
            products: [{
                    id: 1,
                    type: 'product',
                    title: 'iPhone 13 Pro Max 256GB',
                    category: 'Electronics > Phones',
                    price: '₦450,000',
                    status: 'active',
                    image: 'iphone13.jpg',
                    views: 245,
                    orders: 12,
                    stock: 8,
                    createdAt: '2024-01-15'
                },
                {
                    id: 2,
                    type: 'product',
                    title: 'MacBook Air M2 512GB',
                    category: 'Electronics > Laptops',
                    price: '₦680,000',
                    status: 'active',
                    image: 'macbook-air.jpg',
                    views: 189,
                    orders: 8,
                    stock: 3,
                    createdAt: '2024-01-10'
                },
                {
                    id: 3,
                    type: 'product',
                    title: 'AirPods Pro 2nd Generation',
                    category: 'Electronics > Audio',
                    price: '₦120,000',
                    status: 'out-of-stock',
                    image: 'airpods-pro.jpg',
                    views: 156,
                    orders: 15,
                    stock: 0,
                    createdAt: '2024-01-05'
                },
                {
                    id: 4,
                    type: 'product',
                    title: 'iPad Air 5th Generation',
                    category: 'Electronics > Tablets',
                    price: '₦320,000',
                    status: 'draft',
                    image: 'ipad-air.jpg',
                    views: 0,
                    orders: 0,
                    stock: 5,
                    createdAt: '2024-01-20'
                }
            ],
            services: [{
                    id: 5,
                    type: 'service',
                    title: 'iPhone Screen Replacement',
                    category: 'Services > Phone Repair',
                    price: '₦25,000',
                    status: 'active',
                    image: 'screen-repair.jpg',
                    views: 89,
                    orders: 23,
                    duration: '1-2 hours',
                    createdAt: '2024-01-12'
                },
                {
                    id: 6,
                    type: 'service',
                    title: 'Laptop Software Installation',
                    category: 'Services > Computer Repair',
                    price: '₦15,000',
                    status: 'active',
                    image: 'software-install.jpg',
                    views: 67,
                    orders: 15,
                    duration: '30 minutes',
                    createdAt: '2024-01-08'
                }
            ],
            drafts: [{
                id: 7,
                type: 'product',
                title: 'Samsung Galaxy S23 Ultra',
                category: 'Electronics > Phones',
                price: '₦520,000',
                status: 'draft',
                image: 'samsung-s23.jpg',
                views: 0,
                orders: 0,
                stock: 0,
                createdAt: '2024-01-22'
            }]
        };

        let selectedItems = new Set();
        let currentView = 'grid';

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
            loadItems();
            setupEventListeners();
        });

        function initializePage() {
            // Mobile sidebar toggle
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });

            // View mode toggle
            document.getElementById('listView').addEventListener('change', function() {
                if (this.checked) {
                    switchToListView();
                }
            });

            document.getElementById('gridView').addEventListener('change', function() {
                if (this.checked) {
                    switchToGridView();
                }
            });
        }

        function setupEventListeners() {
            // Filter changes
            document.getElementById('categoryFilter').addEventListener('change', filterItems);
            document.getElementById('statusFilter').addEventListener('change', filterItems);
            document.getElementById('sortFilter').addEventListener('change', sortItems);

            // Select all checkbox
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    toggleItemSelection(checkbox);
                });
            });
        }

        function loadItems() {
            renderProducts();
            renderServices();
            renderDrafts();
        }

        function renderProducts() {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';

            if (sampleData.products.length === 0) {
                grid.innerHTML = getEmptyState('products');
                return;
            }

            sampleData.products.forEach(product => {
                grid.appendChild(createItemCard(product));
            });
        }

        function renderServices() {
            const grid = document.getElementById('servicesGrid');
            grid.innerHTML = '';

            if (sampleData.services.length === 0) {
                grid.innerHTML = getEmptyState('services');
                return;
            }

            sampleData.services.forEach(service => {
                grid.appendChild(createItemCard(service));
            });
        }

        function renderDrafts() {
            const grid = document.getElementById('draftsGrid');
            grid.innerHTML = '';

            if (sampleData.drafts.length === 0) {
                grid.innerHTML = getEmptyState('drafts');
                return;
            }

            sampleData.drafts.forEach(draft => {
                grid.appendChild(createItemCard(draft));
            });
        }

        function createItemCard(item) {
            const card = document.createElement('div');
            card.className = 'item-card';
            card.innerHTML = `
                <div class="item-image">
                    ${item.image ?
                        `<img src="/images/${item.image}" alt="${item.title}">` :
                        `<i class="bi bi-${item.type === 'product' ? 'box' : 'tools'}" style="font-size: 3rem;"></i>`
                    }
                    <span class="item-badge badge-${item.type}">
                        ${item.type === 'product' ? 'Product' : 'Service'}
                    </span>
                </div>
                <div class="item-content">
                    <div class="form-check">
                        <input class="form-check-input item-checkbox" type="checkbox" data-id="${item.id}">
                    </div>
                    <div class="item-category">${item.category}</div>
                    <h3 class="item-title">${item.title}</h3>
                    <div class="item-price">${item.price}</div>
                    <div class="item-meta">
                        <span><i class="bi bi-eye"></i> ${item.views} views</span>
                        <span><i class="bi bi-cart"></i> ${item.orders} orders</span>
                        ${item.type === 'product' ? `<span><i class="bi bi-box"></i> ${item.stock} in stock</span>` : ''}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge status-${item.status}">
                            ${getStatusText(item.status)}
                        </span>
                        <div class="item-actions">
                            <button class="btn btn-outline-primary btn-sm" onclick="editItem(${item.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="duplicateItem(${item.id})">
                                <i class="bi bi-files"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(${item.id}, '${item.title}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add checkbox event listener
            const checkbox = card.querySelector('.item-checkbox');
            checkbox.addEventListener('change', function() {
                toggleItemSelection(this);
            });

            return card;
        }

        function getEmptyState(type) {
            const types = {
                products: {
                    icon: 'bi-box',
                    text: 'No products found'
                },
                services: {
                    icon: 'bi-tools',
                    text: 'No services found'
                },
                drafts: {
                    icon: 'bi-file-earmark',
                    text: 'No drafts found'
                }
            };

            const config = types[type] || types.products;

            return `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="${config.icon}"></i>
                    </div>
                    <h4>${config.text}</h4>
                    <p class="text-muted">Get started by adding your first ${type.slice(0, -1)}</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-circle"></i> Add New ${type === 'products' ? 'Product' : 'Service'}
                    </button>
                </div>
            `;
        }

        function getStatusText(status) {
            const statusMap = {
                'active': 'Active',
                'draft': 'Draft',
                'out-of-stock': 'Out of Stock',
                'inactive': 'Inactive'
            };
            return statusMap[status] || status;
        }

        function toggleItemSelection(checkbox) {
            const itemId = parseInt(checkbox.dataset.id);

            if (checkbox.checked) {
                selectedItems.add(itemId);
            } else {
                selectedItems.delete(itemId);
            }

            updateBulkActions();
        }

        function updateBulkActions() {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');

            if (selectedItems.size > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = `${selectedItems.size} item${selectedItems.size > 1 ? 's' : ''} selected`;

                // Update select all checkbox state
                const totalItems = document.querySelectorAll('.item-checkbox').length;
                selectAll.checked = selectedItems.size === totalItems;
                selectAll.indeterminate = selectedItems.size > 0 && selectedItems.size < totalItems;
            } else {
                bulkActions.classList.remove('show');
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
        }

        function applyBulkAction() {
            const action = document.getElementById('bulkAction').value;
            if (action === 'Bulk Actions') {
                alert('Please select a bulk action');
                return;
            }

            if (selectedItems.size === 0) {
                alert('Please select at least one item');
                return;
            }

            if (confirm(`Are you sure you want to ${action} ${selectedItems.size} item(s)?`)) {
                // Simulate API call
                console.log(`Applying ${action} to items:`, Array.from(selectedItems));

                // Reset selection
                selectedItems.clear();
                document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
                updateBulkActions();

                alert(`Successfully ${action}d ${selectedItems.size} item(s)`);
            }
        }

        function filterItems() {
            // Implement filtering logic based on selected filters
            console.log('Filtering items...');
        }

        function sortItems() {
            // Implement sorting logic
            console.log('Sorting items...');
        }

        function resetFilters() {
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            filterItems();
        }

        function editItem(id) {
            // Redirect to edit page or open edit modal
            console.log('Editing item:', id);
            window.location.href = `/edit-item/${id}`;
        }

        function duplicateItem(id) {
            if (confirm('Duplicate this item?')) {
                // Simulate duplication
                console.log('Duplicating item:', id);
                alert('Item duplicated successfully!');
            }
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteItemName').textContent = name;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();

            document.getElementById('confirmDelete').onclick = function() {
                deleteItem(id);
                modal.hide();
            };
        }

        function deleteItem(id) {
            // Remove item from sample data (in real app, this would be an API call)
            Object.keys(sampleData).forEach(key => {
                sampleData[key] = sampleData[key].filter(item => item.id !== id);
            });

            // Reload the items
            loadItems();
            alert('Item deleted successfully!');
        }

        function redirectToAddPage() {
            const itemType = document.querySelector('input[name="itemType"]:checked').id;
            const modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
            modal.hide();

            if (itemType === 'productType') {
                window.location.href = '/sell?type=product';
            } else {
                window.location.href = '/sell?type=service';
            }
        }

        function switchToListView() {
            currentView = 'list';
            document.querySelectorAll('.items-grid').forEach(grid => {
                grid.style.gridTemplateColumns = '1fr';
            });
        }

        function switchToGridView() {
            currentView = 'grid';
            document.querySelectorAll('.items-grid').forEach(grid => {
                grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
            });
        }
    </script>
</body>

</html>

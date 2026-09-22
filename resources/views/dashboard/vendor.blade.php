@extends('layout.layout')
@section('title', 'Vendor Dashboard')
@section('content')
   
@if (session('success'))
    <div class="d-flex justify-content-end w-100">
        <div class="alert alert-success mb-3" style="width: 700px;">
            {{ session('success') }}
        </div>
    </div>
@endif



    <div class="container-fluid">
        <!-- Sidebar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white sidebar d-none">
            <div class="container-fluid">
                <a class="navbar-brand sidebar-logo" href="#">Agii Vendor</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#vendorSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="vendorSidebar">
                    <ul class="navbar-nav flex-column w-100">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>

                        <!-- Store Management Section -->
                        @if (auth()->user()->hasStore() && auth()->user()->isOnStorePlan())
                            <li class="nav-item">
                                <div class="nav-divider">
                                    <span class="nav-divider-text">Store Management</span>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stores.edit', auth()->user()->store->id) }}">
                                    <i class="bi bi-shop me-2"></i>
                                    Update Store
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stores.create') }}">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Store Analytics
                                </a>
                            </li>
                        @elseif(!auth()->user()->hasStore() && auth()->user()->isOnStorePlan())
                            <li class="nav-item">
                                <div class="nav-divider">
                                    <span class="nav-divider-text">Store Management</span>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stores.create') }}">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Create Store
                                </a>
                            </li>
                        @endif

                        <!-- Regular Vendor Menu Items -->
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-box me-2"></i>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-tools me-2"></i>
                                Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-graph-up me-2"></i>
                                Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-chat-left me-2"></i>
                                Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-star me-2"></i>
                                Reviews
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Search products, services, messages...">
                </div>

                <div class="user-menu">
                    <div class="user-info">
                        <p class="user-name">
                            {{ auth()->user()->business_name ?: auth()->user()->full_name }}
                        </p>
                        <p class="user-role">{{ auth()->user()->business_type ?: 'Vendor' }}</p>

                        <!-- Subscription Status -->
                        @if (auth()->user()->hasActiveSubscription())
                            <span class="badge bg-success store-badge">
                                <i class="bi bi-patch-check me-1"></i>
                                {{ auth()->user()->current_plan->name ?? 'Active Plan' }}
                            </span>
                        @else
                            <span class="badge bg-warning store-badge">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                No Active Subscription
                            </span>
                        @endif

                        <!-- Store Status Badge -->
                        @if (auth()->user()->hasStore())
                            <span class="badge bg-info store-badge mt-1">
                                <i class="bi bi-shop me-1"></i>Store Active
                            </span>
                        @elseif(auth()->user()->isOnStorePlan())
                            <span class="badge bg-warning store-badge mt-1">
                                <i class="bi bi-exclamation-triangle me-1"></i>No Store Created
                            </span>
                        @endif
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3 class="stat-value">{{ number_format($totalViews) }}</h3>
                    <p class="stat-label">Total Views</p>
                    <span class="stat-change change-positive">
                        <i class="bi bi-arrow-up"></i> From your products
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h3 class="stat-value">{{ number_format($totalInquiries) }}</h3>
                    <p class="stat-label">Customer Inquiries</p>
                    <span class="stat-change change-positive">
                        <i class="bi bi-arrow-up"></i> Total contacts received
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <h3 class="stat-value">{{ number_format($activeProducts) }}</h3>
                    <p class="stat-label">Active Products</p>
                    <span class="stat-change change-positive">
                        <i class="bi bi-arrow-up"></i> {{ $newProductsThisWeek }} new this week
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h3 class="stat-value">{{ $averageRating }}</h3>
                    <p class="stat-label">Average Rating</p>
                    <span class="stat-change change-positive">
                        <i class="bi bi-arrow-up"></i> Based on customer feedback
                    </span>
                </div>
            </div>








            <!-- Store Alert for Eligible Users -->
            @if (auth()->user()->isOnStorePlan() && !auth()->user()->hasStore())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shop-window me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Create Your Store!</h6>
                            <p class="mb-0">Your
                                <strong>{{ auth()->user()->current_plan->name ?? 'Store Plan' }}</strong> includes store
                                features. <a href="{{ route('stores.create') }}" class="alert-link">Create your store
                                    now</a> to access enhanced business tools.
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Subscription Status Card -->
            @if (auth()->user()->hasActiveSubscription())
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Subscription Details</h3>
                        <div class="card-actions">
                            <span
                                class="badge bg-{{ auth()->user()->activeSubscription->status == 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst(auth()->user()->activeSubscription->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="subscription-info">
                                <h5>{{ auth()->user()->current_plan->name }}</h5>
                                <p class="text-muted mb-2">{{ auth()->user()->current_plan->description }}</p>
                                <div class="subscription-features">
                                    @if (auth()->user()->current_plan->product_limit)
                                        <span class="feature-badge">Up to
                                            {{ auth()->user()->current_plan->product_limit }} products</span>
                                    @endif
                                    @if (auth()->user()->current_plan->featured_listings)
                                        <span
                                            class="feature-badge">{{ auth()->user()->current_plan->featured_listings_count }}
                                            featured listings</span>
                                    @endif
                                    @if (auth()->user()->current_plan->analytics)
                                        <span class="feature-badge">Advanced analytics</span>
                                    @endif
                                    @if (auth()->user()->current_plan->custom_storefront)
                                        <span class="feature-badge">Custom storefront</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="subscription-dates">
                                <p><strong>Billing Cycle:</strong>
                                    {{ ucfirst(auth()->user()->activeSubscription->billing_cycle) }}</p>
                                <p><strong>Started:</strong>
                                    {{ auth()->user()->activeSubscription->starts_at->format('M j, Y') }}</p>
                                <p><strong>Expires:</strong>
                                    {{ auth()->user()->activeSubscription->expires_at->format('M j, Y') }}</p>
                                <p><strong>Amount:</strong>
                                    ₦{{ number_format(auth()->user()->activeSubscription->amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif









            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="quick-actions">
                    <div class="action-card" onclick="location.href='{{ route('products.create', 'product') }}'">
                        <div class="action-icon">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <h5 class="action-title">Add New Product</h5>
                        <p class="action-desc">List a new product for sale</p>
                    </div>

                    <div class="action-card" onclick="location.href='{{ route('products.create', 'service') }}'">
                        <div class="action-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5 class="action-title">Add Service</h5>
                        <p class="action-desc">Offer a new service</p>
                    </div>

                    <!-- Store Action Card -->
                    @if (auth()->user()->hasStore() && auth()->user()->isOnStorePlan())
                        <div class="action-card"
                            onclick="location.href='{{ route('stores.edit', auth()->user()->store->id) }}'">
                            <div class="action-icon" style="background: #e8f5e8; color: #2e7d32;">
                                <i class="bi bi-shop"></i>
                            </div>
                            <h5 class="action-title">Update Store</h5>
                            <p class="action-desc">Manage your store settings</p>
                        </div>
                    @elseif(auth()->user()->isOnStorePlan())
                        <div class="action-card" onclick="location.href='{{ route('stores.create') }}'">
                            <div class="action-icon" style="background: #fff3e0; color: #ef6c00;">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <h5 class="action-title">Create Store</h5>
                            <p class="action-desc">Set up your business store</p>
                        </div>
                    @endif

                    <div class="action-card d-none" onclick="location.href='{{ route('vendor.analytics') }}'">
                        <div class="action-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="action-title">View Analytics</h5>
                        <p class="action-desc">Check business performance</p>
                    </div>

                    <div class="action-card" onclick="location.href='{{ route('vendor.showadvert') }}'">
                        <div class="action-icon">
                            <i class="bi bi-gift"></i>
                        </div>
                        <h5 class="action-title">My Adverts</h5>
                        <p class="action-desc">Manage your product listings</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Recent Customer Contacts -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Customer Contacts</h3>
                            <div class="card-actions">
                                <button class="btn btn-outline-primary btn-sm">View All</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Contact Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentContacts as $contact)
                                        <tr>
                                            <td>{{ $contact->user->first_name ?? 'Customer' }}</td>
                                            <td>{{ $contact->product_name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $contact->contact_method == 'whatsapp' ? 'success' : 'primary' }}">
                                                    {{ ucfirst($contact->contact_method) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-{{ $contact->status ?? 'contacted' }}">
                                                    {{ ucfirst($contact->status ?? 'Contacted') }}
                                                </span>
                                            </td>
                                            <td>{{ $contact->contact_date->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="bi bi-chat-dots text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mt-2">No customer contacts yet</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Store Information Card -->
                    @if (auth()->user()->hasStore())
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h3 class="card-title">Store Information</h3>
                            </div>
                            <div class="store-info">
                                <div class="d-flex align-items-center mb-3">
                                    @if (auth()->user()->store->logo)
                                        <img src="{{ asset(auth()->user()->store->logo) }}"
                                            alt="{{ auth()->user()->store->store_name }}" class="store-logo me-3">
                                    @else
                                        <div class="store-logo-placeholder me-3">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ auth()->user()->store->store_name }}</h6>
                                        <span
                                            class="badge bg-{{ auth()->user()->store->is_verified ? 'success' : 'warning' }}">
                                            {{ auth()->user()->store->is_verified ? 'Verified' : 'Pending Verification' }}
                                        </span>
                                    </div>
                                </div>
                                <p class="store-description small text-muted mb-3">
                                    {{ Str::limit(auth()->user()->store->description, 100) }}
                                </p>
                                <div class="store-stats">
                                    <div class="store-stat">
                                        <strong>{{ auth()->user()->store->products_count ?? 0 }}</strong>
                                        <span>Products</span>
                                    </div>
                                    <div class="store-stat">
                                        <strong>{{ auth()->user()->store->rating ?? '0.0' }}</strong>
                                        <span>Rating</span>
                                    </div>
                                    <div class="store-stat">
                                        <strong>{{ auth()->user()->store->total_reviews ?? 0 }}</strong>
                                        <span>Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Activity -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Activity</h3>
                        </div>
                        <ul class="activity-list">
                            @forelse($recentActivities as $activity)
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="bi bi-{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6 class="activity-title">{{ $activity['title'] }}</h6>
                                        <p class="activity-desc">{{ $activity['description'] }}</p>
                                    </div>
                                    <div class="activity-time">{{ $activity['time'] }}</div>
                                </li>
                            @empty
                                <li class="activity-item">
                                    <div class="activity-content text-center py-3">
                                        <p class="text-muted">No recent activity</p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Your existing CSS styles remain exactly the same */
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

        /* Responsive Sidebar */
        .sidebar {
            background: var(--card-bg);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .nav-link {
            color: var(--dark-color);
            padding: 12px 16px;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            margin: 2px 0;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            padding: 0;
        }

        /* Top Navigation */
        .top-nav {
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
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
            font-size: 0.95rem;
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
            font-size: 0.9rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
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
            margin: 0 auto 15px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stat-label {
            color: var(--light-dark-color);
            margin: 5px 0;
            font-size: 0.9rem;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            display: block;
            margin-top: 5px;
        }

        .change-positive {
            color: var(--success-color);
        }

        .change-negative {
            color: var(--danger-color);
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .dashboard-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            border-radius: 8px;
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

        /* Table Styles */
        .table {
            margin: 0;
            font-size: 0.875rem;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-grey-color);
            padding: 12px 8px;
        }

        .table td {
            vertical-align: middle;
            padding: 10px 8px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: var(--primary-light);
            color: var(--success-color);
        }

        .status-pending {
            background: #fff3cd;
            color: var(--warning-color);
        }

        .status-contacted {
            background: #d1ecf1;
            color: #17a2b8;
        }

        .status-inactive {
            background: #f8d7da;
            color: var(--danger-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .action-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 12px;
        }

        .action-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 0.95rem;
        }

        .action-desc {
            color: var(--light-dark-color);
            font-size: 0.8rem;
            margin: 0;
        }

        /* Chart Container */
        .chart-container {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        /* Recent Activity */
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-title {
            font-weight: 600;
            margin: 0 0 3px 0;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-desc {
            color: var(--light-dark-color);
            margin: 0;
            font-size: 0.8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-time {
            color: var(--light-dark-color);
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .top-nav {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
                min-width: auto;
            }

            .user-menu {
                justify-content: space-between;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr 1fr;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .table-responsive {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .user-info {
                text-align: left;
            }

            .activity-item {
                flex-wrap: wrap;
            }

            .activity-time {
                width: 100%;
                margin-top: 5px;
            }
        }

        /* Sidebar divider */
        .nav-divider {
            padding: 8px 16px;
            margin: 10px 0 5px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .nav-divider-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--light-dark-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Store badge */
        .store-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            margin-top: 2px;
        }

        /* Store info styles */
        .store-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .store-logo-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .store-description {
            line-height: 1.4;
        }

        .store-stats {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }

        .store-stat {
            text-align: center;
            flex: 1;
        }

        .store-stat strong {
            display: block;
            font-size: 1.1rem;
            color: var(--dark-color);
        }

        .store-stat span {
            font-size: 0.75rem;
            color: var(--light-dark-color);
        }

        /* Alert customization */
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .nav-divider {
                padding: 6px 12px;
            }

            .store-stats {
                flex-direction: column;
                gap: 10px;
            }

            .store-stat {
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation to action cards
            document.querySelectorAll('.action-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Auto-dismiss alert after 10 seconds
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 10000);
            }
        });
    </script>
@endpush

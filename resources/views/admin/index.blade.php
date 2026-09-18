@extends('layout.marketer')

@section('title', 'Admin Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class="welcome-card text-white mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="card-title mb-2">Welcome back, {{ auth()->user()->first_name }}! 👋</h2>
                    <p class="card-text mb-0">Here's what's happening with your platform today.</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="date-time">
                        <h4 class="mb-1" id="current-time"></h4>
                        <p class="mb-0" id="current-date"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white rounded-circle me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h4 class="mb-0">{{ number_format($stats['totalUsers']) }}</h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="text-primary small">View all users →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success text-white rounded-circle me-3">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Active Vendors</h6>
                            <h4 class="mb-0">{{ number_format($stats['totalVendors']) }}</h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.vendors.index') }}" class="text-success small">Manage vendors →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white rounded-circle me-3">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Customers</h6>
                            <h4 class="mb-0">{{ number_format($stats['totalCustomers']) }}</h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.customers.index') }}" class="text-warning small">View customers →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info text-white rounded-circle me-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h4 class="mb-0">₦{{ number_format($stats['totalTransactions'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.transactions.index') }}" class="text-info small">View transactions →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger text-white rounded-circle me-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pending Products</h6>
                            <h4 class="mb-0">{{ number_format($stats['pendingProducts']) }}</h4>
                        </div>
                    </div>
                    {{-- <div class="mt-3">
                        <a href="{{ route('admin.products.index') }}" class="text-danger small">Review products →</a>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-purple text-white rounded-circle me-3">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Marketers</h6>
                            <h4 class="mb-0">{{ number_format($stats['totalMarketers']) }}</h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index', ['type' => 'marketer']) }}" class="text-purple small">Manage
                            marketers →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activities -->
    <div class="row mb-4">
        <!-- Recent Users -->
        <div class="col-xl-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">Recent Users</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profile_image ? url($user->profile_image) : asset('images/default-avatar.png') }}"
                                                    alt="{{ $user->first_name }}" class="rounded-circle me-2"
                                                    width="30" height="30">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $user->user_type === 'admin' ? 'danger' : ($user->user_type === 'vendor' ? 'success' : ($user->user_type === 'marketer' ? 'purple' : 'warning')) }}">
                                                {{ ucfirst($user->user_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.users.create') }}" class="quick-action-card">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <div class="quick-action-icon bg-primary text-white mb-3">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <h6 class="mb-0">Add New User</h6>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.tasks.create') }}" class="quick-action-card">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <div class="quick-action-icon bg-warning text-white mb-3">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                        <h6 class="mb-0">Assign Task</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3 d-none">
                            <a href="{{ route('admin.transactions.index') }}" class="quick-action-card">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <div class="quick-action-icon bg-info text-white mb-3">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <h6 class="mb-0">View Reports</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .quick-action-card {
            text-decoration: none;
            color: inherit;
        }

        .quick-action-card:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .quick-action-card .card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 1.8rem;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .date-time h4 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .date-time p {
            opacity: 0.8;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Update current time and date
        function updateDateTime() {
            const now = new Date();

            // Format time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', timeOptions);

            // Format date
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        // Update every second
        setInterval(updateDateTime, 1000);

        // Initial call
        updateDateTime();
    </script>
@endpush

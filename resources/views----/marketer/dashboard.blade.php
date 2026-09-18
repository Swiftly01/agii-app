@extends('layout.marketer')

@section('title', 'Dashboard - Agii Marketer')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class="welcome-card text-white">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="card-title mb-2">Welcome back, {{ auth()->user()->first_name }}! 👋</h2>
                    <p class="card-text mb-0 opacity-90">Here's your marketing performance overview</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="referral-badge">
                        <strong class="text-secondary">Your Referral Code:</strong>
                        <span class="text-primary fw-bold">{{ $marketer->referral_code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card stats-card-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $stats['total_vendors'] }}</div>
                            <div class="stats-label">Total Vendors</div>
                        </div>
                        <div class="stats-icon stats-icon-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card stats-card-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $stats['active_vendors'] }}</div>
                            <div class="stats-label">Active Vendors</div>
                        </div>
                        <div class="stats-icon stats-icon-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card stats-card-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">₦{{ number_format($stats['total_earnings']) }}</div>
                            <div class="stats-label">Total Earnings</div>
                        </div>
                        <div class="stats-icon stats-icon-info">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card stats-card-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">₦{{ number_format($stats['pending_earnings']) }}</div>
                            <div class="stats-label">Pending Earnings</div>
                        </div>
                        <div class="stats-icon stats-icon-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-card">
        <div class="card-header-modern">
            <h5 class="card-title mb-0">Quick Actions</h5>
        </div>
        <div class="card-body-modern">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('marketer.vendors.create') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-user-plus action-icon"></i>
                        <span class="fw-bold">Add New Vendor</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="action-btn action-btn-success" data-bs-toggle="modal"
                        data-bs-target="#shareReferralModal">
                        <i class="fas fa-share-alt action-icon"></i>
                        <span class="fw-bold">Share Referral</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="action-btn action-btn-info" data-bs-toggle="modal"
                        data-bs-target="#withdrawModal">
                        <i class="fas fa-money-bill-wave action-icon"></i>
                        <span class="fw-bold">Withdraw Earnings</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="action-btn action-btn-warning">
                        <i class="fas fa-chart-line action-icon"></i>
                        <span class="fw-bold">View Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Vendors -->
        <div class="col-xl-8 col-lg-7">
            <div class="content-card">
                <div class="card-header-modern d-flex flex-row align-items-center justify-content-between">
                    <h6 class="card-title mb-0">Recent Vendors</h6>
                    <a href="{{ route('marketer.vendors.create') }}" class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-plus-circle me-1"></i> Add Vendor
                    </a>
                </div>
                <div class="card-body-modern">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Business</th>
                                    <th>Subscription</th>
                                    <th>Products</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentVendors as $vendor)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    @if ($vendor->profile_image)
                                                        <img src="{{ url($vendor->profile_image) }}"
                                                            alt="{{ $vendor->full_name }}">
                                                    @else
                                                        <i class="fas fa-user"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $vendor->full_name }}</h6>
                                                    <small class="text-muted">{{ $vendor->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $vendor->business_name }}</strong><br>
                                            <small class="text-muted">{{ $vendor->business_category }}</small>
                                        </td>
                                        <td>
                                            @if ($vendor->activeSubscription)
                                                <span class="badge-modern badge-success">Active</span>
                                            @else
                                                <span class="badge-modern badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $vendor->products_count ?? 0 }}</span>
                                            products
                                        </td>
                                        <td>{{ $vendor->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('marketer.vendors.show', $vendor->id) }}"
                                                    class="btn btn-sm btn-outline-modern" title="View Vendor">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('marketer.products.create', $vendor->id) }}"
                                                    class="btn btn-sm btn-outline-modern" title="Add Product">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                @if (!$vendor->activeSubscription)
                                                    <a href="{{ route('marketer.vendors.payment', $vendor->id) }}"
                                                        class="btn btn-sm btn-outline-modern" title="Setup Payment">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-users empty-state-icon"></i>
                                                <h5 class="mt-3 text-muted">No Vendors Yet</h5>
                                                <p class="text-muted">Start by adding your first vendor</p>
                                                <a href="{{ route('marketer.vendors.create') }}"
                                                    class="btn btn-primary-modern">
                                                    Add First Vendor
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Performance Summary -->
            <div class="content-card">
                <div class="card-header-modern">
                    <h6 class="card-title mb-0">Performance Summary</h6>
                </div>
                <div class="card-body-modern">
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-3">
                            <i class="fas fa-circle text-primary me-1"></i> Active Vendors
                        </span>
                        <span class="me-3">
                            <i class="fas fa-circle text-success me-1"></i> Total Earnings
                        </span>
                        <span>
                            <i class="fas fa-circle text-warning me-1"></i> Pending
                        </span>
                    </div>
                </div>
            </div>

            <!-- Referral Information -->
            <div class="content-card">
                <div class="card-header-modern">
                    <h6 class="card-title mb-0">Referral Information</h6>
                </div>
                <div class="card-body-modern">
                    <div class="text-center">
                        <div class="referral-code-box">
                            <div class="referral-code">{{ $marketer->referral_code }}</div>
                            <p class="text-muted small mb-0">Your Unique Referral Code</p>
                        </div>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="text-primary">{{ $stats['total_vendors'] }}</h5>
                                    <small class="text-muted">Total Vendors</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="text-success">{{ $stats['active_vendors'] }}</h5>
                                <small class="text-muted">Active Vendors</small>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-start mb-3">Share Your Referral Link:</h6>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="referralLink"
                                value="{{ url('/register?ref=' . $marketer->referral_code) }}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyReferralLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-modern" onclick="shareOnWhatsApp()">
                                <i class="fab fa-whatsapp me-1"></i> Share on WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Active Vendors', 'Inactive Vendors', 'Pending Commissions'],
                datasets: [{
                    data: [
                        {{ $stats['active_vendors'] }},
                        {{ $stats['total_vendors'] - $stats['active_vendors'] }},
                        {{ $stats['pending_earnings'] > 0 ? 1 : 0 }}
                    ],
                    backgroundColor: ['#94c953', '#ef4444', '#f59e0b'],
                    hoverBackgroundColor: ['#7db437', '#dc2626', '#d97706'],
                    borderWidth: 0,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%',
            },
        });

        // Share on WhatsApp
        function shareOnWhatsApp() {
            const text =
                `Join Agii as a vendor using my referral code: {{ $marketer->referral_code }}\n\nSign up here: {{ url('/register?ref=' . $marketer->referral_code) }}\n\nI'll help you get started and earn commissions!`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }

        // Share on Facebook
        function shareOnFacebook() {
            const url =
                `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent('{{ url('/register?ref=' . $marketer->referral_code) }}')}`;
            window.open(url, '_blank', 'width=600,height=400');
        }
    </script>
@endpush

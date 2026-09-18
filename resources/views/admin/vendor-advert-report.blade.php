@extends('layout.marketer')


@section('title', 'Vendor Advert Report')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>Vendor Advert Report
            <span class="badge bg-{{ $filters['status'] == 'active' ? 'success' : ($filters['status'] == 'inactive' ? 'danger' : 'primary') }} ms-2">
                {{ ucfirst($filters['status']) }}
            </span>
        </h1>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('export.vendor.advert.report', ['status' => 'all']) }}">
                            <i class="fas fa-file-csv me-2"></i>Export All (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('export.vendor.advert.report', ['status' => 'active']) }}">
                            <i class="fas fa-check-circle me-2 text-success"></i>Export Active Only (CSV)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('export.vendor.advert.report', ['status' => 'inactive']) }}">
                            <i class="fas fa-times-circle me-2 text-danger"></i>Export Inactive Only (CSV)
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('vendor.advert.report') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search vendor name, email, or phone..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-control" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="most_ads" {{ request('sort') == 'most_ads' ? 'selected' : '' }}>Most Ads</option>
                        <option value="highest_paid" {{ request('sort') == 'highest_paid' ? 'selected' : '' }}>Highest Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('vendor.advert.report') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Vendors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vendors->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Adverts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $vendorReports->sum('total_active_ads') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Inactive Adverts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $vendorReports->sum('total_inactive_ads') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₦{{ number_format($vendorReports->sum('total_amount_paid'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ $filters['status'] == 'active' ? 'Active' : ($filters['status'] == 'inactive' ? 'Inactive' : 'All') }} Vendors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $vendorReports->where('vendor_status', $filters['status'] == 'all' ? '!=' : '=', $filters['status'] == 'all' ? null : $filters['status'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Report Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table me-2"></i>Vendor Advert Details
                <small class="ms-2">({{ ucfirst($filters['status']) }} Vendors)</small>
            </h6>
            <span class="badge bg-light text-dark">
                Showing {{ $vendors->firstItem() }}-{{ $vendors->lastItem() }} of {{ $vendors->total() }}
            </span>
        </div>
        <div class="card-body">
            @if($vendors->count() > 0)
                @foreach($vendorReports as $report)
                <div class="card mb-4 border-left-{{ $report['vendor_status'] == 'active' ? 'success' : 'danger' }}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-store me-2"></i>
                                {{ $report['vendor']['business_name'] ?: $report['vendor']['full_name'] }}
                                <span class="badge bg-{{ $report['vendor_status'] == 'active' ? 'success' : 'danger' }} ms-2">
                                    {{ ucfirst($report['vendor_status']) }}
                                </span>
                            </h5>
                            <small class="text-muted">
                                Vendor ID: {{ $report['vendor']['id'] }} | 
                                Registered: {{ $report['vendor']['registration_date']->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">
                                {{ $report['total_active_ads'] }} Active
                            </span>
                            <span class="badge bg-danger ms-2">
                                {{ $report['total_inactive_ads'] }} Inactive
                            </span>
                            <span class="badge bg-primary ms-2">
                                Total: ₦{{ number_format($report['total_amount_paid'], 2) }}
                            </span>
                            <a href="{{ route('export.vendor.report', $report['vendor']['id']) }}" 
                               class="btn btn-sm btn-outline-info ms-2"
                               title="Export this vendor's report">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Vendor Contact Info -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Contact Information
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="120"><strong>Email:</strong></td>
                                        <td>
                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                            <a href="mailto:{{ $report['vendor']['email'] }}">
                                                {{ $report['vendor']['email'] }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            {{ $report['vendor']['phone'] ?: 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>WhatsApp:</strong></td>
                                        <td>
                                            <i class="fab fa-whatsapp me-2 text-muted"></i>
                                            {{ $report['vendor']['whatsapp'] ?: 'N/A' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                @if($report['subscription'])
                                <h6 class="text-primary">
                                    <i class="fas fa-id-card me-2"></i>Subscription Details
                                    <span class="badge bg-{{ $report['subscription']['is_active'] ? 'success' : 'danger' }} ms-2">
                                        {{ $report['subscription']['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="120"><strong>Plan:</strong></td>
                                        <td>{{ $report['subscription']['plan_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Duration:</strong></td>
                                        <td>{{ $report['subscription']['duration_days'] }} days</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Paid:</strong></td>
                                        <td>₦{{ number_format($report['subscription']['amount_paid'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Starts:</strong></td>
                                        <td>{{ $report['subscription']['starts_at']->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expires:</strong></td>
                                        <td>
                                          {{ optional($report['subscription']['expires_at'])->format('Y-m-d H:i:s') ?? 'N/A' }}

                                            @if(optional($report['subscription']['expires_at'])->isPast())
                                            <span class="badge bg-danger ms-2">Expired</span>
                                            @else
                                            <span class="badge bg-success ms-2">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                @else
                                <h6 class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>No Active Subscription
                                </h6>
                                <p class="text-muted">This vendor has no active subscription.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Advert Details Table -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-ad me-2"></i>
                            @if($filters['status'] == 'all')
                                All Adverts ({{ $report['products']->count() }})
                            @elseif($filters['status'] == 'active')
                                Active Adverts ({{ $report['total_active_ads'] }})
                            @else
                                Inactive Adverts ({{ $report['total_inactive_ads'] }})
                            @endif
                        </h6>
                        
                        @if($report['products']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Advert ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Created</th>
                                        <th>Duration</th>
                                        <th>Amount Paid</th>
                                        <th>Expiration Date</th>
                                        <th>Days Left</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report['products'] as $advert)
                                    <tr class="{{ !$advert['is_active'] ? 'table-warning' : '' }}">
                                        <td>#{{ $advert['id'] }}</td>
                                        <td>
                                            <a href="{{ route('product.show', $advert['id']) }}" target="_blank">
                                                {{ Str::limit($advert['title'], 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $advert['category'] }}</td>
                                        <td>{{ $advert['created_at']->format('Y-m-d') }}</td>
                                        <td>{{ $advert['duration_days'] }} days</td>
                                        <td>₦{{ number_format($advert['amount_paid'], 2) }}</td>
                                        <td>
                                            {{ $advert['expiration_date'] ? $advert['expiration_date']->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>
                                            @if($advert['days_until_expiry'] !== null)
                                                @if($advert['days_until_expiry'] > 0)
                                                <span class="badge bg-success">{{ $advert['days_until_expiry'] }} days</span>
                                                @elseif($advert['days_until_expiry'] == 0)
                                                <span class="badge bg-warning">Today</span>
                                                @else
                                                <span class="badge bg-danger">{{ abs($advert['days_until_expiry']) }} days ago</span>
                                                @endif
                                            @else
                                            <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($advert['advert_status'] == 'active')
                                                @if($advert['is_expired'])
                                                <span class="badge bg-danger">Expired</span>
                                                @else
                                                <span class="badge bg-success">Active</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">Sub: {{ ucfirst($advert['subscription_status']) }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No adverts found for this vendor with the current filter.
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $vendors->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No vendor adverts found</h4>
                    <p class="text-muted">
                        @if($filters['status'] == 'active')
                        No active vendor adverts found with the current filters.
                        @elseif($filters['status'] == 'inactive')
                        No inactive vendor adverts found with the current filters.
                        @else
                        No vendor adverts found with the current filters.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .border-left-primary { border-left: .25rem solid #4e73df!important; }
    .border-left-success { border-left: .25rem solid #1cc88a!important; }
    .border-left-danger { border-left: .25rem solid #e74a3b!important; }
    .border-left-info { border-left: .25rem solid #36b9cc!important; }
    .border-left-warning { border-left: .25rem solid #f6c23e!important; }
    .table-warning { background-color: rgba(255,193,7,0.1); }
    .badge { font-size: 0.85em; }
</style>

<script>
    // Auto-submit form on select change
    document.addEventListener('DOMContentLoaded', function() {
        const selectElements = document.querySelectorAll('select[onchange*="submit"]');
        selectElements.forEach(select => {
            select.removeAttribute('onchange');
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
</script>
@endsection
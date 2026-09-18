@extends('layouts.admin')

@section('title', 'Vendor Advert Report')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>Vendor Advert Report
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('export.vendor.advert.report') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export CSV
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('vendor.advert.report') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search vendor name, email, or phone..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-control">
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
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Active Vendors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vendors->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Active Adverts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $vendorReports->sum('total_active_ads') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ad fa-2x text-gray-300"></i>
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
    </div>

    <!-- Main Report Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table me-2"></i>Vendor Advert Details
            </h6>
            <span class="badge bg-light text-dark">
                Showing {{ $vendors->firstItem() }}-{{ $vendors->lastItem() }} of {{ $vendors->total() }}
            </span>
        </div>
        <div class="card-body">
            @if($vendors->count() > 0)
                @foreach($vendorReports as $report)
                <div class="card mb-4 border-left-info">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-store me-2"></i>
                                {{ $report['vendor']['business_name'] ?: $report['vendor']['full_name'] }}
                            </h5>
                            <small class="text-muted">
                                Vendor ID: {{ $report['vendor']['id'] }} | 
                                Registered: {{ $report['vendor']['registration_date'] }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">
                                {{ $report['total_active_ads'] }} Active Ad(s)
                            </span>
                            <span class="badge bg-primary ms-2">
                                Total Paid: ₦{{ number_format($report['total_amount_paid'], 2) }}
                            </span>
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
                                            {{ $report['vendor']['email'] }}
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
                                        <td><strong>Expires:</strong></td>
                                        <td>
                                            {{ $report['subscription']['expires_at'] }}
                                            @if(now()->gt(\Carbon\Carbon::parse($report['subscription']['expires_at'])))
                                            <span class="badge bg-danger ms-2">Expired</span>
                                            @else
                                            <span class="badge bg-success ms-2">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                @endif
                            </div>
                        </div>

                        <!-- Advert Details Table -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-ad me-2"></i>Active Adverts ({{ $report['products']->count() }})
                        </h6>
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
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report['products'] as $advert)
                                    <tr>
                                        <td>#{{ $advert['id'] }}</td>
                                        <td>
                                            <a href="{{ route('products.show', $advert['id']) }}" target="_blank">
                                                {{ Str::limit($advert['title'], 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $advert['category'] }}</td>
                                        <td>{{ $advert['created_at'] }}</td>
                                        <td>{{ $advert['duration_days'] }} days</td>
                                        <td>₦{{ number_format($advert['amount_paid'], 2) }}</td>
                                        <td>
                                            {{ $advert['expiration_date'] }}
                                            @if($advert['is_expired'])
                                            <span class="badge bg-danger">Expired</span>
                                            @else
                                            <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($advert['is_expired'])
                                            <span class="badge bg-warning">Requires Renewal</span>
                                            @else
                                            @php
                                                $expiresIn = \Carbon\Carbon::parse($advert['expiration_date'])->diffInDays(now());
                                            @endphp
                                            @if($expiresIn <= 7)
                                            <span class="badge bg-warning">Expires in {{ $expiresIn }} days</span>
                                            @else
                                            <span class="badge bg-success">Active</span>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                    <p class="text-muted">There are currently no active vendor adverts to display.</p>
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
    .border-left-primary {
        border-left: .25rem solid #4e73df!important;
    }
    .border-left-success {
        border-left: .25rem solid #1cc88a!important;
    }
    .border-left-info {
        border-left: .25rem solid #36b9cc!important;
    }
</style>
@endsection
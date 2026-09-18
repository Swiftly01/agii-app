@extends('layout.marketer')

@section('title', 'Manage Vendors - Agii')
@section('page-title', 'Vendors Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="card-title mb-0">All Customer</h6>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="input-group me-3" style="max-width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search vendors..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form id="filterForm" method="GET" action="{{ route('admin.vendors.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" placeholder="From Date"
                            value="{{ request('date_from') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" placeholder="To Date"
                            value="{{ request('date_to') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>
                </div>
                <input type="hidden" name="search" id="searchHidden" value="{{ request('search') }}">
            </form>
        </div>

        <div class="card-body">


            @if ($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Business</th>
                                <th>Contact</th>

                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $vendor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $vendor->profile_image ? asset($vendor->profile_image) : asset('images/default-avatar.png') }}"
                                                alt="{{ $vendor->first_name }}" class="rounded-circle me-3" width="45"
                                                height="45">
                                            <div>
                                                <h6 class="mb-1">{{ $vendor->first_name }} {{ $vendor->last_name }}</h6>
                                                <small class="text-muted">ID: #{{ $vendor->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $vendor->business_name ?? 'N/A' }}</strong>
                                        @if ($vendor->business_category)
                                            <br><small class="text-muted">{{ $vendor->business_category }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div><i class="fas fa-envelope me-2 text-muted"></i>{{ $vendor->email }}</div>
                                        <div><i class="fas fa-phone me-2 text-muted"></i>{{ $vendor->phone }}</div>
                                        @if ($vendor->city)
                                            <div><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $vendor->city }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $vendor->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $vendor->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                                class="btn btn-sm btn-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.index', ['vendor' => $vendor->id]) }}"
                                                class="btn btn-sm btn-info" title="View Products">
                                                <i class="fas fa-box"></i>
                                            </a>
                                            {{-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editVendorModal{{ $vendor->id }}" title="Edit Vendor">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $vendor->id }})" title="Delete Vendor">
                                                <i class="fas fa-trash"></i>
                                            </button> --}}
                                        </div>

                                        <!-- Delete Form -->
                                        <form id="deleteForm{{ $vendor->id }}"
                                            action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of
                        {{ $customers->total() }}
                        vendors
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-store fa-3x text-muted"></i>
                    </div>
                    <h4 class="mt-3">No Vendors Found</h4>
                    <p class="text-muted">No vendors match your search criteria.</p>
                    <button class="btn btn-primary mt-2" onclick="resetFilters()">
                        <i class="fas fa-redo me-2"></i>Reset Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@endpush

<!-- resources/views/marketer/vendors/index.blade.php -->
@extends('layout.marketer')
@section('title', 'All Vendors - Agii')

@section('page-title', 'Vendors')

@section('content')


    <!-- Content Area -->
    <div class="content-area">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1">Vendor Management</h2>
                <p class="text-muted mb-0">Manage all your registered vendors</p>
            </div>
            <a href="{{ route('marketer.vendors.create') }}" class="btn btn-primary-modern btn-modern">
                <i class="fas fa-user-plus me-2"></i> Add New Vendor
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-4">
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

            <div class="col-xl-4 col-md-4">
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

            <div class="col-xl-4 col-md-4">
                <div class="stats-card stats-card-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="stats-number">{{ $stats['total_products'] }}</div>
                                <div class="stats-label">Total Products</div>
                            </div>
                            <div class="stats-icon stats-icon-info">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Table -->
        <div class="content-card">
            <div class="card-header-modern d-flex flex-row align-items-center justify-content-between">
                <h6 class="card-title mb-0">All Vendors ({{ $vendors->count() }})</h6>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Search vendors..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <button class="btn btn-outline-modern btn-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern" id="vendorsTable">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Business Info</th>
                                <th>Contact</th>
                                <th>Subscription</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                @if ($vendor->profile_image)
                                                    <img src="{{ asset($vendor->profile_image) }}"
                                                        alt="{{ $vendor->full_name }}">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $vendor->first_name }}
                                                    {{ $vendor->last_name }}</h6>
                                                <small class="text-muted">ID: {{ $vendor->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $vendor->business_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $vendor->business_category ?? 'No category' }}</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="fas fa-envelope me-1"></i> {{ $vendor->email }}</div>
                                            <div><i class="fas fa-phone me-1"></i> {{ $vendor->phone ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($vendor->activeSubscription)
                                            <span class="badge-modern badge-success">Active</span>
                                            <br>
                                            <small
                                                class="text-muted">{{ $vendor->activeSubscription->plan->name ?? 'Plan' }}</small>
                                        @else
                                            <span class="badge-modern badge-danger">No Subscription</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold text-primary">{{ $vendor->products_count ?? 0 }}</span>
                                            <br>
                                            <small class="text-muted">products</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($vendor->is_active)
                                            <span class="badge-modern badge-success">Active</span>
                                        @else
                                            <span class="badge-modern badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $vendor->created_at->format('M j, Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $vendor->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('marketer.vendors.show', $vendor->id) }}"
                                                class="btn btn-sm btn-outline-modern" title="View Details">
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
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-users empty-state-icon"></i>
                                            <h5 class="mt-3 text-muted">No Vendors Found</h5>
                                            <p class="text-muted mb-4">
                                                @if (auth()->user()->user_type === 'marketer')
                                                    You haven't referred any vendors yet.
                                                @else
                                                    No vendors are registered in the system.
                                                @endif
                                            </p>
                                            <a href="{{ route('marketer.vendors.create') }}"
                                                class="btn btn-primary-modern">
                                                <i class="fas fa-user-plus me-2"></i> Add Your First Vendor
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                <div class="d-flex justify-content-between align-items-center mt-4">


                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const mobileToggle = document.getElementById('mobileToggle');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Change icon based on state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-chevron-left';
            }
        });

        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 992) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnMobileToggle = mobileToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnMobileToggle && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#vendorsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Additional styles for the vendors page */
        .table-modern th:nth-child(1) {
            width: 15%;
        }

        .table-modern th:nth-child(2) {
            width: 15%;
        }

        .table-modern th:nth-child(3) {
            width: 15%;
        }

        .table-modern th:nth-child(4) {
            width: 12%;
        }

        .table-modern th:nth-child(5) {
            width: 8%;
        }

        .table-modern th:nth-child(6) {
            width: 10%;
        }

        .table-modern th:nth-child(7) {
            width: 12%;
        }

        .table-modern th:nth-child(8) {
            width: 13%;
        }
    </style>
@endpush

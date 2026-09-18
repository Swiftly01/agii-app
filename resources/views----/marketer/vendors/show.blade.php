@extends('layout.marketer')

@section('title', 'Vendor Details - Agii Marketer')
@section('page-title', 'Vendor Details')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .page-subtitle {
            color: var(--secondary);
            font-size: 0.95rem;
        }

        .btn-back {
            background: white;
            color: var(--primary);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--card-shadow);
            color: var(--primary);
            text-decoration: none;
        }

        /* Card Styles */
        .card-modern {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-modern:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .card-header-modern {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        /* Badge Styles */
        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-secondary {
            background-color: rgba(107, 114, 128, 0.1);
            color: var(--secondary);
        }

        .badge-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Info Items */
        .info-item {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 500;
            color: var(--dark);
            min-width: 140px;
        }

        .info-value {
            color: var(--secondary);
            flex: 1;
        }

        /* Table Styles */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-modern thead th {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border: none;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-modern tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Button Styles */
        .btn-modern {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .btn-primary-modern {
            background-color: var(--primary);
            border: none;
        }

        .btn-primary-modern:hover {
            background-color: #3a56d4;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }

        .btn-success-modern {
            background-color: var(--success);
            border: none;
        }

        .btn-info-modern {
            background-color: #0ea5e9;
            border: none;
        }

        .btn-outline-modern {
            background-color: transparent;
            border: 1px solid #e5e7eb;
            color: var(--secondary);
        }

        .btn-outline-modern:hover {
            background-color: #f8fafc;
            border-color: #d1d5db;
        }

        /* Icon Styles */
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .icon-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .icon-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* Quick Actions */
        .quick-actions .btn {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }

        /* Stats Card */
        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .info-item {
                flex-direction: column;
            }

            .info-label {
                min-width: auto;
                margin-bottom: 0.25rem;
            }

            .card-body-modern {
                padding: 1.25rem;
            }

            .btn-back {
                margin-top: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header Section -->
    <div class="page-header d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            {{-- <h1 class="page-title mb-1">Vendor Details</h1> --}}
            <p class="page-subtitle mb-0">View and manage vendor information</p>
        </div>
        <a href="{{ route('marketer.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Dashboard
        </a>
    </div>

    <!-- Vendor Information -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Basic Information</h6>
                    <span class="badge badge-modern badge-success">Active Vendor</span>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Name:</span>
                                <span class="info-value">{{ $vendor->first_name }} {{ $vendor->last_name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value">{{ $vendor->email }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Phone:</span>
                                <span class="info-value">{{ $vendor->phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Business Name:</span>
                                <span class="info-value">{{ $vendor->business_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Business Type:</span>
                                <span class="info-value">{{ ucfirst($vendor->business_type) ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Category:</span>
                                <span class="info-value">{{ $vendor->business_category ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Information Card -->
            @if ($vendor->store)
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6 class="card-title mb-0">Store Information</h6>
                    </div>
                    <div class="card-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Store Name:</span>
                                    <span class="info-value">{{ $vendor->store->name ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Description:</span>
                                    <span class="info-value">{{ $vendor->store->description ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value">
                                        <span
                                            class="badge badge-modern {{ $vendor->store->is_active ? 'badge-success' : 'badge-warning' }}">
                                            {{ $vendor->store->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Rating:</span>
                                    <span class="info-value">{{ $vendor->store->rating ?? 'No ratings yet' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Products Section -->
            <div class="card card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Products ({{ $vendor->products->count() }})</h6>
                </div>
                <div class="card-body-modern">
                    @if ($vendor->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern" id="productsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendor->products as $product)
                                        <tr>
                                            <td>{{ $product->title }}</td>
                                            <td>₦{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-modern {{ $product->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $product->status === 'active' ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('marketer.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-modern">
                                                    <i class="fas fa-eye"></i> View/Edit
                                                </a>

                                                @if ($product->status === 'pending')
                                                    <button class="btn btn-sm btn-outline-success approve-product"
                                                        data-product-id="{{ $product->id }}" title="Approve Product">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger disapprove-product"
                                                        data-product-id="{{ $product->id }}" title="Disapprove Product">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($product->status === 'active')
                                                    <button class="btn btn-sm btn-outline-danger disapprove-product"
                                                        data-product-id="{{ $product->id }}" title="Disapprove Product">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-success approve-product"
                                                        data-product-id="{{ $product->id }}" title="Approve Product">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif





                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>No products found for this vendor.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Subscription Status Card -->
            @if ($vendor->activeSubscription)
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6 class="card-title mb-0">Subscription Status</h6>
                    </div>
                    <div class="card-body-modern">
                        <div class="stats-card">
                            <div class="icon-circle icon-primary">
                                <i class="fas fa-crown fa-lg"></i>
                            </div>
                            <h5 class="stats-number">
                                {{ $vendor->activeSubscription?->paymentPlan?->name ?? 'Unknown Plan' }}
                            </h5>
                            <p class="stats-label">Active Plan</p>

                            <div class="mt-3">
                                <div class="info-item">
                                    <span class="info-label">Expires:</span>
                                    <span
                                        class="info-value">{{ $vendor->activeSubscription->expires_at->format('M d, Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value">
                                        <span
                                            class="badge badge-modern {{ $vendor->activeSubscription->isActive() ? 'badge-success' : 'badge-danger' }}">
                                            {{ $vendor->activeSubscription->isActive() ? 'Active' : 'Expired' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6 class="card-title mb-0">Subscription Status</h6>
                    </div>
                    <div class="card-body-modern">
                        <div class="stats-card">
                            <div class="icon-circle icon-warning">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                            <p class="stats-label">No active subscription</p>
                            <a href="{{ route('marketer.vendors.payment', $vendor->id) }}"
                                class="btn btn-primary-modern btn-modern mt-2">
                                <i class="fas fa-credit-card me-1"></i> Setup Payment
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Information Card -->
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h6 class="card-title mb-0">Location Information</h6>
                </div>
                <div class="card-body-modern">
                    <div class="info-item">
                        <span class="info-label">State:</span>
                        <span class="info-value">{{ $vendor->state ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">LGA:</span>
                        <span class="info-value">{{ $vendor->local_government ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">City:</span>
                        <span class="info-value">{{ $vendor->city ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $vendor->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body-modern">
                    <div class="quick-actions">
                        <a href="mailto:{{ $vendor->email }}" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-envelope fa-fw"></i> Send Email
                        </a>
                        @if ($vendor->phone)
                            <a href="tel:{{ $vendor->phone }}" class="btn btn-success-modern btn-modern">
                                <i class="fas fa-phone fa-fw"></i> Call Vendor
                            </a>
                        @endif
                        <button class="btn btn-info-modern btn-modern" onclick="generateReport()">
                            <i class="fas fa-chart-bar fa-fw"></i> Generate Report
                        </button>
                        <a href="{{ route('marketer.products.create', $vendor->id) }}"
                            class="btn btn-warning btn-modern">
                            <i class="fas fa-plus fa-fw"></i> Add Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function generateReport() {
            // Implement report generation logic
            alert('Report generation feature coming soon!');
        }

        // Initialize DataTable for products
        $(document).ready(function() {
            $('#productsTable').DataTable({
                "pageLength": 5,
                "ordering": true,
                "searching": true,
                "language": {
                    "search": "Search products:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ products",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });

            // Approve product
            document.querySelectorAll('.approve-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    approveProduct(productId);
                });
            });

            // Disapprove product
            document.querySelectorAll('.disapprove-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    disapproveProduct(productId);
                });
            });


            function approveProduct(productId) {
                if (!confirm('Are you sure you want to approve this product?')) {
                    return;
                }

                fetch(`/marketer/products/${productId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Reload page after 1 second to reflect changes
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification(data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'danger');
                    });
            }

            function disapproveProduct(productId) {
                if (!confirm('Are you sure you want to disapprove this product?')) {
                    return;
                }

                fetch(`/marketer/products/${productId}/disapprove`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Reload page after 1 second to reflect changes
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification(data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'danger');
                    });
            }

        });
    </script>
@endpush

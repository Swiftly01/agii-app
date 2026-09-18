@extends('layout.marketer')

@section('title', $vendor->business_name . ' - Vendor Details - Agii')
@section('page-title', 'Vendor Details')

@section('content')
    <div class="row">
        <!-- Vendor Info -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="vendor-avatar mb-3">
                        <img src="{{ $vendor->profile_image ? asset($vendor->profile_image) : asset('images/default-avatar.png') }}"
                            alt="{{ $vendor->first_name }}" class="rounded-circle" width="120" height="120">
                    </div>
                    <h4>{{ $vendor->first_name }} {{ $vendor->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $vendor->user_type }}</p>

                    <div class="vendor-contact mb-4">
                        <div class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            {{ $vendor->email }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            {{ $vendor->phone }}
                        </div>
                        @if ($vendor->city)
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $vendor->city }}, {{ $vendor->state }}
                            </div>
                        @endif
                        @if ($vendor->whatsapp_number)
                            <div class="mb-2">
                                <i class="fab fa-whatsapp me-2 text-success"></i>
                                {{ $vendor->whatsapp_number }}
                            </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $vendor->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        @if ($vendor->phone)
                            <a href="tel:{{ $vendor->phone }}" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>Call Vendor
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Info Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Business Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Business Name:</strong>
                        <p class="mb-0">{{ $vendor->business_name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Business Category:</strong>
                        <p class="mb-0">{{ $vendor->business_category ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Business Type:</strong>
                        <p class="mb-0">{{ $vendor->business_type ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $vendor->address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong>Social Media:</strong>
                        <div class="mt-2">
                            @if ($vendor->facebook_url)
                                <a href="{{ $vendor->facebook_url }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            @endif
                            @if ($vendor->instagram_url)
                                <a href="{{ $vendor->instagram_url }}" target="_blank"
                                    class="btn btn-sm btn-outline-danger me-2">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if ($vendor->twitter_url)
                                <a href="{{ $vendor->twitter_url }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Stats & Products -->
        <div class="col-lg-8">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ $vendorStats['total_products'] }}</h2>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ $vendorStats['active_products'] }}</h2>
                            <p class="text-muted mb-0">Active Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ $vendorStats['pending_products'] }}</h2>
                            <p class="text-muted mb-0">Pending Approval</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ number_format($vendorStats['total_views']) }}</h2>
                            <p class="text-muted mb-0">Total Views</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ $vendorStats['total_sales'] }}</h2>
                            <p class="text-muted mb-0">Sold Items</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <h2 class="mb-1">{{ number_format($vendorStats['average_rating'], 1) }}</h2>
                            <p class="text-muted mb-0">Avg. Rating</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Vendor Products ({{ $vendor->products->count() }})</h6>
                        </div>
                        {{-- <div class="col-md-6 text-end">
                            <a href="{{ route('admin.products.create', ['vendor_id' => $vendor->id]) }}"
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body">
                    @if ($vendor->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">

                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendor->products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (!empty($product->images))
                                                        @php
                                                           
                                                            $images = $product->images; // already an array
                                                            $firstImage =
                                                                is_array($images) && count($images) > 0
                                                                    ? $images[0]
                                                                    : null;
                                                        @endphp
                                                        @if ($firstImage)
                                                            <img src="{{ $firstImage }}" alt="{{ $product->title }}"
                                                                class="rounded me-3" width="50" height="50"
                                                                style="object-fit: cover;">
                                                        @endif
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ Str::limit($product->title, 40) }}</h6>
                                                        <small class="text-muted">ID: #{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $product->category->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <strong>₦{{ number_format($product->price, 2) }}</strong>
                                                @if ($product->old_price)
                                                    <br><small
                                                        class="text-danger"><s>₦{{ number_format($product->old_price, 2) }}</s></small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'pending' => 'warning',
                                                        'inactive' => 'secondary',
                                                        'rejected' => 'danger',
                                                        'sold' => 'info',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusColors[$product->status] ?? 'secondary' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ number_format($product->views) }}
                                            </td>
                                            <td>
                                                {{ $product->created_at->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary"
                                                        title="View Product">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-warning approve-product-btn"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-title="{{ $product->title }}"
                                                        data-current-status="{{ $product->status }}"
                                                        title="Change Status">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <a href="{{ route('admin.products.edit', $product->slug) }}"
                                                        class="btn btn-sm btn-outline-info" title="Edit Product">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state-icon">
                                <i class="fas fa-box-open fa-3x text-muted"></i>
                            </div>
                            <h4 class="mt-3">No Products Found</h4>
                            <p class="text-muted">This vendor hasn't listed any products yet.</p>
                            {{-- <a href="{{ route('admin.products.create', ['vendor_id' => $vendor->id]) }}"
                                class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i>Add First Product
                            </a> --}}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Vendors List
                </a>
                <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Vendor
                </a>
            </div>
        </div>
    </div>

    <!-- Approve Product Modal -->
    <div class="modal fade" id="approveProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Product Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveProductForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Product: <strong id="productTitle"></strong></p>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3" id="rejectionReasonContainer" style="display: none;">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3"
                                placeholder="Please provide a reason for rejection..." maxlength="500"></textarea>
                            <small class="text-muted">This will be shown to the vendor.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .vendor-avatar img {
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .empty-state-icon {
            opacity: 0.5;
        }
    </style>
@endpush

@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', () => {

    // Approve product modal buttons
    document.querySelectorAll('.approve-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productTitle = this.dataset.productTitle;
            const currentStatus = this.dataset.currentStatus;

            // Set modal values safely
            const titleEl = document.getElementById('productTitle');
            if (titleEl) titleEl.textContent = productTitle;

            const statusEl = document.getElementById('status');
            if (statusEl) statusEl.value = currentStatus;

            // Update form action
            const form = document.getElementById('approveProductForm');
            if (form) form.action = `/admin/products/${productId}/approve`;

            // Show/hide rejection reason
            const rejectionContainer = document.getElementById('rejectionReasonContainer');
            if (rejectionContainer) {
                rejectionContainer.style.display = (currentStatus === 'rejected') ? 'block' : 'none';
            }

            // Show modal
            const modalEl = document.getElementById('approveProductModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    });

    // Show/hide rejection reason on status change
    const statusInput = document.getElementById('status');
    if (statusInput) {
        statusInput.addEventListener('change', function() {
            const rejectionContainer = document.getElementById('rejectionReasonContainer');
            if (rejectionContainer) {
                rejectionContainer.style.display = (this.value === 'rejected') ? 'block' : 'none';
            }
        });
    }

    // Handle form submission
    const approveForm = document.getElementById('approveProductForm');
    if (approveForm) {
        approveForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Get CSRF token safely
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.content : '';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Close modal
                    const modalEl = document.getElementById('approveProductModal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }

                    alert('Product status updated successfully!');
                    location.reload();
                } else {
                    alert(result.message || 'Error updating product status.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    }

});
</script>

@endpush

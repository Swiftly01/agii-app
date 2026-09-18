@extends('layout.marketer')

@section('title', 'Pending Products - Agii')
@section('page-title', 'Advert')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    @if (str_contains(url()->current(), 'getpendingproduct'))
                        <h6 class="card-title mb-0">Advert Pending Approval ({{ $products->total() }})</h6>
                    @else
                        <h6 class="card-title mb-0"> All Advert ({{ $products->total() }})</h6>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="input-group me-3" style="max-width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search products..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button class="btn btn-success me-2" id="bulkApproveBtn" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i>Approve Selected
                        </button>
                        <button class="btn btn-danger me-2" id="bulkRejectBtn" style="display: none;">
                            <i class="fas fa-times-circle me-2"></i>Reject Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form id="filterForm" method="GET" action="{{ route('admin.products.pending') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
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


            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Product</th>
                                <th>Vendor</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input product-checkbox" type="checkbox"
                                                value="{{ $product->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (!empty($product->images))
                                                @php
                                                    $images = is_string($product->images)
                                                        ? json_decode($product->images, true)
                                                        : $product->images;

                                                    $firstImage =
                                                        is_array($images) && count($images) > 0 ? $images[0] : null;
                                                @endphp

                                                @if ($firstImage)
                                                    <img src="{{ $firstImage }}" alt="{{ $product->title }}"
                                                        class="rounded me-3" width="60" height="60"
                                                        style="object-fit: cover;">
                                                @endif
                                            @endif

                                            <div>
                                                <h6 class="mb-1">
                                                    {{ Str::limit($product->title, 50) }}
                                                    @if ($product->featured)
                                                        <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star"></i> Featured</span>
                                                    @endif
                                                </h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 70) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($product->user)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->user->profile_image ? asset('storage/' . $product->user->profile_image) : asset('images/default-avatar.png') }}"
                                                    alt="{{ $product->user->first_name }}" class="rounded-circle me-2"
                                                    width="30" height="30">
                                                {{ $product->user->first_name }} {{ $product->user->last_name }}
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->category->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <strong>₦{{ number_format($product->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        {{ $product->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- <a href="{{ route('marketer.products.show', $product->slug) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary" title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </a> --}}
                                            {{-- <button type="button" class="btn btn-sm btn-success approve-btn"
                                                data-product-id="{{ $product->id }}" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button> --}}
                                            {{-- <button type="button" class="btn btn-sm btn-danger reject-btn"
                                                data-product-id="{{ $product->id }}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button> --}}
                                            <a href="{{ route('marketer.products.show', $product->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.products.toggle-featured', $product->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $product->featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    title="{{ $product->featured ? 'Remove from Boosted Listings' : 'Add to Boosted Listings' }}">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>

                                            @if ($product->status != 'active')
                                                <form action="/admin/products/{{ $product->id }}/approve"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">

                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Approve</button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                        products
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h4 class="mt-3">All Products Approved!</h4>
                    <p class="text-muted">There are no products pending approval.</p>
                    <button class="btn btn-primary mt-2" onclick="window.location.href='{{ route('admin.dashboard') }}'">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Product Modal -->
    <div class="modal fade" id="rejectProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectProductForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4"
                                placeholder="Please provide a reason for rejection..." required maxlength="500"></textarea>
                            <small class="text-muted">This will be shown to the vendor.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('searchButton').addEventListener('click', function() {
            document.getElementById('searchHidden').value = document.getElementById('searchInput').value;
            document.getElementById('filterForm').submit();
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchHidden').value = this.value;
                document.getElementById('filterForm').submit();
            }
        });

        // Reset filters
        function resetFilters() {
            window.location.href = "{{ route('admin.products.pending') }}";
        }

        // Bulk selection
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkButtons();
        });

        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleBulkButtons);
        });

        function toggleBulkButtons() {
            const selectedCount = document.querySelectorAll('.product-checkbox:checked').length;
            const bulkApproveBtn = document.getElementById('bulkApproveBtn');
            const bulkRejectBtn = document.getElementById('bulkRejectBtn');

            if (selectedCount > 0) {
                bulkApproveBtn.style.display = 'inline-block';
                bulkRejectBtn.style.display = 'inline-block';
            } else {
                bulkApproveBtn.style.display = 'none';
                bulkRejectBtn.style.display = 'none';
            }
        }

        // Bulk actions
        document.getElementById('bulkApproveBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to approve all selected products?')) {
                const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                    .map(checkbox => checkbox.value);

                bulkAction('approve', selectedIds);
            }
        });

        document.getElementById('bulkRejectBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to reject all selected products?')) {
                const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                    .map(checkbox => checkbox.value);

                bulkAction('reject', selectedIds);
            }
        });

        async function bulkAction(action, productIds) {
            try {
                const response = await fetch('/admin/products/bulk-approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_ids: productIds,
                        action: action
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Error processing bulk action.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        // Individual approve/reject
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;

                if (confirm('Approve this product?')) {
                    approveProduct(productId);
                }
            });
        });

        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;

                // Set form action
                const form = document.getElementById('rejectProductForm');
                form.action = `/admin/products/${productId}/approve`;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('rejectProductModal'));
                modal.show();
            });
        });

        async function approveProduct(productId) {
            try {
                const response = await fetch(`/admin/products/${productId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: 'active'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Product approved successfully!');
                    location.reload();
                } else {
                    alert('Error approving product.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
@endpush

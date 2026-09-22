@extends('layout.layout')
@section('title', 'Customer Dashboard')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Dashboard Header -->
                <div class="dashboard-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="h3 mb-2">Customer Dashboard</h1>
                        <p class="text-muted mb-0">Track your vendor contacts and deal outcomes</p>
                    </div>
                    <a href="{{ route('become-seller') }}" class="btn btn-primary">
                        <i class="fas fa-shop me-1"></i>Become a Seller
                    </a>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-body">
                                <h3>{{ $totalContacts ?? 0 }}</h3>
                                <p>Total Contacts</p>
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-body">
                                <h3>{{ $successfulContacts ?? 0 }}</h3>
                                <p>Successful Deals</p>
                                <div class="stat-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-body">
                                <h3>{{ $successRate ?? 0 }}%</h3>
                                <p>Success Rate</p>
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-card-body">
                                <h3>{{ $pendingFollowup ?? 0 }}</h3>
                                <p>Need Follow-up</p>
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Contacts Table -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vendor Contact History</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                            <i class="fas fa-plus me-2"></i>Add New Contact
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Product</th>
                                        <th>Contact Date</th>
                                        <th>Status</th>
                                        <th>Deal Outcome</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contacts as $contact)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="vendor-avatar me-3">
                                                        {{ substr($contact->vendor_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $contact->vendor_name }}</strong>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $contact->vendor_contact_info }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="product-badge">{{ $contact->product_name }}</span>
                                                <button class="btn btn-sm btn-outline-primary view-product ms-1"
                                                    data-product-id="{{ $contact->product_id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                            <td>{{ $contact->contact_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="status-badge status-{{ $contact->status }}">
                                                    {{ ucfirst($contact->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($contact->deal_outcome)
                                                    <span class="outcome-badge outcome-{{ $contact->deal_outcome }}">
                                                        {{ ucfirst($contact->deal_outcome) }}
                                                    </span>
                                                    @if ($contact->deal_value)
                                                        <br><small
                                                            class="text-muted">${{ number_format($contact->deal_value, 2) }}</small>
                                                    @endif
                                                @else
                                                    <button class="btn btn-sm btn-outline-warning report-outcome"
                                                        data-contact-id="{{ $contact->id }}">
                                                        <i class="fas fa-flag me-1"></i>Report Outcome
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-secondary view-contact"
                                                        data-contact-id="{{ $contact->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary edit-contact"
                                                        data-contact-id="{{ $contact->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p>No vendor contacts yet. Start by adding your first contact!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Success Metrics -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Deal Outcomes Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="success-metrics">
                                    <div class="metric-item">
                                        <span class="metric-label">Total Contacts Made</span>
                                        <span class="metric-value">{{ $totalContacts ?? 0 }}</span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-label">Successful Deals</span>
                                        <span class="metric-value text-success">{{ $successfulContacts ?? 0 }}</span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-label">Pending Outcomes</span>
                                        <span class="metric-value text-warning">{{ $pendingOutcomes ?? 0 }}</span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-label">Unsuccessful Deals</span>
                                        <span class="metric-value text-danger">{{ $unsuccessfulContacts ?? 0 }}</span>
                                    </div>
                                    <div class="progress mt-3" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $successRate ?? 0 }}%">
                                            Success: {{ $successRate ?? 0 }}%
                                        </div>
                                        <div class="progress-bar bg-warning" style="width: {{ $pendingRate ?? 0 }}%">
                                            Pending: {{ $pendingRate ?? 0 }}%
                                        </div>
                                        <div class="progress-bar bg-danger" style="width: {{ $unsuccessfulRate ?? 0 }}%">
                                            Unsuccessful: {{ $unsuccessfulRate ?? 0 }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Activity</h5>
                                <span class="badge bg-primary">{{ $recentActivities->count() }}</span>
                            </div>
                            <div class="card-body">
                                <div class="activity-feed">
                                    @forelse($recentActivities as $activity)
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <i class="fas fa-{{ $activity['icon'] }}"></i>
                                            </div>
                                            <div class="activity-content">
                                                <p class="mb-1">{{ $activity['description'] }}</p>
                                                <small class="text-muted">{{ $activity['time'] }}</small>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No recent activity</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Contact Modal -->
    <div class="modal fade" id="addContactModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vendor Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addContactForm" method="POST" action="{{ route('contacts.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor_search" class="form-label">Search Vendor</label>
                                    <input type="text" class="form-control" id="vendor_search"
                                        placeholder="Type vendor name or business...">
                                    <div id="vendor_results" class="mt-2"
                                        style="max-height: 200px; overflow-y: auto; display: none;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Selected Vendor</label>
                                    <select class="form-select" id="vendor_id" name="vendor_id" required>
                                        <option value="">Select a vendor...</option>
                                        @foreach ($recentProducts->unique('user_id') as $product)
                                            <option value="{{ $product->user->id }}"
                                                data-vendor-name="{{ $product->user->business_name ?: $product->user->full_name }}">
                                                {{ $product->user->business_name ?: $product->user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select class="form-select" id="product_id" name="product_id" required>
                                        <option value="">Select a product...</option>
                                        @foreach ($recentProducts as $product)
                                            <option value="{{ $product->id }}"
                                                data-vendor-id="{{ $product->user_id }}">
                                                {{ $product->title }} - ${{ number_format($product->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_method" class="form-label">Contact Method</label>
                                    <select class="form-select" id="contact_method" name="contact_method" required>
                                        <option value="">Select method...</option>
                                        <option value="website">Website Contact</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Phone</option>
                                        <option value="whatsapp">WhatsApp</option>
                                        <option value="in_person">In Person</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_date" class="form-label">Contact Date</label>
                                    <input type="datetime-local" class="form-control" id="contact_date"
                                        name="contact_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Any additional notes about this contact..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Report Outcome Modal -->
    <div class="modal fade" id="reportOutcomeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Deal Outcome</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="outcomeForm" method="POST" action="{{ route('contacts.update-outcome') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="contact_id" id="outcome_contact_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="deal_outcome" class="form-label">Deal Outcome</label>
                            <select class="form-select" id="deal_outcome" name="deal_outcome" required>
                                <option value="">Select outcome...</option>
                                <option value="successful">✅ Successful - Deal completed</option>
                                <option value="unsuccessful">❌ Unsuccessful - Deal didn't go through</option>
                                <option value="negotiating">🤝 Still negotiating</option>
                                <option value="cancelled">🚫 Cancelled</option>
                                <option value="pending_payment">💳 Pending payment</option>
                                <option value="delivered">📦 Product delivered</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deal_value" class="form-label">Deal Value (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="deal_value" name="deal_value"
                                    step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-text">Approximate value of the deal if successful</div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="outcome_notes" name="notes" rows="3"
                                placeholder="Any additional details about the deal..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Would you recommend this vendor?</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="vendor_rating" id="rating_positive"
                                    value="positive">
                                <label class="btn btn-outline-success" for="rating_positive">👍 Yes</label>

                                <input type="radio" class="btn-check" name="vendor_rating" id="rating_neutral"
                                    value="neutral">
                                <label class="btn btn-outline-warning" for="rating_neutral">😐 Neutral</label>

                                <input type="radio" class="btn-check" name="vendor_rating" id="rating_negative"
                                    value="negative">
                                <label class="btn btn-outline-danger" for="rating_negative">👎 No</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Outcome</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="productDetails">
                    <!-- Product details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Your existing CSS styles from previous response */
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

        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-card-body {
            padding: 1.5rem;
            position: relative;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: var(--light-dark-color);
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .stat-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .vendor-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .product-badge {
            background: var(--primary-light);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge,
        .outcome-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending,
        .outcome-negotiating,
        .outcome-pending_payment {
            background: #fff3cd;
            color: #856404;
        }

        .status-contacted,
        .outcome-negotiating {
            background: #cce7ff;
            color: #004085;
        }

        .status-completed,
        .outcome-successful,
        .outcome-delivered {
            background: var(--primary-light);
            color: var(--success-color);
        }

        .status-failed,
        .outcome-unsuccessful,
        .outcome-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .success-metrics .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .metric-item:last-child {
            border-bottom: none;
        }

        .metric-label {
            color: var(--light-dark-color);
        }

        .metric-value {
            font-weight: 600;
            color: var(--dark-color);
        }

        .activity-feed .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .activity-content p {
            margin-bottom: 0.25rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .table-hover tbody tr:hover {
            background-color: var(--primary-light);
        }

        .progress {
            background: var(--light-grey-color);
        }

        .progress-bar {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .btn-check:checked+.btn {
            border-color: var(--primary-color);
        }

        /* Vendor search results */
        .vendor-result-item {
            padding: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .vendor-result-item:hover {
            background: var(--primary-light);
        }

        .vendor-result-item:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Report outcome functionality
            document.querySelectorAll('.report-outcome').forEach(button => {
                button.addEventListener('click', function() {
                    const contactId = this.getAttribute('data-contact-id');
                    document.getElementById('outcome_contact_id').value = contactId;

                    const modal = new bootstrap.Modal(document.getElementById(
                        'reportOutcomeModal'));
                    modal.show();
                });
            });

            // View product details
            document.querySelectorAll('.view-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    loadProductDetails(productId);
                });
            });

            // Handle outcome form submission
            document.getElementById('outcomeForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating outcome: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the outcome.');
                    });
            });

            // Handle add contact form submission
            document.getElementById('addContactForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error adding contact: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while adding the contact.');
                    });
            });

            // Vendor search functionality
            const vendorSearch = document.getElementById('vendor_search');
            const vendorResults = document.getElementById('vendor_results');
            const vendorSelect = document.getElementById('vendor_id');
            const productSelect = document.getElementById('product_id');

            vendorSearch.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    vendorResults.style.display = 'none';
                    return;
                }

                // Filter vendors from the select options
                const vendors = Array.from(vendorSelect.options)
                    .filter(option => option.value && option.text.toLowerCase().includes(query
                        .toLowerCase()))
                    .slice(0, 10);

                if (vendors.length > 0) {
                    vendorResults.innerHTML = vendors.map(vendor => `
                        <div class="vendor-result-item" data-vendor-id="${vendor.value}">
                            <strong>${vendor.text}</strong>
                        </div>
                    `).join('');
                    vendorResults.style.display = 'block';
                } else {
                    vendorResults.innerHTML =
                        '<div class="vendor-result-item text-muted">No vendors found</div>';
                    vendorResults.style.display = 'block';
                }
            });

            // Vendor selection from search results
            vendorResults.addEventListener('click', function(e) {
                if (e.target.classList.contains('vendor-result-item')) {
                    const vendorId = e.target.getAttribute('data-vendor-id');
                    vendorSelect.value = vendorId;
                    vendorSearch.value = e.target.textContent.trim();
                    vendorResults.style.display = 'none';

                    // Filter products for selected vendor
                    filterProductsByVendor(vendorId);
                }
            });

            // Vendor selection from dropdown
            vendorSelect.addEventListener('change', function() {
                filterProductsByVendor(this.value);
            });

            function filterProductsByVendor(vendorId) {
                const products = Array.from(productSelect.options);

                products.forEach(option => {
                    if (option.value === '') return;

                    const productVendorId = option.getAttribute('data-vendor-id');
                    if (vendorId === productVendorId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Reset product selection if no vendor selected
                if (!vendorId) {
                    productSelect.value = '';
                    products.forEach(option => {
                        option.style.display = 'block';
                    });
                }
            }

            function loadProductDetails(productId) {
                // Simulate loading product details
                const productDetails = `
                    <div class="product-details">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="https://via.placeholder.com/400x300" class="img-fluid rounded" alt="Product Image">
                            </div>
                            <div class="col-md-6">
                                <h4>Sample Product</h4>
                                <p class="text-muted">Product description goes here. This is a sample product description.</p>
                                <div class="product-info">
                                    <p><strong>Price:</strong> $99.99</p>
                                    <p><strong>Category:</strong> Electronics</p>
                                    <p><strong>Vendor:</strong> Sample Vendor</p>
                                    <p><strong>Rating:</strong> ⭐⭐⭐⭐☆ (4.0)</p>
                                </div>
                                <button class="btn btn-primary mt-3">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('productDetails').innerHTML = productDetails;
                const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
                modal.show();
            }

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!vendorSearch.contains(e.target) && !vendorResults.contains(e.target)) {
                    vendorResults.style.display = 'none';
                }
            });
        });
    </script>
@endpush

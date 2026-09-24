@extends('layout.marketer')

@section('title', $customer->first_name . ' ' . $customer->last_name . ' - Customer Details - Agii')
@section('page-title', 'Customer Details')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $customer->profile_image ? asset($customer->profile_image) : asset('images/default-avatar.png') }}"
                        class="rounded-circle mb-3" width="100" height="100" alt="{{ $customer->first_name }}">
                    <h5 class="mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                    <p class="text-muted mb-3">Customer since {{ $customer->created_at->format('M Y') }}</p>

                    <div class="text-start">
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-muted"></i>{{ $customer->email }}</p>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-muted"></i>{{ $customer->phone ?? 'Not provided' }}</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-muted"></i>
                            {{ $customer->address ?? $customer->city ?? $customer->state ?? 'Not provided' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-0">{{ $stats['total_contacts'] }}</h3>
                            <small class="text-muted">Vendor Contacts</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-0">{{ $stats['deals_closed'] }}</h3>
                            <small class="text-muted">Deals Closed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-0">₦{{ number_format($stats['total_deal_value']) }}</h3>
                            <small class="text-muted">Total Deal Value</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Vendor Contact History</h6>
                </div>
                <div class="card-body">
                    @if ($contacts->isEmpty())
                        <p class="text-muted mb-0">This customer hasn't contacted any vendors yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Vendor</th>
                                        <th>Method</th>
                                        <th>Outcome</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td>{{ $contact->product_name ?? $contact->product->title ?? 'N/A' }}</td>
                                            <td>{{ $contact->vendor_name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-light text-dark">{{ ucfirst($contact->contact_method ?? 'N/A') }}</span></td>
                                            <td>
                                                @if ($contact->deal_outcome === 'successful')
                                                    <span class="badge bg-success">Successful</span>
                                                @elseif ($contact->deal_outcome)
                                                    <span class="badge bg-secondary">{{ ucfirst($contact->deal_outcome) }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ optional($contact->contact_date)->format('M d, Y') ?? $contact->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Customers List
                </a>
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Customer
                </a>
                <button type="button" class="btn btn-danger"
                    onclick="if (confirm('Delete this customer? This action cannot be undone.')) document.getElementById('deleteCustomerForm').submit();">
                    <i class="fas fa-trash me-2"></i>Delete Customer
                </button>
                <form id="deleteCustomerForm" action="{{ route('admin.customers.destroy', $customer->id) }}"
                    method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
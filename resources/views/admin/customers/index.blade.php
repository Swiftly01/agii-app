@extends('layout.marketer')

@section('title', 'Manage Customers - Agii')
@section('page-title', 'Customers Management')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="card-title mb-0">All Customers</h6>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="input-group me-3" style="max-width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search customers..."
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
            <form id="filterForm" method="GET" action="{{ route('admin.customers.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-4">
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
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Vendor Contacts</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $customer->profile_image ? asset($customer->profile_image) : asset('images/default-avatar.png') }}"
                                                alt="{{ $customer->first_name }}" class="rounded-circle me-3" width="45"
                                                height="45">
                                            <div>
                                                <h6 class="mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
                                                <small class="text-muted">ID: #{{ $customer->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><i class="fas fa-envelope me-2 text-muted"></i>{{ $customer->email }}</div>
                                        <div><i class="fas fa-phone me-2 text-muted"></i>{{ $customer->phone ?? 'N/A' }}</div>
                                        @if ($customer->city)
                                            <div><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $customer->city }}</div>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $customer->vendor_contacts_count }}</span></td>
                                    <td>
                                        {{ $customer->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                                                class="btn btn-sm btn-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit Customer">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $customer->id }})" title="Delete Customer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <form id="deleteForm{{ $customer->id }}"
                                            action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST"
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

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of
                        {{ $customers->total() }} customers
                    </div>
                    <div>{{ $customers->links() }}</div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted"></i>
                    <h4 class="mt-3">No Customers Found</h4>
                    <p class="text-muted">No customers match your search criteria.</p>
                    <button class="btn btn-primary mt-2" onclick="resetFilters()">
                        <i class="fas fa-redo me-2"></i>Reset Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function submitSearch() {
            document.getElementById('searchHidden').value = document.getElementById('searchInput').value;
            document.getElementById('filterForm').submit();
        }
        document.getElementById('searchButton').addEventListener('click', submitSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') submitSearch();
        });

        function resetFilters() {
            window.location.href = "{{ route('admin.customers.index') }}";
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
@endpush
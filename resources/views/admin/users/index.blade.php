@extends('layout.marketer')

@section('title', 'Manage Users - Agii')
@section('page-title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="card-title mb-0">All Users ({{ $users->total() }})</h6>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="input-group me-3" style="max-width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search users..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form id="filterForm" method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="marketer" {{ request('type') == 'marketer' ? 'selected' : '' }}>Marketer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Email Verified
                            </option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Email
                                Unverified</option>
                           
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" placeholder="From Date"
                            value="{{ request('date_from') }}" onchange="this.form.submit()">
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
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card-sm bg-primary text-white">
                        <div class="stat-card-sm-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-sm me-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    {{-- <h5 class="mb-0">{{ $users::count() }}</h5> --}}
                                    <small>Total Users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card-sm bg-success text-white">
                        <div class="stat-card-sm-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-sm me-3">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div>
                                    {{-- <h5 class="mb-0">{{ User::where('user_type', 'vendor')->count() }}</h5> --}}
                                    <small>Vendors</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card-sm bg-warning text-white">
                        <div class="stat-card-sm-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-sm me-3">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    {{-- <h5 class="mb-0">{{ User::where('user_type', 'customer')->count() }}</h5> --}}
                                    <small>Customers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card-sm bg-purple text-white">
                        <div class="stat-card-sm-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-sm me-3">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    {{-- <h5 class="mb-0">{{ User::where('user_type', 'marketer')->count() }}</h5> --}}
                                    <small>Marketers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>User</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input user-checkbox" type="checkbox"
                                                value="{{ $user->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}"
                                                alt="{{ $user->first_name }}" class="rounded-circle me-3" width="45"
                                                height="45">
                                            <div>
                                                <h6 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                                <small class="text-muted">ID: #{{ $user->id }}</small>
                                                @if ($user->business_name)
                                                    <br><small class="text-muted">{{ $user->business_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><i class="fas fa-envelope me-2 text-muted"></i>{{ $user->email }}</div>
                                        <div><i class="fas fa-phone me-2 text-muted"></i>{{ $user->phone }}</div>
                                        @if ($user->city)
                                            <div><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $user->city }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $user->user_type === 'admin' ? 'danger' : ($user->user_type === 'vendor' ? 'success' : ($user->user_type === 'marketer' ? 'purple' : 'warning')) }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }} mb-1">
                                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                            </span>
                                            <small class="text-muted">Last login:
                                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $user->created_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="openAssignRoleModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}', '{{ $user->user_type }}')"
                                                title="Assign Role">
                                                <i class="fas fa-user-tag"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="viewUserDetails({{ $user->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $user->id }})" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Quick Actions Dropdown -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle ms-1"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (!$user->email_verified_at)
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="verifyEmail({{ $user->id }})">
                                                            <i class="fas fa-check-circle text-success me-2"></i>Verify
                                                            Email
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="mailto:{{ $user->email }}">
                                                        <i class="fas fa-envelope text-primary me-2"></i>Send Email
                                                    </a>
                                                </li>
                                                @if ($user->user_type == 'vendor')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.vendors.show', $user->id) }}">
                                                            <i class="fas fa-store text-info me-2"></i>View Store
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        onclick="confirmDelete({{ $user->id }})">
                                                        <i class="fas fa-user-slash me-2"></i>Delete User
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Delete Form -->
                                        <form id="deleteForm{{ $user->id }}"
                                            action="{{ route('admin.users.delete', $user->id) }}" method="POST"
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
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-users fa-3x text-muted"></i>
                    </div>
                    <h4 class="mt-3">No Users Found</h4>
                    <p class="text-muted">No users match your search criteria.</p>
                    <button class="btn btn-primary mt-2" onclick="resetFilters()">
                        <i class="fas fa-redo me-2"></i>Reset Filters
                    </button>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success mt-2 ms-2">
                        <i class="fas fa-plus me-2"></i>Add New User
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="card mt-3" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span id="selectedCount">0</span> users selected
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary" onclick="bulkAssignRole()">
                        <i class="fas fa-user-tag me-2"></i>Assign Role
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="bulkVerifyEmail()">
                        <i class="fas fa-check-circle me-2"></i>Verify Email
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash me-2"></i>Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Role Modal -->
    <div class="modal fade" id="assignRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignRoleForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="userInfo" class="mb-3">
                            <!-- User info will be loaded here -->
                        </div>

                        <div class="mb-3">
                            <label for="role_user_type" class="form-label">Select Role *</label>
                            <select name="user_type" id="role_user_type" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin">Administrator</option>
                                <option value="vendor">Vendor</option>
                                <option value="customer">Customer</option>
                                <option value="marketer">Marketer</option>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Role Permissions:</strong>
                            <div id="rolePermissions" class="mt-2 small">
                                Select a role to view permissions.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Assign Role Modal -->
    <div class="modal fade" id="bulkAssignRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Role to Selected Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkAssignRoleForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            You are about to change the role for <span id="bulkUserCount">0</span> selected users.
                        </div>

                        <div class="mb-3">
                            <label for="bulk_role_user_type" class="form-label">Select Role *</label>
                            <select name="user_type" id="bulk_role_user_type" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin">Administrator</option>
                                <option value="vendor">Vendor</option>
                                <option value="customer">Customer</option>
                                <option value="marketer">Marketer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Affected Users:</label>
                            <div id="bulkUsersList" class="border rounded p-2 bg-light"
                                style="max-height: 200px; overflow-y: auto;">
                                <!-- Selected users will be listed here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Role to All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card-sm {
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card-sm-body {
            padding: 0;
        }

        .stat-icon-sm {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-card-sm h5 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stat-card-sm small {
            opacity: 0.9;
            font-size: 0.85rem;
        }

        .empty-state-icon {
            opacity: 0.5;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

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
            window.location.href = "{{ route('admin.users.index') }}";
        }

        // Bulk selection
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
            const bulkActionsCard = document.getElementById('bulkActionsCard');
            const selectedCountSpan = document.getElementById('selectedCount');

            selectedCountSpan.textContent = selectedCount;

            if (selectedCount > 0) {
                bulkActionsCard.style.display = 'block';
            } else {
                bulkActionsCard.style.display = 'none';
            }
        }

        // Individual assign role modal
        function openAssignRoleModal(userId, userName, currentRole) {
            // Set form action
            document.getElementById('assignRoleForm').action = `/admin/users/${userId}/assign-role`;

            // Set user info
            document.getElementById('userInfo').innerHTML = `
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-1">${userName}</h6>
                    <span class="badge bg-${currentRole === 'admin' ? 'danger' : (currentRole === 'vendor' ? 'success' : (currentRole === 'marketer' ? 'purple' : 'warning'))}">
                        Current: ${currentRole.charAt(0).toUpperCase() + currentRole.slice(1)}
                    </span>
                </div>
            </div>
        `;

            // Set current role
            document.getElementById('role_user_type').value = currentRole;

            // Update permissions based on selected role
            updateRolePermissions(currentRole);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('assignRoleModal'));
            modal.show();
        }

        // Bulk assign role
        function bulkAssignRole() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            // Get selected users info
            const selectedUsers = [];
            document.querySelectorAll('.user-checkbox:checked').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const userName = row.querySelector('h6.mb-1').textContent;
                selectedUsers.push({
                    id: checkbox.value,
                    name: userName
                });
            });

            // Update modal content
            document.getElementById('bulkUserCount').textContent = selectedIds.length;
            document.getElementById('bulk_role_user_type').value = '';

            const usersList = document.getElementById('bulkUsersList');
            usersList.innerHTML = '';
            selectedUsers.forEach(user => {
                usersList.innerHTML += `<div class="mb-1">${user.name} (ID: ${user.id})</div>`;
            });

            // Set form action (we'll handle this with AJAX)
            const form = document.getElementById('bulkAssignRoleForm');
            form.onsubmit = function(e) {
                e.preventDefault();
                bulkAssignRoleAction(selectedIds);
            };

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('bulkAssignRoleModal'));
            modal.show();
        }

        async function bulkAssignRoleAction(userIds) {
            const role = document.getElementById('bulk_role_user_type').value;

            if (!role) {
                alert('Please select a role.');
                return;
            }

            try {
                const response = await fetch('/admin/users/bulk-assign-role', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_ids: userIds,
                        user_type: role
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Error assigning roles.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        // Bulk verify email
        async function bulkVerifyEmail() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            if (confirm(`Verify email for ${selectedIds.length} selected users?`)) {
                try {
                    const response = await fetch('/admin/users/bulk-verify-email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            user_ids: selectedIds
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        location.reload();
                    } else {
                        alert('Error verifying emails.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        // Bulk delete
        async function bulkDelete() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            if (confirm(`Delete ${selectedIds.length} selected users? This action cannot be undone.`)) {
                try {
                    const response = await fetch('/admin/users/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            user_ids: selectedIds
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        location.reload();
                    } else {
                        alert('Error deleting users.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        // Individual email verification
        async function verifyEmail(userId) {
            if (confirm('Verify this user\'s email address?')) {
                try {
                    const response = await fetch(`/admin/users/${userId}/verify-email`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Email verified successfully!');
                        location.reload();
                    } else {
                        alert('Error verifying email.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        // Individual delete
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById('deleteForm' + userId).submit();
            }
        }

        // View user details
        function viewUserDetails(userId) {
            window.location.href = `/admin/users/${userId}/show`;
        }

        // Update role permissions description
        document.getElementById('role_user_type').addEventListener('change', function() {
            updateRolePermissions(this.value);
        });

        document.getElementById('bulk_role_user_type').addEventListener('change', function() {
            const role = this.value;
            const permissionsDiv = document.getElementById('rolePermissions');

            const permissions = {
                'admin': 'Full system access, can manage all users, products, and settings.',
                'vendor': 'Can list products, manage inventory, and view sales reports.',
                'customer': 'Can browse and purchase products, view order history.',
                'marketer': 'Can view analytics, manage campaigns, and track leads.'
            };

            permissionsDiv.innerHTML = permissions[role] || 'Select a role to view permissions.';
        });

        function updateRolePermissions(role) {
            const permissionsDiv = document.getElementById('rolePermissions');

            const permissions = {
                'admin': 'Full system access, can manage all users, products, and settings.',
                'vendor': 'Can list products, manage inventory, and view sales reports.',
                'customer': 'Can browse and purchase products, view order history.',
                'marketer': 'Can view analytics, manage campaigns, and track leads.'
            };

            permissionsDiv.innerHTML = permissions[role] || 'Select a role to view permissions.';
        }
    </script>
@endpush

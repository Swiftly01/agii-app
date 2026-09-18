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
                            <!-- Permissions will be loaded here -->
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

<script>
    // Open assign role modal
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

    // Update role permissions description
    document.getElementById('role_user_type').addEventListener('change', function() {
        updateRolePermissions(this.value);
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

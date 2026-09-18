{{-- resources/views/admin/leave/types.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Leave Types</h1>
            <p class="lead">Configure and manage different types of leave</p>
        </div>
    </div>

    <!-- Add New Leave Type Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Leave Type</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.leave.types.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Leave Type Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           placeholder="e.g., Annual Leave" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Code *</label>
                                    <input type="text" class="form-control" id="code" name="code" 
                                           placeholder="e.g., AL" maxlength="10" required>
                                    <small class="form-text text-muted">Unique code (max 10 chars)</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="annual_entitlement" class="form-label">Annual Days *</label>
                                    <input type="number" class="form-control" id="annual_entitlement" 
                                           name="annual_entitlement" min="0" max="365" value="0" required>
                                    <small class="form-text text-muted">Days per year</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                           min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Options</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="carry_forward" name="carry_forward">
                                        <label class="form-check-label" for="carry_forward">
                                            Allow Carry Forward
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_approval" name="requires_approval" checked>
                                        <label class="form-check-label" for="requires_approval">
                                            Requires Approval
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_carry_forward" class="form-label">Max Carry Forward Days</label>
                                    <input type="number" class="form-control" id="max_carry_forward" 
                                           name="max_carry_forward" min="0" placeholder="Leave empty for unlimited">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allowed_days" class="form-label">Allowed Days</label>
                                    <select class="form-select" id="allowed_days" name="allowed_days[]" multiple>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl to select multiple. Leave empty for all weekdays.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="2" placeholder="Brief description of this leave type..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Create Leave Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Types List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Leave Types</h5>
                    <span class="badge bg-primary">{{ $leaveTypes->total() }} Types</span>
                </div>
                <div class="card-body">
                    @if($leaveTypes->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No leave types found. Add your first leave type above.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Annual Days</th>
                                    <th>Carry Forward</th>
                                    <th>Approval</th>
                                    <th>Active</th>
                                    <th>Applications</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveTypes as $type)
                                <tr>
                                    <td>{{ $type->sort_order }}</td>
                                    <td>
                                        <strong>{{ $type->name }}</strong>
                                        @if($type->description)
                                            <br><small class="text-muted">{{ Str::limit($type->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $type->code }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $type->annual_entitlement }} days</span>
                                    </td>
                                    <td>
                                        @if($type->carry_forward)
                                            <span class="badge bg-success">
                                                Yes 
                                                @if($type->max_carry_forward)
                                                    (Max: {{ $type->max_carry_forward }})
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->requires_approval)
                                            <span class="badge bg-warning">Required</span>
                                        @else
                                            <span class="badge bg-success">Auto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $type->leaveApplications->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTypeModal"
                                                    data-id="{{ $type->id }}"
                                                    data-name="{{ $type->name }}"
                                                    data-code="{{ $type->code }}"
                                                    data-annual-entitlement="{{ $type->annual_entitlement }}"
                                                    data-carry-forward="{{ $type->carry_forward }}"
                                                    data-max-carry-forward="{{ $type->max_carry_forward }}"
                                                    data-requires-approval="{{ $type->requires_approval }}"
                                                    data-is-active="{{ $type->is_active }}"
                                                    data-allowed-days="{{ json_encode($type->allowed_days ?? []) }}"
                                                    data-description="{{ $type->description }}"
                                                    data-sort-order="{{ $type->sort_order }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($type->leaveApplications->count() == 0)
                                                <form action="{{ route('admin.leave.types.delete', $type->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Delete this leave type?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-danger" disabled title="Cannot delete - has applications">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                        <div>
                            Showing {{ $leaveTypes->firstItem() }} to {{ $leaveTypes->lastItem() }} 
                            of {{ $leaveTypes->total() }} records
                        </div>
                        {{ $leaveTypes->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Type Modal -->
<div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTypeModalLabel">Edit Leave Type</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.leave.types.update', 0) }}" method="POST" id="editTypeForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="type_id" id="edit_type_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Leave Type Name *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_code" class="form-label">Code *</label>
                                <input type="text" class="form-control" id="edit_code" name="code" maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_annual_entitlement" class="form-label">Annual Days *</label>
                                <input type="number" class="form-control" id="edit_annual_entitlement" 
                                       name="annual_entitlement" min="0" max="365" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_max_carry_forward" class="form-label">Max Carry Forward Days</label>
                                <input type="number" class="form-control" id="edit_max_carry_forward" 
                                       name="max_carry_forward" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_allowed_days" class="form-label">Allowed Days</label>
                                <select class="form-select" id="edit_allowed_days" name="allowed_days[]" multiple>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Options</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_carry_forward" name="carry_forward">
                                            <label class="form-check-label" for="edit_carry_forward">
                                                Allow Carry Forward
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_requires_approval" name="requires_approval">
                                            <label class="form-check-label" for="edit_requires_approval">
                                                Requires Approval
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                                            <label class="form-check-label" for="edit_is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Leave Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Edit Type Modal
    const editModal = document.getElementById('editTypeModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const typeId = button.getAttribute('data-id');
            
            // Update form action URL
            const form = document.getElementById('editTypeForm');
            form.action = form.action.replace('/0', '/' + typeId);
            
            // Set form values
            document.getElementById('edit_type_id').value = typeId;
            document.getElementById('edit_name').value = button.getAttribute('data-name');
            document.getElementById('edit_code').value = button.getAttribute('data-code');
            document.getElementById('edit_annual_entitlement').value = button.getAttribute('data-annual-entitlement');
            document.getElementById('edit_sort_order').value = button.getAttribute('data-sort-order');
            document.getElementById('edit_max_carry_forward').value = button.getAttribute('data-max-carry-forward');
            document.getElementById('edit_description').value = button.getAttribute('data-description');
            
            // Set checkboxes
            document.getElementById('edit_carry_forward').checked = button.getAttribute('data-carry-forward') === '1';
            document.getElementById('edit_requires_approval').checked = button.getAttribute('data-requires-approval') === '1';
            document.getElementById('edit_is_active').checked = button.getAttribute('data-is-active') === '1';
            
            // Set allowed days
            const allowedDays = JSON.parse(button.getAttribute('data-allowed-days') || '[]');
            const allowedDaysSelect = document.getElementById('edit_allowed_days');
            
            // Clear previous selections
            Array.from(allowedDaysSelect.options).forEach(option => {
                option.selected = false;
            });
            
            // Set new selections
            allowedDays.forEach(day => {
                const option = Array.from(allowedDaysSelect.options).find(opt => opt.value === day);
                if (option) {
                    option.selected = true;
                }
            });
        });
    }
</script>
@endpush

@push('styles')
<style>
    .badge {
        font-size: 0.8em;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .form-check {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
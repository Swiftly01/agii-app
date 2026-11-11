{{-- Confirmation modal for deleting one staff member. Needs $member (a StaffProfile). --}}
@php($modalId = request()->routeIs('admin.staff.show') ? 'deleteStaffModal' : 'deleteStaffModal-' . $member->id)
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Delete staff member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    Delete <strong>{{ $member->user?->full_name ?: $member->staff_id }}</strong>
                    ({{ $member->staff_id }})?
                </p>
                <p class="text-muted small mb-0">
                    They will lose access to the staff portal immediately. Their attendance, leave and document
                    records are kept, and they can be hired again later.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, delete</button>
            </div>
        </form>
    </div>
</div>

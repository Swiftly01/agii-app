{{-- Posts on its own so it can sit outside any other form --}}
<form action="{{ route('admin.staff.regenerate-password', $staff->id) }}" method="POST"
    onsubmit="return confirm('Generate a new password and email it to this staff member? The current password will stop working.');">
    @csrf
    <button type="submit" class="btn btn-outline-danger">
        <i class="fas fa-key"></i> Regenerate Password
    </button>
</form>

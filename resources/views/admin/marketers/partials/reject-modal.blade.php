{{-- One shared reject modal, the action url is set per marketer by the script below --}}
<div class="modal fade" id="marketerRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="marketerRejectForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Marketer Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="marketerRejectReason" class="form-label">Reason (emailed to the marketer)</label>
                    <textarea name="reason" id="marketerRejectReason" class="form-control" rows="3" maxlength="1000" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Marketer</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Points the shared reject form at the chosen marketer and opens it
        function openMarketerRejectModal(marketerId) {
            document.getElementById('marketerRejectForm').action = "{{ route('admin.marketers.reject', ['marketer' => '__ID__']) }}".replace('__ID__', marketerId);
            document.getElementById('marketerRejectReason').value = '';
            new bootstrap.Modal(document.getElementById('marketerRejectModal')).show();
        }
    </script>
@endpush

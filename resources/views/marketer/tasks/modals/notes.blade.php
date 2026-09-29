<div class="modal fade" id="quickNotesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quickNotesForm" method="POST" action="#">
                @csrf
                <input type="hidden" id="notesTaskId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Add Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="quickNoteText" class="form-label">Note</label>
                    <textarea id="quickNoteText" name="note" class="form-control" rows="4" required maxlength="1000"
                        placeholder="Write an update or comment about this task..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

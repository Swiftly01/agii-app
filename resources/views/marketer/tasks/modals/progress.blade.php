<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('marketer.tasks.update-progress') }}" method="POST">
                @csrf
                <input type="hidden" id="progressTaskId" name="task_id">
                <div class="modal-header">
                    <h5 class="modal-title">Update Progress</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Progress</label>
                        <input type="range" class="form-range" id="progressSlider" 
                               name="progress" min="0" max="100" step="5" value="0">
                        <div class="d-flex justify-content-between">
                            <small>0%</small>
                            <span id="progressValue" class="fw-bold">0%</span>
                            <small>100%</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Progress</label>
                        <input type="number" id="currentProgress" class="form-control" 
                               name="current_progress" min="0" max="100" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Describe your progress..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Progress</button>
                </div>
            </form>
        </div>
    </div>
</div>
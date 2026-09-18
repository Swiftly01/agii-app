<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Tasks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Export your tasks with current filters applied.</p>
                <div class="row g-3">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" onclick="exportTasks('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="exportTasks('excel')">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="exportTasks('csv')">
                            <i class="fas fa-file-csv me-2"></i>CSV
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-info w-100" onclick="exportTasks('print')">
                            <i class="fas fa-print me-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@extends('layout.marketer')

@section('title', 'Task Details - Agii')
@section('page-title', 'Task Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Task Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-1">{{ $task->title }}</h4>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-{{ 
                                        $task->priority == 'high' ? 'danger' : 
                                        ($task->priority == 'medium' ? 'warning' : 'success')
                                    }}">
                                        <i class="fas fa-flag me-1"></i>{{ ucfirst($task->priority) }} Priority
                                    </span>
                                    <span class="badge bg-{{ 
                                        $task->status == 'completed' ? 'success' : 
                                        ($task->status == 'in_progress' ? 'primary' : 
                                        ($task->is_overdue ? 'danger' : 'warning'))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    @if($task->is_overdue)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('marketer.tasks.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Tasks
                                </a>
                            </div>
                        </div>
                        
                        <!-- Progress Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Progress: {{ $task->progress }}%</h6>
                                <button type="button" 
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updateProgressModal">
                                    <i class="fas fa-sync-alt me-1"></i>Update Progress
                                </button>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-{{ $task->progress == 100 ? 'success' : 'primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $task->progress }}%"
                                     aria-valuenow="{{ $task->progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Details -->
                <div class="row">
                    <!-- Left Column: Task Information -->
                    <div class="col-lg-8">
                        <!-- Task Description -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>Description</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $task->description }}</p>
                            </div>
                        </div>

                        <!-- Task Timeline -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Timeline & Notes</h6>
                            </div>
                            <div class="card-body">
                                @if(count($notes) > 0)
                                    <div class="timeline">
                                        @foreach($notes as $note)
                                            <div class="timeline-item mb-3">
                                                <div class="d-flex">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-circle text-primary"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="bg-light p-3 rounded">
                                                            <small>{!! nl2br(e($note)) !!}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">No notes available for this task.</p>
                                @endif
                                
                                <!-- Add New Note Form -->
                                <div class="mt-4">
                                    <form action="{{ route('marketer.tasks.add-note', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="new_note" class="form-label">Add Note</label>
                                            <textarea name="note" id="new_note" class="form-control" rows="3" 
                                                      placeholder="Add a note about this task..."></textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Add Note
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Task Metadata -->
                    <div class="col-lg-4">
                        <!-- Assignment Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Assignment Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Assigned By</label>
                                    <div class="d-flex align-items-center">
                                        @if($task->assigner && $task->assigner->profile_photo)
                                            <img src="{{ asset('storage/' . $task->assigner->profile_photo) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 alt="{{ $task->assigner->name }}">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $task->assigner->name ?? 'N/A' }}</strong>
                                            @if($task->assigner)
                                                <div class="small text-muted">{{ $task->assigner->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Assigned On</label>
                                    <div>
                                        <strong>{{ $task->created_at->format('M d, Y') }}</strong>
                                        <div class="small text-muted">{{ $task->created_at->format('h:i A') }}</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Last Updated</label>
                                    <div>
                                        <strong>{{ $task->updated_at->format('M d, Y') }}</strong>
                                        <div class="small text-muted">{{ $task->updated_at->format('h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deadline & Timeline -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Deadline</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <h4 class="{{ $task->is_overdue ? 'text-danger' : 'text-primary' }}">
                                            {{ $task->deadline->format('M d, Y') }}
                                        </h4>
                                        <div class="small text-muted mb-2">{{ $task->deadline->format('h:i A') }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge {{ $task->is_overdue ? 'bg-danger' : 'bg-info' }}">
                                            <i class="fas fa-clock me-1"></i>{{ $task->days_remaining_text }}
                                        </span>
                                    </div>
                                    
                                    @if(!$task->is_overdue && $task->status != 'completed')
                                        <div class="alert alert-warning small mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            @if($task->days_remaining > 0)
                                                {{ $task->days_remaining }} days remaining to complete this task
                                            @else
                                                This task is due today
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Task Targets -->
                        @if($task->target_leads || $task->target_conversion)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-bullseye me-2"></i>Targets</h6>
                            </div>
                            <div class="card-body">
                                @if($task->target_leads)
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Target Leads</label>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-info" style="width: 0%"></div>
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <strong>{{ $task->target_leads }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($task->target_conversion)
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Target Conversion Rate</label>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $task->target_conversion }}%"></div>
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <strong>{{ $task->target_conversion }}%</strong>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" 
                                            class="btn btn-success"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal"
                                            onclick="prepareStatusUpdate('completed', 100)">
                                        <i class="fas fa-check-circle me-1"></i>Mark as Complete
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-primary"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal"
                                            onclick="prepareStatusUpdate('in_progress', {{ $task->progress > 0 ? $task->progress : 25 }})">
                                        <i class="fas fa-spinner me-1"></i>Set as In Progress
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-warning"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal"
                                            onclick="prepareStatusUpdate('pending', {{ $task->progress }})">
                                        <i class="fas fa-pause me-1"></i>Set as Pending
                                    </button>
                                    
                                    <a href="mailto:{{ $task->assigner->email ?? '#' }}?subject=Regarding Task: {{ $task->title }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-1"></i>Contact Assigner
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status/Progress Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateStatusForm" action="{{ route('marketer.tasks.update-status', $task->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Task Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Progress (%)</label>
                            <input type="range" name="progress" id="progress" class="form-range" 
                                   min="0" max="100" step="5" value="{{ $task->progress }}">
                            <div class="d-flex justify-content-between">
                                <small>0%</small>
                                <span id="progressValue" class="fw-bold">{{ $task->progress }}%</span>
                                <small>100%</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status Notes (Optional)</label>
                            <textarea name="status_notes" class="form-control" rows="3" 
                                      placeholder="Add update notes... (e.g., What did you accomplish? Any challenges?)"></textarea>
                            <small class="text-muted">Max 500 characters</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 20px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
        }
        
        .timeline-marker {
            position: absolute;
            left: -26px;
            top: 8px;
            z-index: 1;
            color: #0d6efd;
            background: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .timeline-marker i {
            font-size: 8px;
        }
        
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }
        
        .progress-bar {
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.8em;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Update progress value display
        const progressInput = document.getElementById('progress');
        const progressValue = document.getElementById('progressValue');
        
        if (progressInput && progressValue) {
            progressInput.addEventListener('input', function() {
                progressValue.textContent = this.value + '%';
                
                // Auto-set status based on progress
                const statusSelect = document.getElementById('status');
                if (this.value == 100 && statusSelect.value !== 'completed') {
                    statusSelect.value = 'completed';
                } else if (this.value > 0 && this.value < 100 && statusSelect.value === 'pending') {
                    statusSelect.value = 'in_progress';
                } else if (this.value == 0 && statusSelect.value !== 'pending') {
                    statusSelect.value = 'pending';
                }
            });
        }
        
        // Auto-set progress when status changes
        const statusSelect = document.getElementById('status');
        if (statusSelect && progressInput) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'completed') {
                    progressInput.value = 100;
                    progressValue.textContent = '100%';
                } else if (this.value === 'pending' && progressInput.value > 0) {
                    if (confirm('Setting status to Pending but progress is not 0%. Do you want to reset progress to 0%?')) {
                        progressInput.value = 0;
                        progressValue.textContent = '0%';
                    }
                }
            });
        }
        
        // Prepare status update with specific values
        function prepareStatusUpdate(status, progress) {
            if (statusSelect) statusSelect.value = status;
            if (progressInput) {
                progressInput.value = progress;
                progressValue.textContent = progress + '%';
            }
        }
        
        // Form validation before submission
        document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
            const progress = parseInt(progressInput.value);
            const status = statusSelect.value;
            
            // Validate progress for completed status
            if (status === 'completed' && progress < 100) {
                if (!confirm('Status is set to "Completed" but progress is less than 100%. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Validate progress for in-progress status
            if (status === 'in_progress' && progress === 0) {
                if (!confirm('Status is set to "In Progress" but progress is 0%. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Check for empty notes on status change
            const notes = document.querySelector('textarea[name="status_notes"]').value;
            if (!notes.trim()) {
                if (!confirm('You haven\'t added any status notes. Do you want to continue without notes?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // Add note form submission
        const addNoteForm = document.querySelector('form[action*="add-note"]');
        if (addNoteForm) {
            addNoteForm.addEventListener('submit', function(e) {
                const noteText = document.getElementById('new_note').value;
                if (!noteText.trim()) {
                    e.preventDefault();
                    alert('Please enter a note before submitting.');
                    return false;
                }
            });
        }
        
        // Auto-refresh progress bar on page load animation
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                const width = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 300);
            }
        });
    </script>
@endpush
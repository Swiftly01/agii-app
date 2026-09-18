@extends('layout.marketer')

@section('title', 'Edit Task - Agii')
@section('page-title', 'Edit Task')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Edit Task Details</h6>
                        <div class="badge bg-{{ $task->priority_color ?? 'secondary' }}">
                            {{ ucfirst($task->priority) }} Priority
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Task Status Overview -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-1">Current Status</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-{{ $task->status_color ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        <span class="badge {{ $task->is_overdue ? 'bg-danger' : 'bg-secondary' }}">
                                            @if($task->is_overdue)
                                                <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                            @else
                                                Deadline: {{ $task->deadline->format('M d, Y h:i A') }}
                                            @endif
                                        </span>
                                        <span class="badge bg-primary">
                                            Progress: {{ $task->progress }}%
                                        </span>
                                        <span class="badge bg-dark">
                                            Created: {{ $task->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assign to Marketer -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="marketer_id" class="form-label">Reassign To</label>
                                <select class="form-select @error('marketer_id') is-invalid @enderror" id="marketer_id"
                                    name="marketer_id">
                                    <option value="">Keep Current Assignee</option>
                                    @foreach ($marketers as $marketer)
                                        <option value="{{ $marketer->id }}"
                                            {{ old('marketer_id', $task->marketer_id) == $marketer->id ? 'selected' : '' }}>
                                            {{ $marketer->first_name }} {{ $marketer->last_name }}
                                            ({{ $marketer->email }})
                                            @if ($marketer->tasks_count)
                                                - {{ $marketer->tasks_count }} active task(s)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('marketer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave blank to keep current assignee. Current: 
                                    <strong>{{ $task->marketer->first_name ?? 'N/A' }} {{ $task->marketer->last_name ?? '' }}</strong>
                                </small>
                            </div>
                        </div>

                        <!-- Task Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-tasks me-2"></i>Task Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Task Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $task->title) }}"
                                    placeholder="e.g., Follow up with leads from Lagos region" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Task Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" placeholder="Provide detailed description of the task, including objectives and expectations..."
                                    required>{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Task Details -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-cog me-2"></i>Task Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority *</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                    name="priority" required>
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="deadline" class="form-label">Update Deadline</label>
                                <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                    id="deadline" name="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Current deadline: <strong>{{ $task->deadline->format('M d, Y h:i A') }}</strong></small>
                            </div>
                        </div>

                        <!-- Task Metrics -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-chart-line me-2"></i>Task Metrics</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="progress" class="form-label">Current Progress (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('progress') is-invalid @enderror"
                                        id="progress" name="progress" value="{{ old('progress', $task->progress) }}"
                                        min="0" max="100" step="1">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('progress')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $task->progress == 100 ? 'success' : 'primary' }}" 
                                         role="progressbar" 
                                         style="width: {{ $task->progress }}%"
                                         aria-valuenow="{{ $task->progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="target_leads" class="form-label">Target Leads</label>
                                <input type="number" class="form-control @error('target_leads') is-invalid @enderror"
                                    id="target_leads" name="target_leads" value="{{ old('target_leads', $task->target_leads) }}"
                                    placeholder="e.g., 50" min="0">
                                @error('target_leads')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="target_conversion" class="form-label">Target Conversion Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('target_conversion') is-invalid @enderror"
                                        id="target_conversion" name="target_conversion" value="{{ old('target_conversion', $task->target_conversion) }}"
                                        placeholder="e.g., 15" min="0" max="100" step="0.1">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('target_conversion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes / Update Log</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4"
                                placeholder="Add update notes, additional instructions, or changes made...">{{ old('notes', $task->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">These notes will be appended to the existing notes with a timestamp.</small>
                        </div>

                        <!-- Task History Section -->
                        @if($task->notes)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Existing Task Notes</h6>
                            </div>
                            <div class="card-body">
                                <div class="task-notes">
                                    @php
                                        $notes = explode("\n\n", $task->notes);
                                    @endphp
                                    @foreach($notes as $note)
                                        @if(trim($note))
                                            <div class="mb-2 p-2 bg-light rounded">
                                                <small class="text-muted">{!! nl2br(e($note)) !!}</small>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Danger Zone -->
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Danger Zone</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-danger w-100" 
                                                data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                                            <i class="fas fa-trash me-2"></i>Delete Task
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-warning w-100" 
                                                onclick="resetToOriginal()">
                                            <i class="fas fa-redo me-2"></i>Reset Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Tasks
                                </a>
                                <a href="{{ route('admin.tasks.show', $task->id) }}" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-eye me-2"></i>View Task
                                </a>
                            </div>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-redo me-2"></i>Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Task Modal -->
    <div class="modal fade" id="deleteTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                    <p>Are you sure you want to delete this task?</p>
                    <ul>
                        <li><strong>Task:</strong> {{ $task->title }}</li>
                        <li><strong>Assigned To:</strong> {{ $task->marketer->first_name ?? 'N/A' }}</li>
                        <li><strong>Status:</strong> {{ ucfirst($task->status) }}</li>
                        <li><strong>Progress:</strong> {{ $task->progress }}%</li>
                    </ul>
                    <p class="text-danger">All task data, including notes and progress history, will be permanently deleted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete Task Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .task-notes div {
            border-left: 3px solid #6c757d;
            padding-left: 10px;
        }
        
        .task-notes div:first-child {
            border-left-color: #0d6efd;
        }
        
        .progress {
            background-color: #e9ecef;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        
        .card.border-danger .card-header {
            background-color: #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Store original values for reset functionality
        const originalValues = {
            title: "{{ addslashes($task->title) }}",
            description: "{{ addslashes($task->description) }}",
            marketer_id: "{{ $task->marketer_id }}",
            priority: "{{ $task->priority }}",
            deadline: "{{ $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '' }}",
            progress: "{{ $task->progress }}",
            status: "{{ $task->status }}",
            target_leads: "{{ $task->target_leads ?? '' }}",
            target_conversion: "{{ $task->target_conversion ?? '' }}",
            notes: "{{ addslashes($task->notes ?? '') }}"
        };

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const dateInput = document.getElementById('deadline');
            const minDate = today.toISOString().slice(0, 16);
            
            if (dateInput) {
                dateInput.min = minDate;
            }

            // Update progress bar when progress input changes
            const progressInput = document.getElementById('progress');
            if (progressInput) {
                progressInput.addEventListener('input', function() {
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar) {
                        progressBar.style.width = this.value + '%';
                        progressBar.setAttribute('aria-valuenow', this.value);
                        progressBar.textContent = this.value + '%';
                        
                        // Update color based on progress
                        if (this.value == 100) {
                            progressBar.classList.remove('bg-primary');
                            progressBar.classList.add('bg-success');
                        } else {
                            progressBar.classList.remove('bg-success');
                            progressBar.classList.add('bg-primary');
                        }
                    }
                });
            }

            // Auto-set status based on progress
            const statusSelect = document.getElementById('status');
            if (progressInput && statusSelect) {
                progressInput.addEventListener('change', function() {
                    if (this.value == 100 && statusSelect.value !== 'completed') {
                        if (confirm('Progress is 100%. Would you like to set status to "Completed"?') && statusSelect) {
                            statusSelect.value = 'completed';
                        }
                    } else if (this.value > 0 && statusSelect.value === 'pending') {
                        statusSelect.value = 'in_progress';
                    }
                });
            }

            // Update status when changed
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'completed') {
                        progressInput.value = 100;
                        progressInput.dispatchEvent(new Event('input'));
                    }
                });
            }
        });

        // Reset form to original values
        function resetToOriginal() {
            if (confirm('Are you sure you want to reset all changes? This cannot be undone.')) {
                document.getElementById('title').value = originalValues.title.replace(/\\/g, '');
                document.getElementById('description').value = originalValues.description.replace(/\\/g, '');
                document.getElementById('marketer_id').value = originalValues.marketer_id;
                document.getElementById('priority').value = originalValues.priority;
                document.getElementById('deadline').value = originalValues.deadline;
                document.getElementById('progress').value = originalValues.progress;
                document.getElementById('status').value = originalValues.status;
                document.getElementById('target_leads').value = originalValues.target_leads;
                document.getElementById('target_conversion').value = originalValues.target_conversion;
                document.getElementById('notes').value = originalValues.notes.replace(/\\/g, '');
                
                // Trigger progress bar update
                document.getElementById('progress').dispatchEvent(new Event('input'));
                
                alert('Form has been reset to original values.');
            }
        }

        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const progress = document.getElementById('progress').value;
            const status = document.getElementById('status').value;
            
            if (status === 'completed' && parseInt(progress) < 100) {
                if (!confirm('Status is set to "Completed" but progress is less than 100%. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (status !== 'completed' && parseInt(progress) === 100) {
                if (!confirm('Progress is 100% but status is not "Completed". Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Check if deadline is in the past for non-completed tasks
            const deadline = document.getElementById('deadline').value;
            if (deadline) {
                const deadlineDate = new Date(deadline);
                const now = new Date();
                
                if (deadlineDate < now && status !== 'completed') {
                    if (!confirm('The deadline is in the past and task is not completed. Do you want to continue?')) {
                        e.preventDefault();
                        return false;
                    }
                }
            }
            
            return true;
        });
    </script>
@endpush
@extends('layout.marketer')

@section('title', 'Assign Multiple Tasks - Agii')
@section('page-title', 'Assign Multiple Tasks')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">ASSIGN MULTIPLE TASKS</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.storeMultiple') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Assignment Information - Table Style -->
                        <table class="table table-bordered mb-4">
                            <tr>
                                <th style="width: 200px;">Assign to:</th>
                                <td>
                                    <select class="form-select @error('marketer_id') is-invalid @enderror" 
                                        id="marketer_id" name="marketer_id" required>
                                        <option value="">Select Marketer</option>
                                        @foreach ($marketers as $marketer)
                                            @php
                                                $staffNumber = $marketer->staffProfile ? $marketer->staffProfile->staff_id : 'N/A';
                                                $department = $marketer->staffProfile ? $marketer->staffProfile->department : 'N/A';
                                            @endphp
                                            <option value="{{ $marketer->id }}" 
                                                data-staff="{{ $staffNumber }}"
                                                data-dept="{{ $department }}"
                                                {{ old('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                                {{ $marketer->first_name }} {{ $marketer->last_name }}
                                                @if($marketer->tasks_count)
                                                    - {{ $marketer->tasks_count }} active task(s)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('marketer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th>Staff Number:/Referral Code:</th>
                                <td>
                                    <span id="staff_number_display" class="form-control-plaintext">-</span>
                                    <input type="hidden" name="staff_number" id="staff_number_input">
                                </td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>
                                    <span id="department_display" class="form-control-plaintext">-</span>
                                    <input type="hidden" name="department" id="department_input">
                                </td>
                            </tr>
                            <tr>
                                <th>Assigned Date:</th>
                                <td>
                                    <!--<span class="form-control-plaintext">{{ now()->format('d/m/Y H:i') }}</span>-->
                                    <input type="text" class='form-control' name="assigned_date" value="{{ now() }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Assigned by:</th>
                                <td>
                                    <!--<span class="form-control-plaintext">-->
                                    <!--    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}-->
                                    <!--    ({{ Auth::user()->email }})-->
                                    <!--</span>-->
                                    <input type="text" name="assigned_by" value="" class='form-control'>
                                </td>
                            </tr>
                            <tr>
                                <th>Report to:</th>
                                <td>
                                    <input type="text" 
                                           name="report_to" 
                                           class="form-control @error('report_to') is-invalid @enderror" 
                                           value="" 
                                           >
                                           {{-- old('report_to', Auth::user()->first_name . ' ' . Auth::user()->last_name . ' (' . Auth::user()->email . ')') --}}
                                    @error('report_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <th>Report Date:</th>
                                <td>
                                    <span class="form-control-plaintext">Set Deadline to each task below</span>
                                </td>
                            </tr>
                        </table>

                        <!-- Single TASK DESCRIPTION for all tasks -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">TASK DESCRIPTION (Applies to all tasks):</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4" 
                                placeholder="Enter the main task description that applies to all tasks..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multiple Tasks Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">TASKS:</label>
                                <button type="button" class="btn btn-sm btn-primary" id="addTaskBtn">
                                    <i class="fas fa-plus me-1"></i>Add Another Task
                                </button>
                            </div>
                            
                            <div id="tasksContainer">
                                <!-- Task 1 (Default) -->
                                <div class="task-item card mb-3" id="task-1">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Task #1</h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-task" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="form-label">Task Title *</label>
                                                <input type="text" class="form-control" 
                                                    name="tasks[0][title]" 
                                                    placeholder="e.g., Follow up with leads from Lagos region" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Priority *</label>
                                                <select class="form-select" name="tasks[0][priority]" required>
                                                    <option value="">Select</option>
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Deadline *</label>
                                                <input type="datetime-local" class="form-control" 
                                                    name="tasks[0][deadline]" 
                                                    min="{{ date('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Target Leads (Optional)</label>
                                                <input type="number" class="form-control" 
                                                    name="tasks[0][target_leads]" 
                                                    placeholder="e.g., 50" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="card mb-4 bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Additional Options (Apply to all tasks)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="target_conversion" class="form-label">Default Target Conversion Rate (%)</label>
                                        <input type="number" class="form-control" id="target_conversion" 
                                            name="target_conversion" value="{{ old('target_conversion') }}" 
                                            placeholder="e.g., 15" min="0" max="100" step="0.1">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="attached_file" class="form-label">Attached file:</label>
                                        <input type="file" class="form-control" id="attached_file" name="attached_file">
                                        <small class="text-muted">Optional: Attach any relevant files (Max: 10MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Assign Tasks
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Set minimum date to today
        function setMinDate(input) {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            input.min = tomorrow.toISOString().slice(0, 16);
            
            // If no value set, default to tomorrow 9:00 AM
            if (!input.value) {
                tomorrow.setHours(9, 0, 0, 0);
                input.value = tomorrow.toISOString().slice(0, 16);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set min date for first task
            const firstDeadline = document.querySelector('input[name="tasks[0][deadline]"]');
            if (firstDeadline) {
                setMinDate(firstDeadline);
            }
        });

        // Update staff number and department when marketer is selected
        document.getElementById('marketer_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const staffNumber = selectedOption.getAttribute('data-staff') || '-';
            const department = selectedOption.getAttribute('data-dept') || '-';
            
            document.getElementById('staff_number_display').textContent = staffNumber;
            document.getElementById('staff_number_input').value = staffNumber;
            
            document.getElementById('department_display').textContent = department;
            document.getElementById('department_input').value = department;
        });

        // Trigger change event if there's a selected value
        const marketerSelect = document.getElementById('marketer_id');
        if (marketerSelect.value) {
            marketerSelect.dispatchEvent(new Event('change'));
        }

        // Task counter
        let taskCount = 1;

        // Add new task
        document.getElementById('addTaskBtn').addEventListener('click', function() {
            taskCount++;
            
            const tasksContainer = document.getElementById('tasksContainer');
            const newTask = document.createElement('div');
            newTask.className = 'task-item card mb-3';
            newTask.id = `task-${taskCount}`;
            
            newTask.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Task #${taskCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-task">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Task Title *</label>
                            <input type="text" class="form-control" 
                                name="tasks[${taskCount-1}][title]" 
                                placeholder="e.g., Follow up with leads from Lagos region" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority *</label>
                            <select class="form-select" name="tasks[${taskCount-1}][priority]" required>
                                <option value="">Select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Deadline *</label>
                            <input type="datetime-local" class="form-control" 
                                name="tasks[${taskCount-1}][deadline]" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Leads (Optional)</label>
                            <input type="number" class="form-control" 
                                name="tasks[${taskCount-1}][target_leads]" 
                                placeholder="e.g., 50" min="0">
                        </div>
                    </div>
                </div>
            `;
            
            tasksContainer.appendChild(newTask);
            
            // Set min date for the new task
            const newDeadline = newTask.querySelector('input[type="datetime-local"]');
            setMinDate(newDeadline);
            
            // Show remove button on first task if hidden
            const firstTaskRemoveBtn = document.querySelector('#task-1 .remove-task');
            if (firstTaskRemoveBtn) {
                firstTaskRemoveBtn.style.display = 'block';
            }
            
            // Update submit button text
            updateSubmitButtonText();
        });

        // Remove task
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task') || e.target.closest('.remove-task')) {
                const removeBtn = e.target.closest('.remove-task');
                const taskItem = removeBtn.closest('.task-item');
                taskItem.remove();
                
                // Renumber remaining tasks
                renumberTasks();
                
                // Hide remove button on first task if only one remains
                const remainingTasks = document.querySelectorAll('.task-item');
                if (remainingTasks.length === 1) {
                    document.querySelector('#task-1 .remove-task').style.display = 'none';
                }
                
                // Update submit button text
                updateSubmitButtonText();
            }
        });

        // Renumber tasks after removal
        function renumberTasks() {
            const tasks = document.querySelectorAll('.task-item');
            tasks.forEach((task, index) => {
                const newIndex = index;
                task.id = `task-${newIndex + 1}`;
                
                // Update header
                const header = task.querySelector('.card-header h6');
                if (header) {
                    header.textContent = `Task #${newIndex + 1}`;
                }
                
                // Update input names
                const titleInput = task.querySelector('input[name^="tasks"][name$="[title]"]');
                if (titleInput) {
                    titleInput.name = `tasks[${newIndex}][title]`;
                }
                
                const prioritySelect = task.querySelector('select[name^="tasks"][name$="[priority]"]');
                if (prioritySelect) {
                    prioritySelect.name = `tasks[${newIndex}][priority]`;
                }
                
                const deadlineInput = task.querySelector('input[name^="tasks"][name$="[deadline]"]');
                if (deadlineInput) {
                    deadlineInput.name = `tasks[${newIndex}][deadline]`;
                }
                
                const targetLeadsInput = task.querySelector('input[name^="tasks"][name$="[target_leads]"]');
                if (targetLeadsInput) {
                    targetLeadsInput.name = `tasks[${newIndex}][target_leads]`;
                }
            });
            
            taskCount = tasks.length;
        }

        // Update submit button text
        function updateSubmitButtonText() {
            const taskCount = document.querySelectorAll('.task-item').length;
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Assign ${taskCount} Task${taskCount > 1 ? 's' : ''}`;
            }
        }
    </script>
@endpush
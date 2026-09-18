@extends('layout.marketer')

@section('title', 'Assign New Task - Agii')
@section('page-title', 'Assign New Task')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Task Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tasks.store') }}" method="POST">
                        @csrf

                        <!-- Assign to Marketer -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="marketer_id" class="form-label">Assign To *</label>
                                <select class="form-select @error('marketer_id') is-invalid @enderror" id="marketer_id"
                                    name="marketer_id" required>
                                    <option value="">Select Marketer</option>
                                    @foreach ($marketers as $marketer)
                                        <option value="{{ $marketer->id }}"
                                            {{ old('marketer_id') == $marketer->id ? 'selected' : '' }}>
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
                                <small class="text-muted">Select the marketer to assign this task to.</small>
                            </div>
                        </div>

                        <!-- Task Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-tasks me-2"></i>Task Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Task Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="e.g., Follow up with leads from Lagos region" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Task Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" placeholder="Provide detailed description of the task, including objectives and expectations..."
                                    required>{{ old('description') }}</textarea>
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
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="deadline" class="form-label">Deadline *</label>
                                <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                    id="deadline" name="deadline" value="{{ old('deadline') }}"
                                    min="{{ date('Y-m-d\TH:i') }}" required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select the deadline date and time for this task.</small>
                            </div>
                        </div>

                        <!-- Task Metrics (Optional) -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-chart-line me-2"></i>Task Metrics (Optional)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="target_leads" class="form-label">Target Leads</label>
                                <input type="number" class="form-control @error('target_leads') is-invalid @enderror"
                                    id="target_leads" name="target_leads" value="{{ old('target_leads') }}"
                                    placeholder="e.g., 50" min="0">
                                @error('target_leads')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Target number of leads to generate.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="target_conversion" class="form-label">Target Conversion Rate (%)</label>
                                <input type="number" class="form-control @error('target_conversion') is-invalid @enderror"
                                    id="target_conversion" name="target_conversion" value="{{ old('target_conversion') }}"
                                    placeholder="e.g., 15" min="0" max="100" step="0.1">
                                @error('target_conversion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Target conversion rate percentage.</small>
                            </div>

                            <div class="col-md-12">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                    placeholder="Any additional instructions, resources, or information...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Task Template Suggestions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Quick Task Templates</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            onclick="useTemplate('lead_generation')">
                                            <i class="fas fa-users me-2"></i>Lead Generation
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-success w-100"
                                            onclick="useTemplate('follow_up')">
                                            <i class="fas fa-phone me-2"></i>Follow Up Leads
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-info w-100"
                                            onclick="useTemplate('campaign')">
                                            <i class="fas fa-bullhorn me-2"></i>Campaign Setup
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-warning w-100"
                                            onclick="useTemplate('report')">
                                            <i class="fas fa-chart-bar me-2"></i>Analytics Report
                                        </button>
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
                                    <i class="fas fa-paper-plane me-2"></i>Assign Task
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
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const dateInput = document.getElementById('deadline');
            const minDate = tomorrow.toISOString().slice(0, 16);
            dateInput.min = minDate;

            // If no value set, default to tomorrow 9:00 AM
            if (!dateInput.value) {
                tomorrow.setHours(9, 0, 0, 0);
                dateInput.value = tomorrow.toISOString().slice(0, 16);
            }
        });

        // Task templates
        const templates = {
            'lead_generation': {
                title: 'Generate New Leads',
                description: 'Research and identify potential leads in the target market. Use available resources to create a list of prospective clients.',
                priority: 'high',
                target_leads: 50,
                target_conversion: 10
            },
            'follow_up': {
                title: 'Follow Up with Existing Leads',
                description: 'Contact existing leads to nurture relationships and move them through the sales pipeline.',
                priority: 'medium',
                target_leads: 30,
                target_conversion: 20
            },
            'campaign': {
                title: 'Setup Marketing Campaign',
                description: 'Create and launch a new marketing campaign targeting specific demographics.',
                priority: 'high',
                target_leads: 100,
                target_conversion: 15
            },
            'report': {
                title: 'Prepare Monthly Analytics Report',
                description: 'Compile and analyze marketing data from the past month. Create insights and recommendations for improvement.',
                priority: 'low',
                target_leads: 0,
                target_conversion: 0
            }
        };

        function useTemplate(templateKey) {
            if (templates[templateKey]) {
                const template = templates[templateKey];

                document.getElementById('title').value = template.title;
                document.getElementById('description').value = template.description;
                document.getElementById('priority').value = template.priority;

                if (template.target_leads > 0) {
                    document.getElementById('target_leads').value = template.target_leads;
                }

                if (template.target_conversion > 0) {
                    document.getElementById('target_conversion').value = template.target_conversion;
                }

                // Show success message
                alert(`"${template.title}" template loaded! Please review and customize as needed.`);
            }
        }
    </script>
@endpush

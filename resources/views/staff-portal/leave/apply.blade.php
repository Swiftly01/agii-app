{{-- resources/views/staff-portal/leave/apply.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('staff.leave.dashboard') }}">Leave Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Apply for Leave</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-medical"></i> Apply for Leave</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.leave.store') }}" method="POST" enctype="multipart/form-data" id="leaveForm">
                        @csrf
                        
                        <!-- Leave Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Leave Type & Duration</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_type_id" class="form-label">Leave Type *</label>
                                    <select class="form-select @error('leave_type_id') is-invalid @enderror" 
                                            id="leave_type_id" name="leave_type_id" required>
                                        <option value="">Select Leave Type</option>
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    data-requires-approval="{{ $type->requires_approval }}"
                                                    data-annual-entitlement="{{ $type->annual_entitlement }}"
                                                    {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} 
                                                @if($type->requires_approval)
                                                    <small class="text-muted">(Requires Approval)</small>
                                                @else
                                                    <small class="text-success">(Auto-Approved)</small>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Select the type of leave you're applying for</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Available Balance</label>
                                    <div id="leaveBalanceDisplay" class="p-2 bg-light rounded">
                                        <small class="text-muted">Select a leave type to see your balance</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}" 
                                           min="{{ date('Y-m-d') }}" 
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">First day of your leave</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date *</label>
                                    <input type="date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date') }}" 
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Last day of your leave</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <h6 id="totalDays">0</h6>
                                            <small class="text-muted">Total Working Days</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 id="weekendDays">0</h6>
                                            <small class="text-muted">Weekend Days</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 id="holidayDays">0</h6>
                                            <small class="text-muted">Holiday Days</small>
                                        </div>
                                    </div>
                                    <small class="d-block mt-2">
                                        <i class="fas fa-info-circle"></i> Weekends and holidays are automatically excluded from your leave days.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reason & Details -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Reason & Details</h6>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason for Leave *</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                                              id="reason" 
                                              name="reason" 
                                              rows="4" 
                                              placeholder="Please provide a detailed reason for your leave application..."
                                              required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Be specific about why you need this leave</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">Additional Information</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                    <input type="text" 
                                           class="form-control @error('emergency_contact') is-invalid @enderror" 
                                           id="emergency_contact" 
                                           name="emergency_contact" 
                                           value="{{ old('emergency_contact') }}"
                                           placeholder="Name and phone number">
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Contact person in case of emergency</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="handover_to" class="form-label">Handover To</label>
                                    <input type="text" 
                                           class="form-control @error('handover_to') is-invalid @enderror" 
                                           id="handover_to" 
                                           name="handover_to" 
                                           value="{{ old('handover_to') }}"
                                           placeholder="Colleague's name">
                                    @error('handover_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Person who will handle your responsibilities</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attachment -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Supporting Document (Optional)</label>
                                    <input type="file" 
                                           class="form-control @error('attachment') is-invalid @enderror" 
                                           id="attachment" 
                                           name="attachment"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Upload supporting documents if required (medical certificate, travel tickets, etc.)
                                        <br>Max file size: 5MB. Allowed: PDF, Word, Images
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms & Conditions -->
                        <div class="alert alert-warning mb-4">
                            <h6><i class="fas fa-exclamation-triangle"></i> Important Information</h6>
                            <ul class="mb-0">
                                <li>Leave applications require supervisor approval unless otherwise specified</li>
                                <li>Apply at least 3 working days in advance for planned leave</li>
                                <li>For medical leave, attach medical certificate</li>
                                <li>Ensure all your responsibilities are properly handed over</li>
                                <li>Your leave balance will be updated after approval</li>
                            </ul>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('staff.leave.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i> Submit Leave Application
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Leave Balances -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Your Leave Balances</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($leaveBalances as $balance)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $balance['name'] }}</h6>
                                <small class="text-muted">Annual: {{ $balance['total'] }} days</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $balance['remaining'] > 0 ? 'success' : 'danger' }}">
                                    {{ $balance['remaining'] }} days left
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Holidays -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar-day"></i> Upcoming Holidays</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingHolidays as $holiday)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $holiday }}</h6>
                                <small class="text-muted">{{ Carbon\Carbon::parse($holiday)->format('D, M j, Y') }}</small>
                            </div>
                            <span class="badge bg-info">Holiday</span>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            No upcoming holidays
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Application Preview -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-eye"></i> Application Preview</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Leave Type:</strong></td>
                            <td id="previewType">Not selected</td>
                        </tr>
                        <tr>
                            <td><strong>Duration:</strong></td>
                            <td id="previewDuration">Not set</td>
                        </tr>
                        <tr>
                            <td><strong>Total Days:</strong></td>
                            <td id="previewDays">0</td>
                        </tr>
                        <tr>
                            <td><strong>Approval:</strong></td>
                            <td id="previewApproval">-</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const leaveForm = document.getElementById('leaveForm');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const leaveTypeSelect = document.getElementById('leave_type_id');
        
        // Set min date for end date
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            calculateDays();
        });
        
        endDateInput.addEventListener('change', calculateDays);
        leaveTypeSelect.addEventListener('change', updateLeaveBalance);
        
        // Calculate leave days
        function calculateDays() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (!startDate || !endDate) {
                updateDisplay(0, 0, 0);
                return;
            }
            
            // Send AJAX request to calculate days
            fetch('{{ route("staff.leave.calculate-days") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateDisplay(data.total_days, data.weekend_days, data.holiday_days);
                    updatePreview();
                }
            });
        }
        
        // Update display with calculated days
        function updateDisplay(totalDays, weekendDays, holidayDays) {
            document.getElementById('totalDays').textContent = totalDays;
            document.getElementById('weekendDays').textContent = weekendDays;
            document.getElementById('holidayDays').textContent = holidayDays;
        }
        
        // Update leave balance display
        function updateLeaveBalance() {
            const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
            const leaveTypeId = leaveTypeSelect.value;
            
            if (!leaveTypeId) {
                document.getElementById('leaveBalanceDisplay').innerHTML = 
                    '<small class="text-muted">Select a leave type to see your balance</small>';
                return;
            }
            
            // Get balance data from data attributes
            const annualEntitlement = selectedOption.getAttribute('data-annual-entitlement');
            const requiresApproval = selectedOption.getAttribute('data-requires-approval');
            
            // In a real application, you would fetch the actual balance from the server
            // For now, we'll use the balance from the sidebar
            const balanceElement = document.querySelector(`[data-balance-id="${leaveTypeId}"]`);
            const remainingDays = balanceElement ? balanceElement.textContent : '?';
            
            document.getElementById('leaveBalanceDisplay').innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Annual Entitlement:</strong> ${annualEntitlement} days
                    </div>
                    <div>
                        <strong>Available:</strong> <span class="badge bg-success">${remainingDays} days</span>
                    </div>
                </div>
                <small class="text-muted d-block mt-1">
                    ${requiresApproval === '1' ? 'Requires approval' : 'Auto-approved'}
                </small>
            `;
            
            updatePreview();
        }
        
        // Update application preview
        function updatePreview() {
            const leaveType = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const totalDays = document.getElementById('totalDays').textContent;
            
            // Update preview
            document.getElementById('previewType').textContent = 
                leaveType.textContent.split('(')[0].trim();
            document.getElementById('previewDuration').textContent = 
                startDate && endDate ? `${startDate} to ${endDate}` : 'Not set';
            document.getElementById('previewDays').textContent = totalDays;
            document.getElementById('previewApproval').textContent = 
                leaveType.getAttribute('data-requires-approval') === '1' ? 
                'Required' : 'Auto-Approved';
        }
        
        // Form validation before submission
        leaveForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const totalDays = parseInt(document.getElementById('totalDays').textContent);
            
            if (totalDays <= 0) {
                e.preventDefault();
                alert('Please select valid dates for your leave application.');
                return false;
            }
            
            // Disable button and show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            return true;
        });
        
        // Initialize
        if (leaveTypeSelect.value) {
            updateLeaveBalance();
        }
        
        if (startDateInput.value && endDateInput.value) {
            calculateDays();
        }
    });
</script>
@endpush

@push('styles')
<style>
    .border-bottom {
        border-color: #dee2e6 !important;
    }
    
    .card-header h5, .card-header h6 {
        font-weight: 600;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .alert ul {
        padding-left: 1.5rem;
        margin-bottom: 0;
    }
    
    .alert li {
        margin-bottom: 0.25rem;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
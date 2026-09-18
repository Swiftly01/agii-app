@extends('layout.marketer')

@section('title', 'Edit Staff - Agii')
@section('page-title', 'Edit Staff Profile')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Staff</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Staff Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.update-from-application', $staff->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="employment_application_id" value="{{ $staff->employment_application_id }}">
                        <input type="hidden" name="first_name" value="{{ $staff->user->first_name }}">
                        <input type="hidden" name="surname" value="{{ $staff->user->last_name }}">
                        
                        <!-- Employment Details Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-briefcase"></i> Employment Details
                                </h6>
                            </div>
                            
                            <!-- Staff ID (Read-only) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Staff ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $staff->staff_id }}" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Department -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department *</label>
                                    <select class="form-select @error('department') is-invalid @enderror" 
                                            id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept }}" 
                                                {{ old('department', $staff->department) == $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                        <option value="other" 
                                            {{ !in_array($staff->department, $departments) ? 'selected' : '' }}>
                                            Other (Specify)
                                        </option>
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="text" 
                                           class="form-control mt-2 {{ !in_array($staff->department, $departments) ? '' : 'd-none' }}" 
                                           id="other_department" 
                                           name="other_department" 
                                           value="{{ !in_array($staff->department, $departments) ? $staff->department : '' }}"
                                           placeholder="Please specify department">
                                </div>
                            </div>
                            
                            <!-- Designation -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation/Position *</label>
                                    <input type="text" 
                                           class="form-control @error('designation') is-invalid @enderror" 
                                           id="designation" 
                                           name="designation" 
                                           value="{{ old('designation', $staff->designation) }}" 
                                           required>
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Employment Type -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Employment Type *</label>
                                    <select class="form-select @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Type</option>
                                        <option value="full_time" {{ old('employment_type', $staff->employment_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ old('employment_type', $staff->employment_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('employment_type', $staff->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="probation" {{ old('employment_type', $staff->employment_type) == 'probation' ? 'selected' : '' }}>Probation</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Date of Employment -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_employment" class="form-label">Date of Employment *</label>
                                    <input type="date" 
                                           class="form-control @error('date_of_employment') is-invalid @enderror" 
                                           id="date_of_employment" 
                                           name="date_of_employment" 
                                           value="{{ old('date_of_employment', $staff->date_of_employment->format('Y-m-d')) }}" 
                                           required>
                                    @error('date_of_employment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Salary -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Monthly Salary (₦)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₦</span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control @error('salary') is-invalid @enderror" 
                                               id="salary" 
                                               name="salary" 
                                               value="{{ old('salary', $staff->salary) }}" 
                                               placeholder="0.00">
                                    </div>
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Account Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-shield"></i> User Account Details
                                </h6>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $staff->user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Password (Optional on update) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password (Optional)</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        <button class="btn btn-outline-secondary" type="button" id="generatePassword">
                                            <i class="fas fa-sync-alt"></i> Generate
                                        </button>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User Role -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">User Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="staff" {{ old('role', $staff->user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="manager" {{ old('role', $staff->user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="admin" {{ old('role', $staff->user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle"></i> Additional Information
                                </h6>
                            </div>
                            
                            <!-- Supervisor/Manager -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supervisor_id" class="form-label">Reporting To (Supervisor)</label>
                                    <select class="form-select" id="supervisor_id" name="supervisor_id">
                                        <option value="">Select Supervisor</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->user_id }}" 
                                                {{ old('supervisor_id', $staff->supervisor_id) == $supervisor->user_id ? 'selected' : '' }}>
                                                {{ $supervisor->user->name }} ({{ $supervisor->designation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Work Location -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_location" class="form-label">Work Location</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="work_location" 
                                           name="work_location" 
                                           value="{{ old('work_location', $staff->work_location) }}" 
                                           placeholder="e.g., Head Office, Branch Office">
                                </div>
                            </div>
                            
                            <!-- Work Schedule -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_schedule" class="form-label">Work Schedule</label>
                                    <select class="form-select" id="work_schedule" name="work_schedule">
                                        <option value="">Select Schedule</option>
                                        <option value="9-5" {{ old('work_schedule', $staff->work_schedule) == '9-5' ? 'selected' : '' }}>9 AM - 5 PM (Standard)</option>
                                        <option value="8-4" {{ old('work_schedule', $staff->work_schedule) == '8-4' ? 'selected' : '' }}>8 AM - 4 PM</option>
                                        <option value="flexible" {{ old('work_schedule', $staff->work_schedule) == 'flexible' ? 'selected' : '' }}>Flexible Hours</option>
                                        <option value="shift" {{ old('work_schedule', $staff->work_schedule) == 'shift' ? 'selected' : '' }}>Shift Work</option>
                                        <option value="remote" {{ old('work_schedule', $staff->work_schedule) == 'remote' ? 'selected' : '' }}>Remote</option>
                                        <option value="hybrid" {{ old('work_schedule', $staff->work_schedule) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Probation Period -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="probation_period" class="form-label">Probation Period (Months)</label>
                                    <select class="form-select" id="probation_period" name="probation_period">
                                        <option value="">Select Period</option>
                                        <option value="1" {{ old('probation_period', $staff->probation_period) == '1' ? 'selected' : '' }}>1 Month</option>
                                        <option value="3" {{ old('probation_period', $staff->probation_period) == '3' ? 'selected' : '' }}>3 Months</option>
                                        <option value="6" {{ old('probation_period', $staff->probation_period) == '6' ? 'selected' : '' }}>6 Months</option>
                                        <option value="0" {{ old('probation_period', $staff->probation_period) == '0' ? 'selected' : '' }}>No Probation</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3">{{ old('notes', $staff->notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Document Upload Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-file-upload"></i> Update Documents (Optional)
                                </h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Upload new files to replace existing ones. Leave empty to keep current documents.
                                </div>
                            </div>
                            
                            <!-- Current Documents Display -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Current Documents:</label>
                                <div class="list-group">
                                    @if($staff->documents->where('type', 'offer_letter')->count())
                                        <a href="{{ Storage::url($staff->documents->where('type', 'offer_letter')->first()->file_path) }}" 
                                           class="list-group-item list-group-item-action" target="_blank">
                                            <i class="fas fa-file-pdf text-danger"></i> Current Offer Letter
                                        </a>
                                    @endif
                                    @if($staff->documents->where('type', 'contract')->count())
                                        <a href="{{ Storage::url($staff->documents->where('type', 'contract')->first()->file_path) }}" 
                                           class="list-group-item list-group-item-action" target="_blank">
                                            <i class="fas fa-file-pdf text-danger"></i> Current Employment Contract
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Offer Letter Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="offer_letter" class="form-label">New Offer Letter (PDF)</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="offer_letter" 
                                           name="offer_letter" 
                                           accept=".pdf">
                                    <small class="form-text text-muted">Upload to replace existing offer letter</small>
                                </div>
                            </div>
                            
                            <!-- Contract Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contract" class="form-label">New Employment Contract (PDF)</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="contract" 
                                           name="contract" 
                                           accept=".pdf">
                                    <small class="form-text text-muted">Upload to replace existing contract</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Update Notice -->
                        <div class="alert alert-warning mb-4">
                            <h6><i class="fas fa-exclamation-triangle"></i> Update Information</h6>
                            <ul class="mb-0">
                                <li>No email notification will be sent for this update</li>
                                <li>Only fill password field if you want to change the password</li>
                                <li>Uploading new documents will replace the existing ones</li>
                                <li>All changes will be saved immediately</li>
                            </ul>
                        </div>
                        <!-- Add this hidden input in your edit form, somewhere after the opening form tag -->
<input type="hidden" name="user_id" value="{{ $staff->user->id }}">


                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                  {{--  <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Staff List
                                    </a> --}}
                                    
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-warning" id="submitBtn">
                                            <i class="fas fa-save"></i> Update Staff Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department other field toggle
        const departmentSelect = document.getElementById('department');
        const otherDepartmentInput = document.getElementById('other_department');
        
        departmentSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                otherDepartmentInput.classList.remove('d-none');
            } else {
                otherDepartmentInput.classList.add('d-none');
                otherDepartmentInput.value = '';
            }
        });
        
        // Password generation
        document.getElementById('generatePassword').addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;
        });
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush
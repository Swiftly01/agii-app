@extends('layout.marketer')

@section('title', 'Staff - Agii')
@section('page-title', 'Staff Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff Management</a></li>
                    {{--<li class="breadcrumb-item"><a href="{{ route('applications.index') }}">Applications</a></li> --}}
                    <li class="breadcrumb-item active" aria-current="page">Hire Staff from Application</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- Applicant Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Applicant Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($application->passport_photo)
                            <img src="{{ url($application->passport_photo) }}" 
                                 alt="Passport Photo" 
                                 class="img-thumbnail rounded-circle" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user text-muted" style="font-size: 60px;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="text-center">{{ $application->first_name }} {{ $application->surname }}</h4>
                    <p class="text-center text-muted mb-4">{{ $application->position_applying_for }}</p>
                    
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $application->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $application->contact_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ ucfirst($application->sex) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State/LGA:</strong></td>
                                    <td>{{ $application->state_of_origin }} / {{ $application->lga }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $application->residential_address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Education:</strong></td>
                                    <td>{{ $application->educational_qualification }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($application->resume)
                    <div class="mt-3">
                        <a href="{{ Storage::url($application->resume) }}" 
                           class="btn btn-outline-primary btn-sm w-100" 
                           target="_blank">
                            <i class="fas fa-file-download"></i> Download Resume
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Application Documents -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-file-alt"></i> Application Documents</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                      @php
                            $certificates = is_array($application->certificates)
                                ? $application->certificates
                                : json_decode($application->certificates, true);
                        @endphp
                        
                        @if(!empty($certificates))
                            @foreach($certificates as $certificate)
                                <a href="{{ url($certificate) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                   target="_blank">
                                    <span>
                                        <i class="fas fa-certificate text-warning"></i>
                                        Certificate
                                    </span>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                        
                                <img src="{{ url($certificate) }}" 
                                     alt="Certificate Image" 
                                     class="img-thumbnail mt-2"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @endforeach
                        @endif


                        
                       @php
                            $ids = is_array($application->means_of_identification)
                                ? $application->means_of_identification
                                : json_decode($application->means_of_identification, true);
                        @endphp
                        
                        @if(!empty($ids))
                            @foreach($ids as $index => $id)
                                <a href="{{ url($id) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                   target="_blank">
                                    <span>
                                        <i class="fas fa-id-card text-info"></i>
                                        ID Document {{ $index + 1 }}
                                    </span>
                                    <i class="fas fa-external-link-alt text-muted"></i>
                                </a>
                        
                                @php
                                    $ext = strtolower(pathinfo($id, PATHINFO_EXTENSION));
                                @endphp
                        
                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                    <img src="{{ url($id) }}"
                                         alt="ID Document {{ $index + 1 }}"
                                         class="img-thumbnail mt-2"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @endif
                            @endforeach
                        @endif


                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Create Staff Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.store') }}" method="POST" id="hireForm">
                        @csrf
                        
                        <input type="hidden" name="employment_application_id" value="{{ $application->id }}">
                        <input type="hidden" name="first_name" value="{{ $application->first_name }}">
                        <input type="hidden" name="surname" value="{{ $application->surname }}">
                        
                        <!-- Employment Details Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-briefcase"></i> Employment Details
                                </h6>
                            </div>
                            
                            <!-- Staff ID (Auto-generated display) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Staff ID (Auto-generated)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light" 
                                               value="STAFF{{ date('Ym') }}{{ str_pad(App\Models\StaffProfile::count() + 1, 4, '0', STR_PAD_LEFT) }}"
                                               readonly>
                                    </div>
                                    <small class="form-text text-muted">Will be automatically generated</small>
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
                                            <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                        <option value="other">Other (Specify)</option>
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="text" 
                                           class="form-control mt-2 d-none" 
                                           id="other_department" 
                                           name="other_department" 
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
                                           value="{{ old('designation', $application->position_applying_for) }}" 
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
                                        <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="probation" {{ old('employment_type') == 'probation' ? 'selected' : '' }}>Probation</option>
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
                                           value="{{ old('date_of_employment', date('Y-m-d')) }}" 
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
                                               value="{{ old('salary') }}" 
                                               placeholder="0.00">
                                    </div>
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Can be set later if not determined</small>
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
                                           value="{{ old('email', $application->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">This will be their login email</small>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Temporary Password *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               value="{{ old('password') }}" 
                                               required>
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
                                    <small class="form-text text-muted">Min 8 characters. User should change on first login.</small>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-1">
                                        <div class="progress" style="height: 5px;">
                                            <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small id="passwordHelp" class="form-text"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User Role -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">User Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="staff" selected>Staff</option>
                                        <option value="manager">Manager</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                    <small class="form-text text-muted">Default is 'Staff'</small>
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
                                        @foreach(App\Models\StaffProfile::with('user')->where('status', 'active')->get() as $supervisor)
                                            <option value="{{ $supervisor->user_id }}">
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
                                           value="{{ old('work_location') }}" 
                                           placeholder="e.g., Head Office, Branch Office">
                                </div>
                            </div>
                            
                            <!-- Work Schedule -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_schedule" class="form-label">Work Schedule</label>
                                    <select class="form-select" id="work_schedule" name="work_schedule">
                                        <option value="">Select Schedule</option>
                                        <option value="9-5">9 AM - 5 PM (Standard)</option>
                                        <option value="8-4">8 AM - 4 PM</option>
                                        <option value="flexible">Flexible Hours</option>
                                        <option value="shift">Shift Work</option>
                                        <option value="remote">Remote</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Probation Period -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="probation_period" class="form-label">Probation Period (Months)</label>
                                    <select class="form-select" id="probation_period" name="probation_period">
                                        <option value="">Select Period</option>
                                        <option value="1">1 Month</option>
                                        <option value="3" selected>3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="0">No Probation</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Initial Notes</label>
                                    <textarea class="form-control" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Any additional notes for HR or the new staff member...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Document Upload Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-file-upload"></i> Initial Documents (Optional)
                                </h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Staff can upload additional documents (certificates, TORs, etc.) through their portal after account creation.
                                </div>
                            </div>
                            
                            <!-- Offer Letter Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="offer_letter" class="form-label">Offer Letter (PDF)</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="offer_letter" 
                                           name="offer_letter" 
                                           accept=".pdf">
                                    <small class="form-text text-muted">Upload signed offer letter if available</small>
                                </div>
                            </div>
                            
                            <!-- Contract Upload -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contract" class="form-label">Employment Contract (PDF)</label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="contract" 
                                           name="contract" 
                                           accept=".pdf">
                                    <small class="form-text text-muted">Upload signed contract if available</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms & Conditions -->
                        <div class="alert alert-warning mb-4">
                            <h6><i class="fas fa-exclamation-triangle"></i> Important Information</h6>
                            <ul class="mb-0">
                                <li>Creating this staff profile will generate login credentials for the new employee</li>
                                <li>An email notification will be sent to the provided email address</li>
                                <li>The employment application status will be automatically updated to "Hired"</li>
                                <li>Staff will be required to change their password on first login</li>
                                <li>Ensure all information is accurate before proceeding</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                {{--    <a href="{{ route('applications.show', $application->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Application
                                    </a>
                                --}}
                                    
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal">
                                            <i class="fas fa-eye"></i> Preview
                                        </button>
                                        <button type="submit" class="btn btn-success" id="submitBtn">
                                            <i class="fas fa-user-check"></i> Create Staff Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="card-title">Total Staff</h6>
                            <h3 class="text-primary">{{ App\Models\StaffProfile::count() }}</h3>
                            <small class="text-muted">Currently employed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="card-title">In Department</h6>
                            <h3 id="deptCount" class="text-success">0</h3>
                            <small class="text-muted" id="deptText">Select a department</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6 class="card-title">New This Month</h6>
                            <h3 class="text-info">{{ App\Models\StaffProfile::whereMonth('created_at', date('m'))->count() }}</h3>
                            <small class="text-muted">{{ date('F Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-file-alt"></i> Staff Profile Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Employee Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td id="previewName">{{ $application->first_name }} {{ $application->surname }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td id="previewEmail">{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td id="previewDepartment">Not selected</td>
                            </tr>
                            <tr>
                                <td><strong>Designation:</strong></td>
                                <td id="previewDesignation">{{ $application->position_applying_for }}</td>
                            </tr>
                            <tr>
                                <td><strong>Employment Type:</strong></td>
                                <td id="previewEmploymentType">Not selected</td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td id="previewStartDate">{{ date('M d, Y') }}</td>
                            </tr>
                        </table>
                        
                        <h6 class="mt-4">Account Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Login Email:</strong></td>
                                <td id="previewLoginEmail">{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>User Role:</strong></td>
                                <td id="previewRole">Staff</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('hireForm').submit()">
                    <i class="fas fa-check"></i> Confirm & Create
                </button>
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
                otherDepartmentInput.required = true;
            } else {
                otherDepartmentInput.classList.add('d-none');
                otherDepartmentInput.required = false;
                otherDepartmentInput.value = '';
            }
            updateDepartmentCount();
        });
        
        // Update preview modal
        function updatePreview() {
            document.getElementById('previewDepartment').textContent = 
                departmentSelect.value === 'other' 
                    ? otherDepartmentInput.value 
                    : departmentSelect.options[departmentSelect.selectedIndex].text;
            document.getElementById('previewDesignation').textContent = 
                document.getElementById('designation').value;
            document.getElementById('previewEmploymentType').textContent = 
                document.getElementById('employment_type').options[document.getElementById('employment_type').selectedIndex].text;
            document.getElementById('previewStartDate').textContent = 
                new Date(document.getElementById('date_of_employment').value).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            document.getElementById('previewLoginEmail').textContent = 
                document.getElementById('email').value;
            document.getElementById('previewRole').textContent = 
                document.getElementById('role').options[document.getElementById('role').selectedIndex].text;
        }
        
        // Update department count
        function updateDepartmentCount() {
            const dept = departmentSelect.value === 'other' 
                ? otherDepartmentInput.value 
                : departmentSelect.value;
            
            if (dept) {
                fetch(`/api/staff/department-count/${encodeURIComponent(dept)}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('deptCount').textContent = data.count || 0;
                        document.getElementById('deptText').textContent = `Staff in ${dept}`;
                    })
                    .catch(() => {
                        document.getElementById('deptCount').textContent = '0';
                        document.getElementById('deptText').textContent = 'Select a department';
                    });
            }
        }
        
        // Password generation
        document.getElementById('generatePassword').addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;
            checkPasswordStrength(password);
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
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let helpText = '';
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const strengthBar = document.getElementById('passwordStrength');
            const helpElement = document.getElementById('passwordHelp');
            
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthBar.style.width = '25%';
                    helpText = 'Very weak password';
                    break;
                case 2:
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthBar.style.width = '50%';
                    helpText = 'Weak password';
                    break;
                case 3:
                    strengthBar.className = 'progress-bar bg-info';
                    strengthBar.style.width = '75%';
                    helpText = 'Good password';
                    break;
                case 4:
                    strengthBar.className = 'progress-bar bg-success';
                    strengthBar.style.width = '100%';
                    helpText = 'Strong password';
                    break;
            }
            
            helpElement.textContent = helpText;
        }
        
        // Real-time password strength checking
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
        
        // Update preview on form changes
        const formElements = ['department', 'designation', 'employment_type', 'date_of_employment', 'email', 'role'];
        formElements.forEach(id => {
            document.getElementById(id).addEventListener('change', updatePreview);
        });
        document.getElementById('designation').addEventListener('input', updatePreview);
        document.getElementById('email').addEventListener('input', updatePreview);
        
        // Form validation
        document.getElementById('hireForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            // Additional validation can be added here
            
            return true;
        });
        
        // Initialize department count
        if (departmentSelect.value) {
            updateDepartmentCount();
        }
        updatePreview();
        
        // Auto-generate password on page load
        document.getElementById('generatePassword').click();
    });
</script>
@endpush

@push('styles')
<style>
    .card-header h5, .card-header h6 {
        font-weight: 600;
    }
    
    .table-sm td {
        padding: 0.5rem;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    .border-bottom {
        border-color: #dee2e6 !important;
    }
    
    .alert ul {
        padding-left: 1.5rem;
        margin-bottom: 0;
    }
    
    .alert li {
        margin-bottom: 0.25rem;
    }
    
    .modal-body h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    #deptCount, .text-primary, .text-success, .text-info {
        font-weight: 700;
    }
</style>
@endpush
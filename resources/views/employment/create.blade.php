@extends('layout.generic')
@section('title', 'Employment Application Form')

@section('content')

    <div class="container py-5">
        <div class="application-form">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">
                    <i class="bi bi-briefcase-fill me-2"></i>Employment Application Form
                </h1>
                <p class="lead text-muted">Complete all sections of this form to apply for employment</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('employment.store') }}" method="POST" enctype="multipart/form-data" id="applicationForm">
                @csrf

                <!-- Section 1: Personal Data -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-person-fill me-2"></i>PERSONAL DATA</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Position Applying For</label>
                                <input type="text" class="form-control" name="position_applying_for" required
                                       value="{{ old('position_applying_for') }}" placeholder="e.g., Sales Manager">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label required">Surname</label>
                                <input type="text" class="form-control" name="surname" required
                                       value="{{ old('surname') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">First Name</label>
                                <input type="text" class="form-control" name="first_name" required
                                       value="{{ old('first_name') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Name</label>
                                <input type="text" class="form-control" name="other_name"
                                       value="{{ old('other_name') }}">
                            </div>
                            
                             <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">Sex</label>
                                <select class="form-select" name="sex" required>
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" required
                                       value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                <small class="form-text text-muted">Must be at least 18 years old</small>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">State of Origin</label>
                                <select class="form-select" name="state_of_origin" required id="state_of_origin">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('state_of_origin') == $state ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">L.G.A.</label>
                                <input type="text" class="form-control" name="lga" required
                                       value="{{ old('lga') }}" placeholder="Enter LGA">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">Marital Status</label>
                                <select class="form-select" name="marital_status" required>
                                    <option value="">Select...</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">Educational Qualification</label>
                                <select class="form-select" name="educational_qualification" required>
                                    <option value="">Select...</option>
                                    <option value="SSCE" {{ old('educational_qualification') == 'SSCE' ? 'selected' : '' }}>SSCE</option>
                                    <option value="OND" {{ old('educational_qualification') == 'OND' ? 'selected' : '' }}>OND</option>
                                    <option value="HND" {{ old('educational_qualification') == 'HND' ? 'selected' : '' }}>HND</option>
                                    <option value="B.Sc" {{ old('educational_qualification') == 'B.Sc' ? 'selected' : '' }}>B.Sc</option>
                                    <option value="B.A" {{ old('educational_qualification') == 'B.A' ? 'selected' : '' }}>B.A</option>
                                    <option value="M.Sc" {{ old('educational_qualification') == 'M.Sc' ? 'selected' : '' }}>M.Sc</option>
                                    <option value="MBA" {{ old('educational_qualification') == 'MBA' ? 'selected' : '' }}>MBA</option>
                                    <option value="PhD" {{ old('educational_qualification') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Residential Address</label>
                                <textarea class="form-control" name="residential_address" required rows="2">{{ old('residential_address') }}</textarea>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">LGA/State</label>
                                <input type="text" class="form-control" name="residential_lga_state" required
                                       value="{{ old('residential_lga_state') }}" placeholder="e.g., Ikeja, Lagos">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label required">Contact Number</label>
                                <input type="tel" class="form-control" name="contact_number" required
                                       value="{{ old('contact_number') }}" placeholder="e.g., 08012345678">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Next of Kin -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>NEXT OF KIN</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Next of Kin Name</label>
                                <input type="text" class="form-control" name="next_of_kin_name" required
                                       value="{{ old('next_of_kin_name') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Relationship with Next of Kin</label>
                                <select class="form-select" name="next_of_kin_relationship" required>
                                    <option value="">Select...</option>
                                    <option value="Spouse" {{ old('next_of_kin_relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                    <option value="Parent" {{ old('next_of_kin_relationship') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                    <option value="Sibling" {{ old('next_of_kin_relationship') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                    <option value="Child" {{ old('next_of_kin_relationship') == 'Child' ? 'selected' : '' }}>Child</option>
                                    <option value="Other" {{ old('next_of_kin_relationship') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Contact Number</label>
                                <input type="tel" class="form-control" name="next_of_kin_contact" required
                                       value="{{ old('next_of_kin_contact') }}">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label required">Next of Kin's Residential Address</label>
                                <textarea class="form-control" name="next_of_kin_address" required rows="2">{{ old('next_of_kin_address') }}</textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Parent/Guardian Name (if different from Next of Kin)</label>
                                <input type="text" class="form-control" name="parent_guardian_name"
                                       value="{{ old('parent_guardian_name') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Parent/Guardian Address</label>
                                <textarea class="form-control" name="parent_guardian_address" rows="2">{{ old('parent_guardian_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Educational Qualifications (Dynamic) -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-mortarboard-fill me-2"></i>EDUCATIONAL QUALIFICATIONS (Starting with the most Recent)</h4>
                    </div>
                    <div class="card-body">
                        <div id="educationalQualifications">
                            @for($i = 0; $i < 3; $i++)
                            <div class="dynamic-row">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Institution</label>
                                        <input type="text" class="form-control" name="educational_institution[]"
                                               value="{{ old('educational_institution.' . $i) }}" placeholder="e.g., University of Lagos">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Qualifications</label>
                                        <input type="text" class="form-control" name="educational_qualification_type[]"
                                               value="{{ old('educational_qualification_type.' . $i) }}" placeholder="e.g., B.Sc Computer Science">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date Obtained</label>
                                        <input type="date" class="form-control" name="educational_date_obtained[]"
                                               value="{{ old('educational_date_obtained.' . $i) }}">
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-add-row" onclick="addEducationalRow()">
                            <i class="bi bi-plus-circle me-1"></i>Add Another Qualification
                        </button>
                    </div>
                </div>

                <!-- Section 4: Professional Qualifications (Dynamic) -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-award-fill me-2"></i>PROFESSIONAL QUALIFICATION (Starting with the most Recent)</h4>
                    </div>
                    <div class="card-body">
                        <div id="professionalQualifications">
                            @for($i = 0; $i < 2; $i++)
                            <div class="dynamic-row">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Name of Professional Body</label>
                                        <input type="text" class="form-control" name="professional_body[]"
                                               value="{{ old('professional_body.' . $i) }}" placeholder="e.g., Institute of Chartered Accountants">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Grade of Membership</label>
                                        <input type="text" class="form-control" name="professional_membership_grade[]"
                                               value="{{ old('professional_membership_grade.' . $i) }}" placeholder="e.g., Member, Fellow">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Year Obtained</label>
                                        <input type="date" class="form-control" name="professional_year_obtained[]"
                                               value="{{ old('professional_year_obtained.' . $i) }}">
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-add-row" onclick="addProfessionalRow()">
                            <i class="bi bi-plus-circle me-1"></i>Add Another Professional Qualification
                        </button>
                    </div>
                </div>

                <!-- Section 5: Employment History (Dynamic) -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>EMPLOYMENT HISTORY (from most recent including contract job)</h4>
                    </div>
                    <div class="card-body">
                        <div id="employmentHistory">
                            @for($i = 0; $i < 2; $i++)
                            <div class="dynamic-row">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">ORGANIZATION NAME</label>
                                        <input type="text" class="form-control" name="employment_organization[]"
                                               value="{{ old('employment_organization.' . $i) }}" placeholder="e.g., ABC Company Limited">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ADDRESS</label>
                                        <input type="text" class="form-control" name="employment_address[]"
                                               value="{{ old('employment_address.' . $i) }}" placeholder="Office Address">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">PHONE NO</label>
                                        <input type="tel" class="form-control" name="employment_phone[]"
                                               value="{{ old('employment_phone.' . $i) }}" placeholder="Office Phone">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">POSITION</label>
                                        <input type="text" class="form-control" name="employment_position[]"
                                               value="{{ old('employment_position.' . $i) }}" placeholder="e.g., Sales Manager">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">FROM DATE</label>
                                        <input type="date" class="form-control" name="employment_from_date[]"
                                               value="{{ old('employment_from_date.' . $i) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">TO DATE</label>
                                        <input type="date" class="form-control" name="employment_to_date[]"
                                               value="{{ old('employment_to_date.' . $i) }}">
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-add-row" onclick="addEmploymentRow()">
                            <i class="bi bi-plus-circle me-1"></i>Add Another Employment
                        </button>
                    </div>
                </div>

               

                <!-- Section 7: Document Uploads -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>DOCUMENTS UPLOAD</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Passport Photo -->
                            <div class="col-md-6">
                                <label class="form-label required">Passport Photograph</label>
                                <div class="file-upload" onclick="document.getElementById('passport_photo').click()">
                                    <i class="bi bi-camera fs-1 text-muted"></i>
                                    <p class="mb-1">Click to upload passport photo</p>
                                    <small class="text-muted">JPG/PNG (Max 2MB)</small>
                                    <input type="file" class="d-none" id="passport_photo" name="passport_photo" accept="image/*" required
                                           onchange="previewFile(this, 'passportPreview')">
                                </div>
                                <div id="passportPreview" class="mt-2"></div>
                            </div>
                            
                            <!-- Resume/CV -->
                            <div class="col-md-6">
                                <label class="form-label required">Resume/CV</label>
                                <div class="file-upload" onclick="document.getElementById('resume').click()">
                                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                                    <p class="mb-1">Click to upload resume</p>
                                    <small class="text-muted">PDF/DOC/DOCX (Max 5MB)</small>
                                    <input type="file" class="d-none" id="resume" name="resume" accept=".pdf,.doc,.docx" required
                                           onchange="previewFile(this, 'resumePreview')">
                                </div>
                                <div id="resumePreview" class="mt-2"></div>
                            </div>
                            
                            <!-- Certificates -->
                            <div class="col-md-6">
                                <label class="form-label required">Educational/Professional Certificates</label>
                                <div class="file-upload" onclick="document.getElementById('certificates').click()">
                                    <i class="bi bi-files fs-1 text-muted"></i>
                                    <p class="mb-1">Click to upload certificates</p>
                                    <small class="text-muted">PDF/JPG/PNG (Max 5MB each, at least 1 file)</small>
                                    <input type="file" class="d-none" id="certificates" name="certificates[]" accept=".pdf,.jpg,.jpeg,.png" multiple 
                                           onchange="previewMultipleFiles(this, 'certificatesPreview')">
                                </div>
                                <div id="certificatesPreview" class="mt-2"></div>
                            </div>
                            
                            <!-- Means of Identification -->
                            <div class="col-md-6">
                                <label class="form-label required">Means of Identification</label>
                                <div class="file-upload" onclick="document.getElementById('identification').click()">
                                    <i class="bi bi-card-checklist fs-1 text-muted"></i>
                                    <p class="mb-1">Click to upload ID documents</p>
                                    <small class="text-muted">International Passport, Driver's License, Voter's Card, NIN (Max 5MB each, at least 1 file)</small>
                                    <input type="file" class="d-none" id="identification" name="means_of_identification[]" accept=".pdf,.jpg,.jpeg,.png" multiple required
                                           onchange="previewMultipleFiles(this, 'identificationPreview')">
                                </div>
                                <div id="identificationPreview" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 8: Declaration -->
                <div class="card mb-4 section-card">
                    <div class="card-header section-header">
                        <h4 class="mb-0"><i class="bi bi-pen-fill me-2"></i>DECLARATION</h4>
                    </div>
                    <div class="card-body">
                        <div class="declaration-box mb-4">
                            <p class="mb-0">
                                I <strong id="declarationName"></strong>, declare that the above details are correct and true to the best of my knowledge and I acknowledge that I am liable to any action against me by the Organization if, at any point in time during my employment with the Organization, any of the above details are found to be untrue.
                            </p>
                        </div>
                        <div class="row g-3">
                            <!-- In your employment form -->
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label required">Digital Signature</label>
                                        <p class="text-muted small">Draw your signature in the box below</p>
                                        
                                        @component('components.signature-pad', ['name' => 'declaration_signature'])
                                        @endcomponent
                                    </div>

                            
                            <div class="col-md-6">
                                <label class="form-label required">Date</label>
                                <input type="date" class="form-control" name="declaration_date" required
                                       value="{{ old('declaration_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mb-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-send-check me-2"></i>Submit Application
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg ms-3">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
    
@endsection

@push('styles')
    <style>
        body { background-color: #f8f9fa; }
        .application-form { max-width: 1200px; margin: 0 auto; }
        .section-card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section-header { background: #90c74b; color: white; border-radius: 10px 10px 0 0; }
        .required:after { content: " *"; color: #dc3545; }
        .file-upload { border: 2px dashed #dee2e6; border-radius: 5px; padding: 20px; text-align: center; cursor: pointer; }
        .file-upload:hover { border-color: #667eea; }
        .file-preview { max-width: 100px; max-height: 100px; object-fit: cover; }
        .dynamic-row { border-bottom: 1px solid #dee2e6; padding-bottom: 15px; margin-bottom: 15px; }
        .btn-add-row { margin-top: 10px; }
        .declaration-box { background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; }
        .guarantor-section { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    </style>
@endpush

@push('scripts')
  <script>
        // Update declaration name in real-time
        function updateDeclarationName() {
            const signature = document.getElementById('declarationSignature').value;
            document.getElementById('declarationName').textContent = signature;
        }
        
        // File preview functions
        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'file-preview rounded border';
                        preview.appendChild(img);
                    } else {
                        const div = document.createElement('div');
                        div.className = 'alert alert-info p-2';
                        div.innerHTML = `<i class="bi bi-file-earmark-text me-2"></i>${file.name} (${formatBytes(file.size)})`;
                        preview.appendChild(div);
                    }
                }
                
                reader.readAsDataURL(file);
            }
        }
        
        function previewMultipleFiles(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const div = document.createElement('div');
                    div.className = 'mb-1';
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'file-preview rounded border me-2';
                            img.style.maxWidth = '80px';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        div.innerHTML = `<span class="badge bg-secondary"><i class="bi bi-file-earmark-pdf me-1"></i>${file.name}</span>`;
                        preview.appendChild(div);
                    }
                }
            }
        }
        
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
        
        // Dynamic form row functions
        let eduRowCount = 3;
        let profRowCount = 2;
        let empRowCount = 2;
        let guarantorCount = 2;
        
        function addEducationalRow() {
            const container = document.getElementById('educationalQualifications');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-row';
            newRow.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="educational_institution[]" placeholder="e.g., University of Lagos">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="educational_qualification_type[]" placeholder="e.g., B.Sc Computer Science">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="educational_date_obtained[]">
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            eduRowCount++;
        }
        
        function addProfessionalRow() {
            const container = document.getElementById('professionalQualifications');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-row';
            newRow.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="professional_body[]" placeholder="e.g., Institute of Chartered Accountants">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="professional_membership_grade[]" placeholder="e.g., Member, Fellow">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="professional_year_obtained[]">
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            profRowCount++;
        }
        
        function addEmploymentRow() {
            const container = document.getElementById('employmentHistory');
            const newRow = document.createElement('div');
            newRow.className = 'dynamic-row';
            newRow.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="employment_organization[]" placeholder="e.g., ABC Company Limited">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="employment_address[]" placeholder="Office Address">
                    </div>
                    <div class="col-md-6">
                        <input type="tel" class="form-control" name="employment_phone[]" placeholder="Office Phone">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="employment_position[]" placeholder="e.g., Sales Manager">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="employment_from_date[]">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="employment_to_date[]">
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            empRowCount++;
        }
        
        function addGuarantor() {
            const container = document.getElementById('guarantorsContainer');
            guarantorCount++;
            
            const newSection = document.createElement('div');
            newSection.className = 'guarantor-section';
            newSection.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Guarantor #${guarantorCount}</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGuarantor(this)">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_name[]" required placeholder="Guarantor's Name">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_address[]" required placeholder="Residential Address">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_lga_state[]" required placeholder="LGA/State">
                    </div>
                    <div class="col-md-4">
                        <input type="tel" class="form-control" name="guarantor_residential_phone[]" required placeholder="Residential Phone">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_occupation[]" required placeholder="Occupation">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_office_address[]" required placeholder="Office Address">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_designation[]" required placeholder="Designation">
                    </div>
                    <div class="col-md-4">
                        <input type="tel" class="form-control" name="guarantor_office_phone[]" required placeholder="Office Phone">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="guarantor_years_known[]" required min="1" placeholder="Years" value="2">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="guarantor_relationship[]" required placeholder="Relationship">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="guarantor_signature[]" required placeholder="Full Name as Signature">
                    </div>
                </div>
            `;
            container.appendChild(newSection);
        }
        
        function removeGuarantor(button) {
            if (guarantorCount > 2) {
                button.closest('.guarantor-section').remove();
                guarantorCount--;
                
                // Update numbering
                const sections = document.querySelectorAll('.guarantor-section');
                sections.forEach((section, index) => {
                    const title = section.querySelector('h5');
                    if (title) {
                        title.textContent = `Guarantor #${index + 1}`;
                    }
                });
            } else {
                alert('Minimum of 2 guarantors required.');
            }
        }
        
        // Form validation before submit
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            // Validate at least 2 guarantors
            // const guarantorInputs = document.querySelectorAll('input[name="guarantor_name[]"]');
            // let filledGuarantors = 0;
            
            // guarantorInputs.forEach(input => {
            //     if (input.value.trim()) {
            //         filledGuarantors++;
            //     }
            // });
            
            // if (filledGuarantors < 2) {
            //     e.preventDefault();
            //     alert('Please provide at least 2 guarantors.');
            //     return false;
            // }
            
            // Validate file sizes
            const maxSize = 5 * 1024 * 1024; // 5MB
            const files = [
                ...document.getElementById('passport_photo').files,
                ...document.getElementById('resume').files,
                ...document.getElementById('certificates').files,
                ...document.getElementById('identification').files
            ];
            
            for (let file of files) {
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                    return false;
                }
            }
            
            return true;
        });
        
        // Initialize declaration name
        updateDeclarationName();
    </script>
@endpush


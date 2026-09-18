@extends('layout.generic')
@section('title', 'Application Submitted Successfully - Agii')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white py-4">
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h1 class="h2 mb-0">Application Submitted Successfully!</h1>
                        <p class="mb-0 opacity-75">Your employment application has been received</p>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <!-- Application Summary -->
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <h3 class="h4 text-primary">Application Reference</h3>
                            <div class="d-inline-block bg-light border rounded-pill px-4 py-2">
                                <code class="h5 text-success fw-bold">{{ $application->application_reference ?? 'EMP-' . $application->id . '-' . strtoupper(substr($application->surname, 0, 3)) }}</code>
                            </div>
                        </div>
                        
                        <p class="lead text-muted mb-4">
                            Thank you <strong>{{ $application->first_name . ' ' . $application->surname }}</strong> for applying for the position of <strong>{{ $application->position_applying_for }}</strong>.
                        </p>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please keep your application reference for future correspondence.
                        </div>
                    </div>
                    
                    <!-- Application Details -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Applicant Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th class="border-0">Full Name:</th>
                                            <td class="border-0">{{ $application->surname . ' ' . $application->first_name . ' ' . ($application->other_name ?? '') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Position:</th>
                                            <td>{{ $application->position_applying_for }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact:</th>
                                            <td>{{ $application->contact_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date Applied:</th>
                                            <td>{{ $application->created_at->format('F j, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Next Steps</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-success text-white">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="fw-bold">Application Submitted</h6>
                                                <p class="small text-muted mb-0">Your application is now in our system</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-light border text-muted">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="fw-bold">Under Review</h6>
                                                <p class="small text-muted mb-0">HR team will review your application</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-light border text-muted">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="fw-bold">Contact & Interview</h6>
                                                <p class="small text-muted mb-0">If shortlisted, we'll contact you</p>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-step">
                                            <div class="timeline-icon bg-light border text-muted">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="fw-bold">Final Decision</h6>
                                                <p class="small text-muted mb-0">Employment offer if successful</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Important Notes -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    An email confirmation has been sent to you (if email was provided)
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Your application will be reviewed within 5-7 working days
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Please ensure your contact information is correct and accessible
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Keep your application reference for any inquiries
                                </li>
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    You may be contacted for additional documentation
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="text-center mt-5">
                        <div class="row g-3 justify-content-center">
                            <div class="col-auto">
                                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-home me-2"></i>Return to Homepage
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('employment.apply') }}" class="btn btn-outline-success btn-lg px-4">
                                    <i class="fas fa-plus me-2"></i>Submit Another Application
                                </a>
                            </div>
                            <div class="col-auto">
                                <button onclick="window.print()" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-print me-2"></i>Print This Page
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted mb-2">
                                Need to contact us about your application?
                            </p>
                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-2">
                                <i class="fas fa-phone-alt text-primary me-2"></i>
                                <!--<span>Call: <strong>+234 800 123 4567</strong></span>-->
                                <span class="mx-2">|</span>
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span>Email: <strong>contact@agii.ng</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="card-footer bg-light py-3 text-center">
                    <p class="small text-muted mb-0">
                        <i class="fas fa-lock me-1"></i>
                        Your application data is secured and confidential. We respect your privacy.
                    </p>
                </div>
            </div>
            
            <!-- Application Receipt (Print Only) -->
            <div class="d-none d-print-block mt-5">
                <h3 class="text-center mb-4">Application Receipt</h3>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Application Reference:</th>
                        <td>{{ $application->application_reference ?? 'EMP-' . $application->id }}</td>
                    </tr>
                    <tr>
                        <th>Applicant Name:</th>
                        <td>{{ $application->surname . ' ' . $application->first_name . ' ' . ($application->other_name ?? '') }}</td>
                    </tr>
                    <tr>
                        <th>Position Applied For:</th>
                        <td>{{ $application->position_applying_for }}</td>
                    </tr>
                    <tr>
                        <th>Date Submitted:</th>
                        <td>{{ $application->created_at->format('F j, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Application Status:</th>
                        <td><span class="badge bg-warning">Pending Review</span></td>
                    </tr>
                </table>
                
                <div class="mt-4">
                    <p class="text-muted small">
                        This is an automated receipt for your employment application. Please keep this for your records.
                    </p>
                    <hr>
                    <p class="text-center small">
                        AGII Recruitment Portal • {{ date('F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    
    .timeline-step {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -30px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    
    .timeline-content {
        margin-left: 10px;
    }
    
    @media print {
        .d-print-block {
            display: block !important;
        }
        
        .btn, .timeline:before, .timeline-icon, .alert, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background: #fff !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }
    }
    
    @media (max-width: 768px) {
        .timeline {
            padding-left: 25px;
        }
        
        .timeline-icon {
            width: 25px;
            height: 25px;
            left: -25px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-scroll to top on page load
        window.scrollTo(0, 0);
        
        // Add print styles
        const printStyle = document.createElement('style');
        printStyle.textContent = `
            @media print {
                body * {
                    visibility: hidden;
                }
                .container, .container * {
                    visibility: visible;
                }
                .container {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
            }
        `;
        document.head.appendChild(printStyle);
        
        // Copy application reference to clipboard
        const copyReference = document.getElementById('copyReference');
        if (copyReference) {
            copyReference.addEventListener('click', function() {
                const reference = "{{ $application->application_reference ?? 'EMP-' . $application->id . '-' . strtoupper(substr($application->surname, 0, 3)) }}";
                
                navigator.clipboard.writeText(reference).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-secondary');
                    }, 2000);
                });
            });
        }
        
        // Confetti animation on success
        if (typeof confetti === 'function') {
            setTimeout(() => {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }, 500);
        }
    });
</script>

<!-- Optional: Confetti library for celebration effect -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
@endpush
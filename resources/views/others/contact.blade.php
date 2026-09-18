@extends('layout.layout')
@section('title', 'Agii Contact Us- Buy and Sell Anything in Nigeria')

@section('content')
<main class="main">
    <div class="contact-hero d-flex flex-column justify-content-center align-items-center text-center">
        <h1 class="mb-3">Contact Us</h1>
        <p class="lead">Keep in touch with us</p>
    </div>

    <div class="page-content pb-0">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="title mb-4">Contact Information</h2>
                    <p class="mb-4">Stay connected with us! Our support team is here to assist you. Whether you have inquiries, feedback, or require assistance, we are happy to help. Reach out to us anytime.</p>
                    
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="contact-info card border-0 shadow-sm p-4 mb-4">
                                <h3 class="mb-3 text-primary">
                                    <i class="fas fa-building me-2"></i>The Office
                                </h3>
                                <ul class="contact-list list-unstyled">
                                    <li class="mb-3">
                                        <i class="icon-map-marker text-primary me-2"></i>
                                        <strong>Address:</strong> Lagos, Nigeria
                                    </li>
                                    <li class="mb-3">
                                        <i class="icon-phone text-primary me-2"></i>
                                        <strong>Phone:</strong> 
                                        <a href="tel:+2349135000739" class="text-decoration-none">+234 913 500 0739</a>
                                    </li>
                                    <li class="mb-3">
                                        <i class="icon-envelope text-primary me-2"></i>
                                        <strong>Email:</strong> 
                                        <a href="mailto:support@agii.ng" class="text-decoration-none">support@agii.ng</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="contact-info card border-0 shadow-sm p-4">
                                <h3 class="mb-3 text-primary">
                                    <i class="fas fa-headset me-2"></i>Customer Service
                                </h3>
                                <ul class="contact-list list-unstyled">
                                    <li class="mb-3">
                                        <i class="icon-phone text-primary me-2"></i>
                                        <strong>Phone:</strong> 
                                        <a href="tel:+2349135000738" class="text-decoration-none">+234 913 500 0738</a>
                                    </li>
                                    <li class="mb-3">
                                        <i class="icon-clock-o text-primary me-2"></i>
                                        <strong>Service Hours:</strong> 
                                        <span class="text-dark">24/7 Support</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <h4 class="mb-3">Find Us On</h4>
                        <div class="social-icons">
                            <a href="#" class="social-icon social-facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon social-twitter" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon social-instagram" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon social-linkedin" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="title mb-3">Got Any Questions?</h2>
                        <p class="mb-4">Use the form below to get in touch with the sales team</p>

                        <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          cols="30" rows="5" id="message" name="message" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <span>SEND MESSAGE</span>
                                <i class="icon-long-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
  <style>
.contact-hero {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 0;
    height: 30vh;
    min-height: 250px;
}

.contact-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    color: #333;
}

.contact-hero p {
    font-size: 1.25rem;
    color: #666;
}

.contact-info .contact-list li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.contact-info .contact-list li:last-child {
    border-bottom: none;
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    color: #333;
    transition: all 0.3s ease;
}

.social-icon:hover {
    transform: translateY(-3px);
    color: #fff;
}

.social-facebook:hover { background: #1877f2; }
.social-twitter:hover { background: #1da1f2; }
.social-instagram:hover { background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d); }
.social-linkedin:hover { background: #0077b5; }

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.form-control {
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>
@endpush

@push('scripts')
    
@endpush

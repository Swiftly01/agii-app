@extends('layout.layout')
@section('title', 'About Agii - Buy and Sell Anything in Nigeria')

@section('content')
    

     <main class="main">
        <div class='bg-white'>    
           <div class="d-flex justify-content-center align-items-center" style="height: 30vh;">
                <h3 class="page-title text-black">About us</h3>
            </div>


            <div class="page-content pb-0">
                <div class="container">
                    <div class="row bg-white">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <h2 class="title">AGII NG</h2><!-- End .title -->
                            <p>Welcome to Agii.ng, Nigeria’s premier E-Commerce and technology platform, designed to simplify your connection with trusted service providers and vendors across the nation. Whether you're looking for a skilled artisan, a reliable handyman, a seasoned professional, or a dependable manufacturer, Agii.ng ensures that you find the closest and most qualified provider to meet your needs; all at the click of a button.
                            </p>
                        </div><!-- End .col-lg-6 -->
                        
                        <div class="col-lg-6">
                            <h2 class="title">Our Mission</h2><!-- End .title -->
                            <p>At Agii.ng, we aim to bridge the gap between service providers and customers by offering a platform that’s efficient, reliable, and easy to use. Our goal is to empower users with convenience and vendors with visibility, fostering connections that create value for all.
                            </p><br>
                            <h2 class="title">Our Vision</h2><!-- End .title -->
                            <p>To be the go-to platform for seamless connections, empowering businesses and enhancing customer experiences.”
                            </p>
                        </div><!-- End .col-lg-6 -->
                    </div><!-- End .row -->

                    <div class="mb-5"></div><!-- End .mb-4 -->
                </div><!-- End .container -->
            </div>
            
        </div>
                <div class="bg-light-2 pt-6 pb-5 mb-6 mb-lg-8">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 mb-3 mb-lg-0">
                                <h2 class="title">What Makes Agii.ng Unique?</h2><!-- End .title -->
                                <p class="mb-2"><strong>Hyperlocal Connections:</strong> With our cutting-edge location technology, Agii.ng connects you with service providers as close as 10 meters from your location. This means faster response times, reduced costs, and more personalized services, right in your neighborhood.<br>
                                    <br><strong>Seamless Google Maps Integration:</strong> Our platform features built-in Google Maps functionality, making it effortless to locate vendors or service providers. Whether you prefer online transactions or in-person engagements, navigating to your preferred vendor has never been easier.<br>
                                    <br><strong>User-Friendly Design:</strong> Our intuitive interface ensures that you can effortlessly search, compare, and select the best providers for your specific needs. We prioritize convenience, so you can focus on what matters most.
                                     </p>
                            </div><!-- End .col-lg-5 -->

                            <div class="col-lg-6 offset-lg-1">
                                <div class="about-images">
                                    <img src="https://agii.ng/frontend/assets/images/demos/demo-14/banners/banner-10.png" alt="" class="about-img-front" style="width: 400px;">
                                    <img src="https://agii.ng/frontend/assets/images/demos/demo-14/banners/banner-1.png" alt="" class="about-img-back" style="width: 400px; left:400;">
                                </div><!-- End .about-images -->
                            </div><!-- End .col-lg-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .bg-light-2 pt-6 pb-6 -->

               

                <div class="about-testimonials bg-light-2 pt-6 pb-6">
                    <div class="container">
                        <h2 class="title text-center mb-3">Explore Agii.ng – Where Convenience Meets Connection!
                        </h2><!-- End .title text-center -->

                        <div class="owl-carousel owl-simple owl-testimonials-photo" data-toggle="owl" >
                           <div class="testimonial text-center">
                                <p>Your journey to finding trusted service providers and promoting your business starts here. Dive in and experience the future of seamless, location-driven e-commerce in Nigeria.</p>
                            
                           </div>
            
                        </div><!-- End .testimonials-slider owl-carousel -->
                    </div><!-- End .container -->
                </div><!-- End .bg-light-2 pt-5 pb-6 -->
            </div><!-- End .page-content -->
        </main><!-- End .main -->
@endsection

@push('styles')
  
@endpush

@push('scripts')
    
@endpush

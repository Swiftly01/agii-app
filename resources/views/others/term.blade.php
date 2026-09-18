@extends('layout.layout')
@section('title', 'Agii Terms & Condidtion - Buy and Sell Anything in Nigeria')

@section('content')
        <main class="terms-container">
        <h1 class="terms-title">AGII.NG Terms and Conditions</h1>
        <p class="effective-date">Effective Date: April 2025</p>

        <div class="terms-section">
            <p>Welcome to AGII.NG. These Terms and Conditions ("Terms") govern your use of our e-commerce marketplace and associated services, including the purchase of products, service bookings, and ride-hailing features (collectively, the "Services"). By accessing or using AGII.NG, you agree to be bound by these Terms.</p>
            <p>Please read carefully before using the platform.</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By using AGII.NG, you agree to comply with these Terms, all applicable laws, and our Privacy Policy. If you do not agree, you may kindly decline from using our Services.</p>

            <h2>2. Platform Overview</h2>
            <p>AGII.NG operates as a digital marketplace that connects users with:</p>
            <ul>
                <li><strong>Products:</strong> Vendors can list physical or digital goods for sale.</li>
                <li><strong>Services:</strong> Users can request or offer professional services.</li>
                <li><strong>Ride:</strong> Users can book rides through approved transport partners.</li>
            </ul>
            <p>AGII.NG acts solely as an intermediary and does not participate in any transaction between users and vendors or service providers.</p>

            <h2>3. User Eligibility and Responsibility</h2>
            <p>You must be at least 18 years old or have legal guardian consent to use AGII.NG. You are responsible for maintaining the confidentiality of your account credentials.</p>

            <h2>4. Vendor and Service Provider Responsibilities</h2>
            <p>Vendors and service providers must ensure all listings are accurate complies AGII.NG's listing policies and with Nigerian laws.</p>
            <p>You may not list prohibited items, expired products or provide fraudulent or misleading services.</p>
            <p>AGII.NG reserves the right to remove any listing or suspend accounts that violate our terms.</p>

            <h2>5. Order, Payment, and Delivery</h2>
            <p>All purchases made through AGII.NG must be paid via approved payment methods.</p>
            <p>Delivery times and service schedules are subject to vendor or provider availability.</p>
            <p>AGII.NG is not liable for delays or damages resulting from third-party delivery or service execution.</p>

            <h2>6. Ride Services</h2>
            <p>Rides are offered by independent drivers registered on the platform.</p>
            <p>AGII.NG does not own or operate the vehicles and bears no responsibility for driver conduct or performance.</p>
            <p>Users must ensure the accuracy of pickup and drop-off details.</p>
            <p>Cancellations may incur a fee depending on timing and driver policy.</p>

            <h2>7. Refunds and Disputes</h2>
            <p>Refund eligibility is subject to the vendor or provider's individual policy and applicable laws.</p>
            <p>Users can report disputes via the platform. AGII.NG will mediate in good faith but is not liable for transaction outcomes.</p>

            <h2>8. Prohibited Conduct</h2>
            <p>Users may not:</p>
            <ul>
                <li>Use the platform for unlawful or harmful activities</li>
                <li>Post false or misleading content</li>
                <li>Interfere with system security or operations</li>
                <li>Exploit the platform for fraudulent purposes</li>
            </ul>

            <h2>9. Intellectual Property</h2>
            <p>All trademarks, content, and platform designs are the property of AGII.NG or its licensors. Users may not reproduce, copy, or distribute platform content without written consent.</p>

            <h2>10. Limitation of Liability</h2>
            <p>AGII.NG shall not be liable for:</p>
            <ul>
                <li>Losses resulting from user transactions or third-party actions</li>
                <li>Errors or delays in the platform caused by technical issues</li>
                <li>Indirect or consequential damages</li>
            </ul>

            <h2>11. Account Suspension and Termination</h2>
            <p>We reserve the right to suspend or terminate user accounts for violation of these Terms or other malicious behavior.</p>

            <h2>12. Privacy Policy</h2>
            <p>Your personal information is collected and used in accordance with our Privacy Policy. Please review the policy for more details on data protection.</p>

            <h2>13. Modifications</h2>
            <p>AGII.NG may update these Terms at any time. Users will be notified of significant changes, and continued use of the platform implies acceptance of the revised Terms.</p>

            <h2>14. Governing Law</h2>
            <p>These Terms are governed by the laws of the Federal Republic of Nigeria. Disputes shall be resolved in Nigerian courts.</p>

            <div class="contact-info">
                <h2>Contact Us</h2>
                <p>For inquiries, support, or complaints, please contact:</p>
                <p>Email: <a href="mailto:support@agiing.ng">support@agii.ng</a></p>
                <p>Phone: +2349135000738</p>
            </div>
        </div>
    </main>
       
        
@endsection

@push('styles')
  <style>

.btn-link {
    display: inline-block;
    padding: 8px 16px;
    margin: 4px;
    background-color: #8fc74a;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-link:hover {
    background-color: #0056b3;
}


/* Remove hover effects from all links inside the ooter */
.footer a {
    text-decoration: none !important;
    color: white !important;
    transition: none !important;
}

.footer a:hover {
    color: white !important;
    text-decoration: none !important;
}

/* Remove hover effects from social icons */
.footer .social-icons a {
    background: none !important;
    transition: none !important;
}

.footer .social-icons a:hover {
    background: none !important;
    transform: none !important;
}

.sticky-header {
            position: sticky;
            top: 0;
            width: 100%;
            background: #333 !important; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;  
            z-index: 1000;
            transition: background 0.3s ease-in-out;
        }



      /*Hide navbar on mobile screens (below 768px) */
        @media (max-width: 768px) {
            .sticky-header {
                display: none;
            }
        }

    :root {
            --primary-color: #8fc74a;
            --secondary-color: #2c3e50;
            --accent-color: #3498db;
            --text-color: #333;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        .header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        
        .header-top {
            background-color: #f8f8f8;
            padding: 10px 0;
        }
        
        .containe {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .header-logo {
            text-align: center;
            margin: 15px 0;
        }
        
        .header-logo img {
            max-height: 50px;
        }
        
        /* Terms and Conditions Styles */
        .terms-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .terms-title {
            color: var(--secondary-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .effective-date {
            font-style: italic;
            color: #666;
            margin-bottom: 30px;
        }
        
        .terms-section h2 {
            color: var(--accent-color);
            margin-top: 25px;
            font-size: 1.5rem;
        }
        
        .terms-section ul {
            padding-left: 20px;
        }
        
        .terms-section li {
            margin-bottom: 8px;
        }
        
        .contact-info {
            margin-top: 40px;
            padding: 20px;
            background-color: var(--light-bg);
            border-left: 4px solid var(--accent-color);
        }
        
        .contact-info a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 30px 0;
            /*text-align: center;*/
        }
        
        /* Mobile Navigation */
        .bottom-nav {
            display: none;
        }
        
        @media (max-width: 768px) {
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                justify-content: space-around;
                align-items: center;
                padding: 10px 0;
                z-index: 1000;
            }
            
            .nav-item {
                color: white;
                font-size: 24px;
            }
        }

</style>
@endpush

@push('scripts')
    
@endpush

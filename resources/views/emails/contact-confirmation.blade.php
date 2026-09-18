<!DOCTYPE html>
<html>
<head>
    <title>Thank you for contacting us</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #667eea;">Thank you for contacting AGII!</h2>
        
        <p>Dear {{ $data['name'] }},</p>
        
        <p>We have received your message and our team will get back to you within 24 hours.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Your Message Summary:</strong></p>
            <div style="background: white; padding: 15px; border-left: 4px solid #667eea;">
                {{ $data['message'] }}
            </div>
        </div>
        
        <p><strong>Our Contact Information:</strong></p>
        <ul>
            <li>Email: support@agii.ng</li>
            <li>Phone: +234 913 500 0739</li>
            <li>Address: Lagos, Nigeria</li>
        </ul>
        
        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>The AGII Team</strong>
        </p>
    </div>
</body>
</html>
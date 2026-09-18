<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #333;">New Contact Form Submission</h2>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px;">
            <p><strong>Name:</strong> {{ $data['name'] }}</p>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            <p><strong>Phone:</strong> {{ $data['phone'] ?? 'Not provided' }}</p>
            <p><strong>Subject:</strong> {{ $data['subject'] ?? 'No subject' }}</p>
            <p><strong>Message:</strong></p>
            <div style="background: white; padding: 15px; border-left: 4px solid #667eea; margin: 10px 0;">
                {{ $data['message'] }}
            </div>
        </div>
        
        <p style="margin-top: 20px;">
            <small>This message was sent from the contact form on your website.</small>
        </p>
    </div>
</body>
</html>
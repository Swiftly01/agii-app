{{-- resources/views/emails/offer-letter.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Offer Letter - {{ $document->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Offer Letter</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $staff->user->name }},</p>
            
            <p>Please find your offer letter attached. The details are as follows:</p>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Title:</strong></td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $document->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Effective Date:</strong></td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        {{ date('F d, Y', strtotime($document->effective_date)) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Sent By:</strong></td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        {{ $document->reviewer->name ?? 'HR Department' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Sent Date:</strong></td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        {{ $document->created_at->format('F d, Y h:i A') }}
                    </td>
                </tr>
            </table>
            
            @if($customMessage)
                <div style="background: #e8f4ff; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff;">
                    <strong>Message from HR:</strong>
                    <p style="margin: 10px 0 0 0;">{{ $customMessage }}</p>
                </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('staff.offer-letters.download', $document->id) }}" class="btn">
                    Download Offer Letter
                </a>
            </div>
            
            <p>You can also view this document anytime from your staff portal.</p>
            
            <p>Best regards,<br>
            Human Resources Department</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
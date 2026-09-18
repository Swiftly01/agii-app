<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offer Letter - {{ $staff->staff_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            background: white;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9em;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .message-box {
            background: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Offer Letter Notification</h2>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <p>Dear {{ $staff->user->name ?? 'Valued Employee' }},</p>
        
        <p>A new offer letter has been issued to you. Please find the details below:</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Title:</strong> {{ $document->title }}</p>
            <p><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($document->effective_date)->format('F d, Y') }}</p>
            <p><strong>Issued By:</strong> {{ $document->reviewer->name ?? 'Administration' }}</p>
            <p><strong>Issued Date:</strong> {{ $document->created_at->format('F d, Y') }}</p>
        </div>

        @if($emailMessage)
            <div class="message-box">
                <strong>Additional Message:</strong>
                <p>{{ $emailMessage }}</p>
            </div>
        @endif

        <p>You can download your offer letter by clicking the button below:</p>
        
        <a href="{{ $document->file_url }}" class="btn" target="_blank">
            Download Offer Letter
        </a>
        
        <p>Or use this link: <a href="{{ $document->file_url }}">{{ $document->file_url }}</a></p>
        
        <p><em>Please keep this document for your records.</em></p>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
        <p>If you have any questions, please contact the HR department.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
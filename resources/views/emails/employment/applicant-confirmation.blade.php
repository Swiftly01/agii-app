<x-mail::message>
# Application Received Successfully

Dear {{ $application->full_name }},

Thank you for submitting your employment application for the position of **{{ $application->position_applying_for }}**.

**Application Reference:** {{ $application->application_reference }}

Your application has been received and is currently under review. We will contact you via phone or email if your application is shortlisted.

**Application Summary:**
- Position: {{ $application->position_applying_for }}
- Application Date: {{ $application->created_at->format('F j, Y') }}
- Status: Pending Review

<x-mail::panel>
Please keep your application reference for future correspondence. You may be contacted for further documentation or interviews.
</x-mail::panel>

If you have any questions, please contact our HR department.

Best regards,<br>
HR Department<br>
{{ config('app.name') }}
</x-mail::message>
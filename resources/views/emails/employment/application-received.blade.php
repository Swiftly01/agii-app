<x-mail::message>
# New Employment Application Received

A new employment application has been submitted through the online portal.

**Application Details:**
- **Application Reference:** {{ $application->application_reference }}
- **Applicant Name:** {{ $application->full_name }}
- **Position Applied For:** {{ $application->position_applying_for }}
- **Date of Application:** {{ $application->created_at->format('F j, Y h:i A') }}

<x-mail::button :url="route('admin.employment.show', $application)">
View Application Details
</x-mail::button>

**Applicant Information:**
- Email: {{ $application->contact_number }}
- Phone: {{ $application->contact_number }}
- Qualification: {{ $application->educational_qualification }}

<x-mail::panel>
The applicant has uploaded all required documents including passport photo, resume, certificates, and means of identification.
</x-mail::panel>

Please review this application in the admin panel.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
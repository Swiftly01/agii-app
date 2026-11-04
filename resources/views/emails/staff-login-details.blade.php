<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $isReset ? 'Password reset' : 'Welcome to the team' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>{{ $isReset ? 'Your password has been reset' : 'Welcome to the team' }}</h2>
    <p>Hello {{ $user->first_name }} {{ $user->last_name }},</p>

    <p>{{ $isReset ? 'An administrator has reset your staff account password.' : 'Your staff account has been created.' }}</p>

    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Temporary password:</strong> {{ $plainPassword }}</p>
    <p><strong>Login:</strong> <a href="{{ route('login') }}">{{ route('login') }}</a></p>

    <p>Please log in and change your password as soon as you can.</p>

    <p>Best regards,<br>HR Team</p>
</body>
</html>

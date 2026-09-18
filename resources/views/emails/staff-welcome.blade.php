<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to the Team</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <h2>Welcome to the Team</h2>
                <p>Hello {{ $full_name }},</p>

                <p>Your staff account has been created successfully.</p>
                
                <p><strong>Email:</strong> {{ $user }}</p>
                <p><strong>Temporary Password:</strong> {{ $password }}</p>
                
                <p><strong>Login using this link</strong> https://agii.ng/login </p>


                <p>Please log in and change your password immediately.</p>

                <p>Best regards,<br>HR Team</p>
            </td>
        </tr>
    </table>
</body>
</html>

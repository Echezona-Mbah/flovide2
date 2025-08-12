<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        h1, h3, p {
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background-color: #1e88e5;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #1565c0;
        }

        .social-icons {
            margin-top: 30px;
            text-align: center;
        }

        .social-icons a {
            display: inline-block;
            margin: 0 8px;
        }

        .social-icons a img {
            width: 36px;
            height: 36px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('Logo.png') }}" alt="Company Logo" style="max-width: 60%; margin-bottom: 20px;">

        <h1>You've Been Invited! 🎉</h1>
        <h3>Hello,</h3>
        <p><strong>{{ $ownerName }}</strong> has invited you to join their team on <strong>Flovide</strong>.</p>
        <p>Click the button below to accept the invitation and get started:</p>

        <a href="{{ $url }}" class="btn">Accept Invitation</a>

        <p style="margin-top: 20px;">If the button doesn’t work, copy and paste the link below into your browser:</p>
        <p><a href="{{ $url }}">{{ $url }}</a></p>

        <p>Best Regards,</p>
        <p>The Flovide Team</p>

        <!-- Social Media Icons -->
        <div class="social-icons">
            <a href="https://www.facebook.com/your-facebook-page"><img src="{{ asset('image/download1.png') }}" alt="Facebook"></a>
            <a href="https://www.instagram.com/your-instagram-account"><img src="{{ asset('image/download 4.png') }}" alt="Instagram"></a>
            <a href="https://www.linkedin.com/in/your-linkedin-profile"><img src="{{ asset('image/download3.png') }}" alt="LinkedIn"></a>
            <a href="https://wa.me/your-whatsapp-number"><img src="{{ asset('image/download 2.png') }}" alt="WhatsApp"></a>
            <a href="https://www.youtube.com/your-youtube-channel"><img src="{{ asset('image/download 5.png') }}" alt="YouTube"></a>
        </div>
    </div>
</body>
</html>

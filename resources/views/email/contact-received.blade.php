<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Flovide</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3, p {
            color: #333;
        }

        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icons a {
            display: inline-block;
            margin-right: 15px;
        }

        .social-icons a img {
            width: 40px;
            height: 40px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">

    <img src="{{ asset('Logo.png') }}" alt="Company Logo" style="max-width: 60%; margin-bottom:20px;">

    <h2>Hello {{ $contact->first_name }},</h2>

    <p>
        Thank you for contacting <strong>Flovide</strong>.
    </p>

    <p>
        We have successfully received your message. Our team is currently reviewing your inquiry and will get back to you as soon as possible.
    </p>

    <p>
        Below is a copy of the message you submitted:
    </p>

    <div class="message-box">
        <strong>Message:</strong><br><br>
        {{ $contact->message }}
    </div>

    <p>
        We appreciate your interest in our services and look forward to assisting you.
    </p>

    <p>
        <strong>Best Regards,</strong><br>
        The Flovide Team
    </p>

    <p class="footer">
        This is an automated confirmation email. Please do not reply to this message.
    </p>

    <!-- Social Media Icons -->
    <div class="social-icons">
        <a href="https://www.facebook.com/your-facebook-page">
            <img src="{{ asset('asserts/SocialMedia/facebook.png') }}" alt="Facebook">
        </a>

        <a href="https://www.instagram.com/your-instagram-account">
            <img src="{{ asset('asserts/SocialMedia/instagram.png') }}" alt="Instagram">
        </a>

        <a href="https://www.linkedin.com/in/your-linkedin-profile">
            <img src="{{ asset('asserts/SocialMedia/linkedin.png') }}" alt="LinkedIn">
        </a>

        <a href="https://wa.me/2348132824987">
            <img src="{{ asset('asserts/SocialMedia/whatsapp.png') }}" alt="WhatsApp">
        </a>

        <a href="https://www.youtube.com/your-youtube-channel">
            <img src="{{ asset('asserts/SocialMedia/youtube.png') }}" alt="YouTube">
        </a>
    </div>

</div>

</body>
</html>
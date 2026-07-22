<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>

    <style>
        body{
            background:#f4f4f4;
            font-family:Arial,sans-serif;
            margin:0;
            padding:0;
        }

        .container{
            max-width:600px;
            margin:0 auto;
            padding:20px;
            background:#fff;
        }

        h2,p{
            color:#333;
        }

        .message-box{
            background:#f8f9fa;
            border-left:4px solid #2563eb;
            padding:18px;
            margin:20px 0;
            white-space: pre-line;
        }

        .social-icons{
            margin-top:25px;
        }

        .social-icons a{
            display:inline-block;
            margin-right:12px;
        }

        .social-icons img{
            width:40px;
            height:40px;
        }

        .footer{
            margin-top:35px;
            font-size:12px;
            color:#888;
        }
    </style>
</head>
<body>

<div class="container">

    <img src="{{ url('Logo.png') }}" alt="Company Logo" style="max-width:60%;margin-bottom:20px;">

    <h2>Hello {{ $firstName }},</h2>

    <p>
        Thank you for contacting <strong>Flovide</strong>.
    </p>

    <p>
        One of our team members has reviewed your enquiry and sent the following response:
    </p>

    <div class="message-box">
        {!! nl2br(e($messageBody)) !!}
    </div>

    <p>
        If you have any further questions, simply reply to this email and we'll be happy to assist you.
    </p>

    <p>
        <strong>Kind Regards,</strong><br>
        <strong>The Flovide Team</strong>
    </p>

    <p class="footer">
        Thank you for choosing Flovide.
    </p>

    <div class="social-icons">

        <a href="https://www.facebook.com/your-facebook-page">
            <img src="{{ url('asserts/SocialMedia/facebook.png') }}" alt="Facebook">
        </a>

        <a href="https://www.instagram.com/your-instagram-account">
            <img src="{{ url('asserts/SocialMedia/instagram.png') }}" alt="Instagram">
        </a>

        <a href="https://www.linkedin.com/in/your-linkedin-profile">
            <img src="{{ url('asserts/SocialMedia/linkedin.png') }}" alt="LinkedIn">
        </a>

        <a href="https://wa.me/2348132824987">
            <img src="{{ url('asserts/SocialMedia/whatsapp.png') }}" alt="WhatsApp">
        </a>

        <a href="https://www.youtube.com/your-youtube-channel">
            <img src="{{ url('asserts/SocialMedia/youtube.png') }}" alt="YouTube">
        </a>

    </div>

</div>

</body>
</html>
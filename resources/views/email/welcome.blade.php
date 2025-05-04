<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0px;
            padding: 20px;
        }

        h1, h3, p {
            color: #333;
        }

        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('Logo.png') }}" alt="Company Logo" style="max-width: 60%; margin-bottom: 20px;">

        <h1>Welcome to Flovide! 🚀</h1>
        <h3>Dear {{ $user->business_name }}!,</h3>
        <p>Welcome to Flovide, We are thrilled to have you on board.</p>
        <p>As our CEO, often says, "At Flovide, we're not just a platform;
             we're a community that values convenience, security, and speed. Your journey with us is about to get extraordinary!"
        </p>
        <p>Thank you for choosing Flovide.
        </p>
        <p>Best Regards,
        </p>


        <!-- Social Media Icons -->
        <div class="social-icons">
            <a href="https://www.facebook.com/your-facebook-page"><img src="{{ asset('image/download1.png') }}" alt="Facebook"></a>
            <a href="https://www.instagram.com/your-instagram-account"><img src="{{ asset('image/download 4.png') }}" alt="Instagram"></a>
            <a href="https://www.linkedin.com/in/your-linkedin-profile"><img src="{{ asset('image/download3.png') }}" alt="LinkedIn"></a>
            <a href="https://wa.me/your-whatsapp-number"><img src="{{ asset('image/download 2.png') }}" alt="WhatsApp"></a>
            <a href="https://www.youtube.com/your-youtube-channel"><img src="{{ asset('image/download 5.png') }}" alt="YouTube Video Thumbnail"></a>
        </div>
    </div>
</body>
</html>

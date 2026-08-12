<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Registration</title>
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

        .badge {
            display: inline-block;
            background-color: {{ $type === 'business' ? '#215F9C' : '#7bcf9e' }};
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .details-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 4px 20px;
            margin: 20px 0;
        }

        .details-row {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .details-row:last-child {
            border-bottom: none;
        }

        .details-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 0 0 4px 0;
        }

        .details-value {
            font-size: 15px;
            color: #111827;
            font-weight: 600;
            margin: 0;
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

        <div class="badge">New {{ ucfirst($type) }} Registration</div>

        <h1>A new {{ $type }} account was just created 🎉</h1>
        <p>Here are the details of the new registration on Flovide:</p>

        <div class="details-card">
            <div class="details-row">
                <p class="details-label">Name</p>
                <p class="details-value">
                    {{ $account->firstname ?? '' }} {{ $account->lastname ?? '' }}
                    @if($type === 'business' && !empty($account->business_name))
                        &mdash; {{ $account->business_name }}
                    @endif
                </p>
            </div>

            <div class="details-row">
                <p class="details-label">Email</p>
                <p class="details-value">{{ $account->email }}</p>
            </div>

            <div class="details-row">
                <p class="details-label">Phone</p>
                <p class="details-value">{{ $account->person_phone ?? 'N/A' }}</p>
            </div>

            <div class="details-row">
                <p class="details-label">Country</p>
                <p class="details-value">{{ $account->country ?? $account->countries_id ?? 'N/A' }}</p>
            </div>

            @if($type === 'business')
            <div class="details-row">
                <p class="details-label">Business Type</p>
                <p class="details-value">{{ $account->business_type ?? 'N/A' }}</p>
            </div>

            <div class="details-row">
                <p class="details-label">Industry</p>
                <p class="details-value">{{ $account->industry ?? 'N/A' }}</p>
            </div>
            @endif

            <div class="details-row">
                <p class="details-label">Registered At</p>
                <p class="details-value">{{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <p>This is an automated notification &mdash; no action is required unless you're reviewing new sign-ups.</p>
        <p>Best Regards,</p>

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
            <a href="https://wa.me/your-whatsapp-number">
                <img src="{{ asset('asserts/SocialMedia/whatsapp.png') }}" alt="WhatsApp">
            </a>
            <a href="https://www.youtube.com/your-youtube-channel">
                <img src="{{ asset('asserts/SocialMedia/youtube.png') }}" alt="YouTube Video Thumbnail">
            </a>
        </div>
    </div>
</body>
</html>
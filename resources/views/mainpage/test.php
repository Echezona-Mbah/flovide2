<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Worker ID Card</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --primary: #1f3a5f;
            --accent: #49a6ff;
            --accent-2: #7dd3fc;
            --text: #1e293b;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.25);
            --success: #10b981;
            --shadow: 0 25px 50px rgba(31, 58, 95, 0.18);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at top left, rgba(73, 166, 255, 0.22), transparent 32%),
                radial-gradient(circle at bottom right, rgba(125, 211, 252, 0.28), transparent 28%),
                var(--bg);
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
        }

        .card-shell {
            width: min(92vw, 780px);
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 28px;
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .id-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #081c34 0%, #102b49 30%, #1e486f 100%);
            border-radius: 24px;
            padding: 26px 26px 20px;
            color: #fff;
            min-height: 470px;
        }

        .id-card::before,
        .id-card::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .id-card::before {
            width: 280px;
            height: 280px;
            right: -70px;
            top: -110px;
        }

        .id-card::after {
            width: 240px;
            height: 240px;
            left: -80px;
            bottom: -100px;
        }

        .topbar {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 12px;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #0b1c30;
            font-size: 18px;
            font-weight: 800;
            overflow: hidden;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.35), 0 10px 20px rgba(73,166,255,0.25);
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .brand-mark .brand-fallback {
            display: none;
            width: 100%;
            height: 100%;
            place-items: center;
            font-size: 18px;
            font-weight: 800;
        }

        .brand-mark svg {
            width: 22px;
            height: 22px;
            display: block;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            font-size: 12px;
            color: #d9ebff;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15);
        }

        .content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: center;
        }

        .profile {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 20px;
        }

        .avatar {
            width: 96px;
            height: 96px;
            border-radius: 26px;
            background: linear-gradient(135deg, #dfeeff, #a8d5ff);
            display: grid;
            place-items: center;
            color: #0b1c30;
            font-size: 38px;
            font-weight: 700;
            border: 3px solid rgba(255,255,255,0.3);
            box-shadow: 0 12px 28px rgba(0,0,0,0.18);
        }

        .person h1 {
            margin: 0 0 5px;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.1;
            letter-spacing: -0.04em;
        }

        .role {
            margin: 0;
            font-size: 15px;
            color: #dfeeff;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 20px;
            margin-top: 12px;
        }

        .meta-item {
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .label {
            display: block;
            font-size: 11px;
            letter-spacing: 0.08em;
            color: rgba(226, 236, 255, 0.7);
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .value {
            font-weight: 700;
            font-size: 15px;
            color: #f9fbff;
        }

        .side-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 22px;
            padding: 18px 14px;
            min-height: 290px;
        }

        .qr-box {
            width: 145px;
            height: 145px;
            border-radius: 18px;
            background:
                linear-gradient(90deg, #0b1c30 10%, transparent 10%) 0 0 / 20px 20px,
                linear-gradient(#0b1c30 10%, transparent 10%) 0 0 / 20px 20px,
                linear-gradient(135deg, #f7fbff, #d9ebff);
            border: 8px solid rgba(255,255,255,0.85);
            position: relative;
            box-shadow: inset 0 0 0 3px rgba(16, 33, 52, 0.12);
        }

        .qr-box::before,
        .qr-box::after {
            content: "";
            position: absolute;
            inset: 18px;
            background: rgba(16, 33, 52, 0.9);
            clip-path: polygon(0 0, 100% 0, 100% 24%, 24% 24%, 24% 100%, 0 100%);
            opacity: 0.9;
        }

        .qr-box::after {
            inset: 34px;
            background: rgba(16, 33, 52, 0.85);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 0 0, 26% 0, 26% 26%, 74% 26%, 74% 74%, 26% 74%, 26% 100%, 0 100%);
        }

        .worker-id {
            text-align: center;
        }

        .worker-id .label {
            margin-bottom: 8px;
            font-size: 10px;
        }

        .worker-id .value {
            font-size: 18px;
            letter-spacing: 0.12em;
        }

        .footer {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid rgba(255,255,255,0.12);
            color: rgba(233, 243, 255, 0.86);
            font-size: 12px;
        }

        .footer strong {
            color: #fff;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .seal {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.3);
            display: grid;
            place-items: center;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #dfeeff;
            background: rgba(255,255,255,0.04);
        }

        @media (max-width: 640px) {
            .card-shell {
                padding: 14px;
            }

            .content {
                grid-template-columns: 1fr;
            }

            .id-card {
                padding: 20px 18px 18px;
            }

            .profile {
                flex-direction: column;
                align-items: flex-start;
            }

            .meta {
                grid-template-columns: 1fr;
            }

            .footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="card-shell">
        <div class="id-card">
            <div class="topbar">
                <div class="brand">
                    <img src="{{asset('../asserts/homepage/Logo.png')}}" alt="Logo" />
                </div>

                <div class="status">
                    <span class="status-dot"></span>
                    Active Staff
                </div>
            </div>

            <div class="content">
                <div>
                    <div class="profile">
                        <div class="avatar">EP</div>
                        <div class="person">
                            <h1>Emmanuel Pinnacle</h1>
                            <p class="role">Senior Software Engineer</p>
                        </div>
                    </div>

                    <div class="meta">
                        <div class="meta-item">
                            <span class="label">Employee ID</span>
                            <span class="value">FLV-2048</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Department</span>
                            <span class="value">Engineering</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Location</span>
                            <span class="value">Lagos, NG</span>
                        </div>
                        <div class="meta-item">
                            <span class="label">Valid Until</span>
                            <span class="value">12/2027</span>
                        </div>
                    </div>
                </div>

                <div class="side-panel">
                    <div class="qr-box" aria-label="Employee QR code"></div>

                    <div class="worker-id">
                        <span class="label">Staff No</span>
                        <div class="value">#A-184</div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div>
                    <div><strong>Contact</strong></div>
                    <div>emmanuel@flovide.com</div>
                </div>

                <div class="seal">Verified</div>
            </div>
        </div>
    </div>
</body>
</html>
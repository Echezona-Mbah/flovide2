<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flovide | Employee Verification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --flovide-primary: #071b3b;
            --flovide-primary-soft: #0e224c;
            --flovide-secondary: #1ec8b5;
            --flovide-secondary-soft: #c9f9f1;
            --flovide-accent: #0f9cf3;
            --flovide-surface: #f4f7fb;
            --flovide-panel: #ffffff;
            --flovide-border: rgba(7, 27, 59, 0.1);
            --flovide-text: #10213d;
            --flovide-muted: #64748b;
            --flovide-success: #18b56d;
            --flovide-shadow: 0 20px 45px rgba(9, 21, 44, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #eef5ff 0%, #f7f9fc 100%);
            color: var(--flovide-text);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .page-shell {
            width: min(1100px, 100%);
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 28px;
            box-shadow: var(--flovide-shadow);
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 22px 34px;
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--flovide-border);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            color: var(--flovide-primary);
            letter-spacing: -0.04em;
            font-size: clamp(1.15rem, 1.5vw, 1.6rem);
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--flovide-primary) 0%, var(--flovide-accent) 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(15, 156, 243, 0.18);
            font-size: 1.15rem;
            font-weight: 800;
        }

        .verification-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--flovide-secondary-soft) 0%, rgba(30, 200, 181, 0.15) 100%);
            border: 1px solid rgba(30, 200, 181, 0.25);
            color: var(--flovide-primary);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .verification-pill .dot {
            width: 10px;
            height: 10px;
            background: var(--flovide-success);
            border-radius: 50%;
            box-shadow: 0 0 0 5px rgba(24, 181, 109, 0.18);
        }

        .content {
            padding: 34px;
            background:
                radial-gradient(circle at top left, rgba(30, 200, 181, 0.10), transparent 34%),
                radial-gradient(circle at bottom right, rgba(15, 156, 243, 0.08), transparent 42%),
                #ffffff;
        }

        .verification-card {
            display: grid;
            grid-template-columns: 300px 1fr;
            background: var(--flovide-panel);
            border: 1px solid var(--flovide-border);
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 14px 30px rgba(9, 21, 44, 0.06);
        }

        .profile-side {
            background: linear-gradient(180deg, #071b3b 0%, #102b57 100%);
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            color: #fff;
            text-align: center;
        }

        .avatar-wrap {
            position: relative;
            width: 164px;
            height: 164px;
            border-radius: 50%;
            padding: 5px;
            background: linear-gradient(135deg, rgba(255,255,255,0.7), rgba(30,200,181,0.9));
            box-shadow: 0 18px 30px rgba(30, 200, 181, 0.25);
        }

        .avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.9);
            display: block;
        }

        .profile-name {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2rem);
            letter-spacing: -0.05em;
        }

        .profile-role {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            font-weight: 500;
        }




        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: 1px solid currentColor;
        }

        .status-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }

        /* .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(30, 200, 181, 0.12);
            border: 1px solid rgba(255,255,255,0.18);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #dffef7;
        } */

        /* .status-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--flovide-secondary);
            box-shadow: 0 0 0 4px rgba(30,200,181,0.18);
        } */

        .info-side {
            padding: 30px 30px 26px;
            background: linear-gradient(180deg, rgba(244,247,251,0.8), rgba(255,255,255,0.96));
        }

        .intro {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .eyebrow {
            margin: 0 0 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            color: var(--flovide-muted);
            text-transform: uppercase;
        }

        .title {
            margin: 0;
            font-size: clamp(1.7rem, 2vw, 2.4rem);
            letter-spacing: -0.06em;
            color: var(--flovide-primary);
        }

        .verified-note {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 12px;
            background: rgba(24, 181, 109, 0.08);
            border: 1px solid rgba(24, 181, 109, 0.15);
            color: var(--flovide-success);
            font-weight: 700;
            font-size: 0.8rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 18px;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--flovide-border);
            border-radius: 16px;
            padding: 16px 18px;
            min-height: 104px;
        }

        .detail-label {
            display: block;
            margin-bottom: 8px;
            color: var(--flovide-muted);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .detail-value {
            display: block;
            color: var(--flovide-text);
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.5;
        }

        .detail-value.email {
            color: var(--flovide-primary-soft);
            word-break: break-word;
        }

        /* .detail-value.status {
            color: var(--flovide-success);
        } */

        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid var(--flovide-border);
            color: var(--flovide-muted);
            font-size: 0.82rem;
        }

        .footer strong {
            color: var(--flovide-primary);
        }

        @media (max-width: 820px) {
            .verification-card {
                grid-template-columns: 1fr;
            }

            .profile-side {
                padding-bottom: 22px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            body {
                padding: 18px 12px;
            }

            .topbar,
            .content {
                padding-left: 18px;
                padding-right: 18px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .intro {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .detail-value.status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
        }

        /* Active: Green */
        .status-active {
            color: #15803d;
            background: #dcfce7;
            border-color: #86efac;
        }

        /* Inactive: Red */
        .status-inactive {
            color: #b91c1c;
            background: #fee2e2;
            border-color: #fca5a5;
        }

        /* On leave: Amber */
        .status-on-leave {
            color: #b45309;
            background: #fef3c7;
            border-color: #fcd34d;
        }

        /* Unknown status: Grey */
        .status-unknown {
            color: #475569;
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

    </style>
</head>
<body>
    @php
        $employee = [
            'name' => $employee->name,
            'id' => $employee->employee_id,
            'title' => $employee->job_title,
            'department' => $employee->department,
            'email' => $employee->work_email,
            // 'employment_date' => \Carbon\Carbon::parse($employee->employment_date)->format('d F Y'),
            'id_card_expiration_date' => \Carbon\Carbon::parse($employee->id_card_expiration_date)->format('d F Y'),
            'status' => ucfirst(str_replace('_', ' ', $employee->status)),
            'status_class' => match ($employee->status) {
                'active' => 'status-active',
                'inactive' => 'status-inactive',
                'on_leave' => 'status-on-leave',
                default => 'status-unknown',
            },
            'photo' => $employee->photo_path
                ? asset('storage/' . $employee->photo_path)
                : asset('../asserts/homepage/default-avatar.png'),
        ];
    @endphp

    <div class="page-shell">
        <header class="topbar">
            <div class="brand" aria-label="Flovide logo and brand name">
                <img src="{{asset('../asserts/homepage/Logo.png')}}" alt="Logo" />
            </div>

            <div class="verification-pill" aria-label="Employee verification status">
                <span class="dot" aria-hidden="true"></span>
                Verified Employee
            </div>
        </header>

        <main class="content">
            <section class="verification-card" aria-label="Employee identity verification card">
                <aside class="profile-side">
                    <div class="avatar-wrap">
                        <img class="avatar" src="{{ $employee['photo'] }}" alt="{{ $employee['name'] }} profile photo">
                    </div>

                    <div>
                        <h1 class="profile-name">{{ $employee['name'] }}</h1>
                        <p class="profile-role">{{ $employee['title'] }}</p>
                    </div>

                    <div class="status-badge {{ $employee['status_class'] }}">
                        {{ $employee['status'] }}
                    </div>
                </aside>

                <section class="info-side">
                    <div class="intro">
                        <div>
                            <p class="eyebrow">Employee ID Verification</p>
                            <h2 class="title">Identity Verified</h2>
                        </div>

                        <span class="verified-note">✓ Verified</span>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value">{{ $employee['name'] }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Employee ID</span>
                            <span class="detail-value">{{ $employee['id'] }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Job Title</span>
                            <span class="detail-value">{{ $employee['title'] }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Department</span>
                            <span class="detail-value">{{ $employee['department'] }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Work Email</span>
                            <span class="detail-value email">{{ $employee['email'] }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">ID Card Expiration Date</span>
                            <span class="detail-value">{{ $employee['id_card_expiration_date'] }}</span>
                        </div>

                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <span class="detail-label">Employment Status</span>
                            <span class="detail-value status {{ $employee['status_class'] }}">
                                {{ $employee['status'] }}
                            </span>
                        </div>
                    </div>

                    <footer class="footer">
                        <span><strong>Flovide</strong> Security Verification</span>
                        <span>Scan Date: {{ date('d M Y') }}</span>
                    </footer>
                </section>
            </section>
        </main>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Terms & Conditions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        window.fcWidgetMessengerConfig = {
            open: true,
        }
    </script>
    <script src='//fw-cdn.com/16096204/7073720.js' chat='true'></script>
    <style>
        :root {
            --brand-dark: #0b1f36;
            --brand-deep: #12385f;
            --brand-blue: #1f6fb2;
            --brand-cyan: #67d3ff;
            --text-main: #1f2937;
            --text-soft: #64748b;
            --line: rgba(15, 36, 61, 0.08);
            --card: rgba(255, 255, 255, 0.84);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            color: var(--text-main);
            background:
                radial-gradient(circle at 10% 10%, rgba(103, 211, 255, 0.18), transparent 24%),
                radial-gradient(circle at 85% 15%, rgba(31, 111, 178, 0.12), transparent 28%),
                radial-gradient(circle at 80% 80%, rgba(11, 31, 54, 0.08), transparent 25%),
                linear-gradient(180deg, #f8fbff 0%, #edf4fb 100%);
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 34px 34px;
        }

        .glass-card {
            background: var(--card);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow:
                0 20px 70px rgba(15, 36, 61, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .soft-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.92));
            border: 1px solid var(--line);
            box-shadow: 0 12px 40px rgba(15, 36, 61, 0.06);
        }

        .terms-copy h2 {
            font-size: 1.45rem;
            line-height: 1.25;
            font-weight: 800;
            color: var(--brand-dark);
            margin-top: 2.7rem;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .terms-copy p {
            font-size: 1rem;
            line-height: 1.95;
            color: var(--text-soft);
            margin-bottom: 1rem;
        }

        .terms-copy ul {
            margin-top: 1rem;
            margin-bottom: 1.25rem;
            display: grid;
            gap: 0.85rem;
        }

        .terms-copy li {
            list-style: none;
            position: relative;
            padding-left: 1.75rem;
            color: var(--text-soft);
            line-height: 1.85;
        }

        .terms-copy li::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand-cyan), var(--brand-blue));
            position: absolute;
            left: 0;
            top: 0.7rem;
            box-shadow: 0 0 0 5px rgba(103, 211, 255, 0.12);
        }

        .toc-link {
            transition: all 0.25s ease;
        }

        .toc-link:hover {
            transform: translateX(4px);
        }

        .hero-orb {
            animation: floatOrb 9s ease-in-out infinite;
        }

        .hero-orb.delay {
            animation-delay: 1.5s;
        }

        @keyframes floatOrb {
            0%,
            100% {
                transform: translateY(0px) translateX(0px);
            }
            50% {
                transform: translateY(-14px) translateX(8px);
            }
        }
    </style>
</head>

<body>
    @include('mainpage.navbar')

    <header>
        <section class="bg-[#0F243D] text-white" id="mobileMenuButton">
            <section class="md:hidden px-4 py-3 border border-[#1E5186] rounded-2xl shadow-md">
                <div class="flex justify-between items-center w-full">
                    <div>
                        <img src="../asserts/mobileLogo.svg" alt="Flovide Logo" class="h-8">
                    </div>
                    <div id="openSidebarBtn">
                        <img src="../asserts/menu-icon.svg" alt="Menu Icon" class="h-6">
                    </div>
                </div>
            </section>

            <section class="md:hidden px-4 py-3 w-full flex justify-center items-center">
                <div id="mobileMenuContent" class="mt-2 absolute top-[15vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
                    <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[18px] font-medium space-y-4 text-white">
                        <a href="{{ route('personal') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Personal</a>
                        <a href="{{ route('business') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Business</a>
                        <a href="{{ url('/Coming') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Developer</a>
                        <a href="{{ route('blog') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Blog</a>
                        <a href="{{ route('careers') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Career</a>
                        <a href="{{ route('contactUs') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Contact Us</a>
                    </ul>
                </div>
            </section>
        </section>
    </header>

    <main class="right-0 left-0 mx-auto overflow-x-hidden">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#081a2e] via-[#0F243D] to-[#1b4d7f]"></div>
            <div class="absolute inset-0 hero-grid opacity-40"></div>

            <div class="hero-orb absolute -top-16 -left-8 w-64 h-64 rounded-full bg-cyan-300/20 blur-3xl"></div>
            <div class="hero-orb delay absolute top-20 right-0 w-72 h-72 rounded-full bg-blue-300/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 w-80 h-80 rounded-full bg-sky-200/10 blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-6 md:px-8 py-20 md:py-28">
                <div class="grid lg:grid-cols-12 gap-10 items-end">
                    <div class="lg:col-span-8">
                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full border border-white/15 bg-white/10 text-blue-100 text-sm">
                            <i class="fas fa-balance-scale"></i>
                            Terms, rights, and responsibilities
                        </div>

                        <h1 class="mt-6 text-4xl md:text-6xl font-extrabold text-white leading-[1.05] tracking-tight max-w-4xl">
                            Terms & Conditions
                        </h1>

                        <p class="mt-6 max-w-3xl text-lg md:text-xl text-slate-200 leading-8">
                            A clear and trustworthy overview of how users access, use, and interact with Flovide’s services, products, and platform.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <div class="px-5 py-3 rounded-2xl bg-white/10 border border-white/10 text-sm text-blue-100">
                                Effective date: March 18, 2026
                            </div>
                            <div class="px-5 py-3 rounded-2xl bg-white/10 border border-white/10 text-sm text-blue-100">
                                Reviewed by legal before publish
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <div class="glass-card rounded-[28px] p-6 text-white/90 bg-white/10 border border-white/10">
                            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center text-2xl">
                                <i class="fas fa-file-signature text-white"></i>
                            </div>
                            <h3 class="mt-5 text-2xl font-bold text-white">Built for clarity</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-200">
                                This page is designed to make legal content feel easier to read, easier to trust, and easier to navigate on both desktop and mobile.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 md:px-8 py-14 md:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <aside class="lg:col-span-4 xl:col-span-3">
                    <div class="soft-panel rounded-[28px] p-6 lg:sticky lg:top-28">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-[#0F243D] text-white flex items-center justify-center">
                                <i class="fas fa-stream"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#0F243D]">Quick Navigation</h3>
                                <p class="text-sm text-slate-500">Jump to any section</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2 text-sm">
                            <a href="#acceptance" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Acceptance of Terms</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#services" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Use of Services</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#accounts" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Account Responsibility</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#payments" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Payments and Fees</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#privacy" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Privacy and Data</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#restricted" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Restricted Activities</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#liability" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Liability</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#termination" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Suspension or Termination</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#changes" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Changes to Terms</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                            <a href="#contact" class="toc-link flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-slate-50 text-slate-600 hover:text-[#0F243D]">
                                <span>Contact</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                        <div class="mt-8 rounded-[24px] bg-gradient-to-br from-[#0F243D] to-[#1B4D7F] p-5 text-white">
                            <p class="text-sm leading-7 text-blue-100">
                                Continued use of the platform means the user agrees to these terms and any future lawful updates.
                            </p>
                        </div>
                    </div>
                </aside>

                <section class="lg:col-span-8 xl:col-span-9">
                    <div class="glass-card rounded-[32px] p-7 md:p-12 lg:p-14 terms-copy">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 pb-7">
                            <div class="flex items-center gap-3 text-slate-500">
                                <div class="w-11 h-11 rounded-2xl bg-sky-100 text-[#0F243D] flex items-center justify-center">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-[#0F243D]">Legal Notice</p>
                                    <p class="text-sm">Review with compliance and legal before publishing live</p>
                                </div>
                            </div>

                            <div class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 text-sm">
                                Version 1.0
                            </div>
                        </div>

                        <section id="acceptance">
                            <h2>1. Acceptance of Terms</h2>
                            <p>
                                By accessing or using Flovide’s website, application, APIs, payment tools, or related services, you agree to comply with and be legally bound by these Terms and Conditions. If you do not agree with any part of these terms, you should discontinue use of the platform immediately.
                            </p>
                        </section>

                        <section id="services">
                            <h2>2. Use of Services</h2>
                            <p>
                                Flovide provides financial technology services subject to applicable law, operational policies, and service-specific product conditions. Users agree to use the platform only for lawful and authorized purposes.
                            </p>
                            <ul>
                                <li>You must provide accurate, complete, and up-to-date information.</li>
                                <li>You must not interfere with the platform’s normal operation or security.</li>
                                <li>Some services may carry additional product rules, limits, or onboarding requirements.</li>
                            </ul>
                        </section>

                        <section id="accounts">
                            <h2>3. Account Responsibility</h2>
                            <p>
                                You are responsible for maintaining the confidentiality of your credentials, PINs, passwords, API keys, OTPs, and all devices associated with your account. Activity performed through your account may be treated as authorized unless proven otherwise.
                            </p>
                            <ul>
                                <li>Keep your access details secure at all times.</li>
                                <li>Notify Flovide immediately if you detect suspicious or unauthorized access.</li>
                                <li>Ensure your profile and business information remain current and accurate.</li>
                            </ul>
                        </section>

                        <section id="payments">
                            <h2>4. Payments and Fees</h2>
                            <p>
                                Use of the platform may attract service charges, transaction fees, taxes, settlement deductions, or third-party partner costs. By initiating or receiving services through the platform, you agree to the applicable fee structure communicated by Flovide.
                            </p>
                            <p>
                                Fees may be revised from time to time, and updated pricing may take effect after notice is provided through official communication channels.
                            </p>
                        </section>

                        <section id="privacy">
                            <h2>5. Privacy and Data Protection</h2>
                            <p>
                                Flovide may collect, use, store, and share personal or transactional data where necessary to deliver services, prevent fraud, meet legal obligations, improve product performance, and support users. All such handling should align with applicable privacy requirements and Flovide’s Privacy Policy.
                            </p>
                        </section>

                        <section id="restricted">
                            <h2>6. Restricted Activities</h2>
                            <p>
                                You may not use the platform for unlawful, deceptive, abusive, or prohibited activities. Flovide reserves the right to review, delay, reject, suspend, or report suspicious activity where necessary.
                            </p>
                            <ul>
                                <li>Fraud, identity misrepresentation, or false account ownership claims.</li>
                                <li>Transactions connected to money laundering, sanctions evasion, or terrorism financing.</li>
                                <li>Use involving restricted goods, services, industries, or jurisdictions.</li>
                                <li>Attempts to bypass risk controls, platform rules, or compliance screening.</li>
                            </ul>
                        </section>

                        <section id="liability">
                            <h2>7. Limitation of Liability</h2>
                            <p>
                                To the fullest extent permitted by law, Flovide shall not be liable for indirect, incidental, consequential, punitive, or special damages arising from use of the platform, including service interruptions, third-party downtime, delayed processing, or losses outside Flovide’s reasonable control.
                            </p>
                        </section>

                        <section id="termination">
                            <h2>8. Suspension or Termination</h2>
                            <p>
                                Flovide may suspend, restrict, or terminate access to any service or account where there is a breach of these terms, suspected fraud, legal exposure, compliance concerns, operational risk, or any other reason reasonably necessary to protect users, partners, or the platform.
                            </p>
                        </section>

                        <section id="changes">
                            <h2>9. Changes to Terms</h2>
                            <p>
                                These Terms and Conditions may be updated periodically. Revised terms become effective once published or on the date specified in the update notice. Continued use of the service after that point constitutes acceptance of the updated terms.
                            </p>
                        </section>

                        <section id="contact">
                            <h2>10. Contact Information</h2>
                            <p>
                                Questions, complaints, or legal concerns regarding these Terms and Conditions may be directed to Flovide through the official support or contact channels listed on the website or application.
                            </p>
                        </section>
                    </div>
                </section>
            </div>
        </section>

        @include('mainpage.footer')
    </main>

    @include('mainpage.script')
</body>

</html>

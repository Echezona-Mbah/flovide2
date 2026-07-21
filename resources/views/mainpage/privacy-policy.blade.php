<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>privacy-policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&display=swap" rel="stylesheet">
    <script>
        window.fcWidgetMessengerConfig = {
            open: false,
        }
    </script>
    <script src='//fw-cdn.com/16096204/7073720.js' chat='true'></script>
    <style>
        body {
            color: #252525;
            font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
        }
        .legal-serif {
            font-family: 'Source Serif 4', Georgia, serif;
        }
        .policy-content h1 { font-family: 'Source Serif 4', Georgia, serif; }
        .policy-content h2 { scroll-margin-top: 6rem; }
        .policy-content h3 { scroll-margin-top: 6rem; }

        .accordion-header i { transition: transform .25s ease; }

        .nav-link {
            position: relative;
        }
        .nav-link.is-active {
            background: #0F243D;
            color: #fff;
        }
        .nav-link.is-active::before {
            content: "";
            position: absolute;
            left: -1px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 2px;
            background: #3B82F6;
        }

        .data-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            padding: .2rem .55rem;
            border-radius: 999px;
        }
        .chip-biometric { background: #FEF3E8; color: #B4530A; }
        .chip-partner { background: #EAF1FB; color: #0F243D; }

        .partner-card {
            border: 1px solid #E3E8EF;
            border-radius: .85rem;
            background: linear-gradient(180deg, #FBFCFE 0%, #F5F8FC 100%);
        }
    </style>
</head>

<body>
    <!-- navbar  -->


    @include('mainpage.navbar')

     <header class="">
        <section class="bg-[#0F243D] text-white" id="mobileMenuButton">
            <!-- Mobile Navbar -->
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

            <!-- Mobile Dropdown Menu -->
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

    <main class="right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">

<section class="max-w-7xl mx-auto px-6 md:px-8 py-16 grid grid-cols-1 md:grid-cols-4 gap-8">


        <!-- Sidebar -->
        <aside class="col-span-1 space-y-4 md:sticky md:top-24 md:self-start">
            <h3 class="legal-serif text-xl font-semibold text-[#0F243D] mb-4">Legal Documents</h3>

            <div class="border border-[#E3E8EF] rounded-xl overflow-hidden">
                <button class="accordion-header w-full flex justify-between items-center px-4 py-3 font-semibold text-[#0F243D] hover:bg-[#F5F8FC]">
                    General Agreements
                    <i class="fas fa-chevron-down transition-transform text-[#1E5186]"></i>
                </button>
                <div class="accordion-content hidden px-2 pb-2 space-y-1 text-sm">
                    <a class="policy-link nav-link is-active block px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-100" data-target="privacy-policy">Privacy Policy</a>
                    {{-- <a class="policy-link block px-3 py-2 hover:bg-gray-100 rounded cursor-pointer" data-target="referral-terms">Referral Terms & Conditions</a> --}}
                </div>
            </div>

            {{-- <div class="border rounded-lg">
                <button class="accordion-header w-full flex justify-between items-center px-4 py-3 font-semibold">
                    Agreements — United Kingdom
                    <i class="fas fa-chevron-down transition-transform"></i>
                </button>
                <div class="accordion-content hidden px-3 pb-3 space-y-2 text-sm">
                    <a class="policy-link block px-3 py-2 hover:bg-gray-100 rounded cursor-pointer" data-target="uk-agreement">UK Privacy Policy</a>
                </div>
            </div> --}}


            {{-- <div class="border rounded-lg">
                <button class="accordion-header w-full flex justify-between items-center px-4 py-3 font-semibold">
                    Agreements — United State
                    <i class="fas fa-chevron-down transition-transform"></i>
                </button>
                <div class="accordion-content hidden px-3 pb-3 space-y-2 text-sm">
                    <a class="policy-link block px-3 py-2 hover:bg-gray-100 rounded cursor-pointer" data-target="us-agreement">Us Privacy Policy</a>
                </div>
            </div> --}}


            <div class="border border-[#E3E8EF] rounded-xl overflow-hidden">
                <button class="accordion-header w-full flex justify-between items-center px-4 py-3 font-semibold text-[#0F243D] hover:bg-[#F5F8FC]">
                    Agreements — Canada
                    <i class="fas fa-chevron-down transition-transform text-[#1E5186]"></i>
                </button>
                <div class="accordion-content hidden px-2 pb-2 space-y-1 text-sm">
                    <a class="policy-link nav-link block px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-100" data-target="ca-agreement">Canada Privacy Policy</a>
                </div>
            </div>

{{-- 
            <div class="border rounded-lg">
                <button class="accordion-header w-full flex justify-between items-center px-4 py-3 font-semibold">
                    Agreements — Nigeria
                    <i class="fas fa-chevron-down transition-transform"></i>
                </button>
                <div class="accordion-content hidden px-3 pb-3 space-y-2 text-sm">
                    <a class="policy-link block px-3 py-2 hover:bg-gray-100 rounded cursor-pointer" data-target="ng-agreement">Term and Condition</a>
                    <a class="policy-link block px-3 py-2 hover:bg-gray-100 rounded cursor-pointer" data-target="ng-dispute">Dispute Resolution and Arbitration clause </a>
                </div>
            </div> --}}
        </aside>

        <!-- Content Area -->
        <div class="col-span-3">

            <!-- Privacy Policy -->
            <div id="privacy-policy" class="policy-content prose prose-lg max-w-none">
                <h1 class="text-4xl font-extrabold text-[#0F243D] mb-4">Privacy Policy</h1>

                <p>
                    This Privacy Policy explains how Flovide (“we”, “us”, or “our”) collects, uses, stores, and shares personal data,
                    and the circumstances under which such data may be disclosed to third parties.
                </p>

                <p>
                    We value our relationship with you and place the highest importance on respecting and protecting your privacy.
                    We process personal data only in accordance with applicable laws and this Privacy Policy.
                </p>

                <h2 class="mt-8 font-bold text-2xl">What Data Do We Collect?</h2>
                <p>
                    As a regulated financial service provider, Flovide is legally required to collect, verify, and retain certain
                    information about you and, where applicable, the recipients of your transactions.
                </p>

                <h3 class="mt-6 font-semibold">1. Basic Personal Data</h3>
                <ul>
                    <li>Full name</li>
                    <li>Date of birth</li>
                    <li>Phone number</li>
                    <li>Email address</li>
                </ul>

                <h3 class="mt-6 font-semibold">2. Identity Verification Information (Know Your Customer / KYC)</h3>
                <p>
                    As part of our Know Your Customer (KYC) and Anti-Money Laundering (AML) obligations, we collect identity
                    verification information, including government-issued identification documents, selfie photographs, and
                    biometric facial information used solely to verify that the individual presenting the identity document
                    is its legitimate owner.
                </p>
                <ul>

                    <li>
                        <span class="data-chip chip-biometric"><i class="fas fa-camera-retro"></i>Biometric</span>
                        A live selfie/facial photo (and, where required, a short liveness video) captured during identity verification
                    </li>
                </ul>

                <div class="partner-card p-5 my-6 not-prose">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 h-9 w-9 shrink-0 rounded-full bg-[#0F243D] text-white flex items-center justify-center">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-[#0F243D]">Facial / selfie data and identity verification</p>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                When you open or verify an account, we ask you to take a live selfie and, in some cases, a short
                                video, and to photograph a government-issued ID. This biometric and document data is used solely
                                to confirm that you are who you say you are, to match your face against your ID, to detect
                                impersonation and document fraud, and to meet our Know Your Customer (KYC) and Anti-Money
                                Laundering (AML) obligations.
                            </p>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                                Biometric facial information is used exclusively for identity verification and fraud
                                prevention. It is never used for advertising, profiling, marketing, or any unrelated
                                purpose.
                            </p>
                               <li class="font-semibold text-[#0F243D]"> <b>Personal  (KYC) </b> </li>
<li>Selfie/Liveness</li>
<li>Identity Document</li>
<li class="font-semibold text-[#0F243D]"><b>Business Address  (KYB)</b></li>
<li>Proof of Address (optional depending on country)</li>
<li>BVN (country-specific)</li>
<li>Business KYC</li>
<li>Business Registration (CAC/Formation Documents)</li>
<li>Directors & Owners</li>
<li>Tax Information (TIN)</li>
<li>Ownership & Organizational Structure</li>
<li>Identity Verification for authorized persons</li>
<li>Selfie/Liveness for authorized representative (if required)</li>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                                <span class="data-chip chip-partner"><i class="fas fa-shield-alt"></i>Verification Partner</span>
                                <span class="ml-1">
                                    Identity verification information may be processed by trusted third-party identity
                                    verification service providers acting on our behalf. This processing is carried out
                                    through our identity verification partner, <strong>Sumsub</strong>, which processes
                                    your selfie, ID document, and related liveness data on our behalf under contractual
                                    data protection and confidentiality obligations. These providers process the
                                    information solely to perform identity verification services and are contractually
                                    required to protect the confidentiality and security of the information. Sumsub does
                                    not use your biometric data for any purpose other than performing identity verification
                                    for Flovide, and does not sell it to third parties.
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <h3 class="mt-6 font-semibold">3. Recipient Data</h3>
                <ul>
                    <li>Recipient’s name</li>
                    <li>Phone number</li>
                    <li>Recipient identification documents</li>
                </ul>

                <h3 class="mt-6 font-semibold">4. Data from Third Parties</h3>
                <ul>
                    <li>Social media platforms (Google, Facebook, Twitter)</li>
                    <li>Banks and payment providers</li>
                    <li>Credit reference agencies</li>
                    <li>Advertising and analytics partners</li>
                    <li>Identity verification providers (Sumsub) for biometric and document checks</li>
                </ul>

                <h3 class="mt-6 font-semibold">5. Technical Data</h3>
                <ul>
                    <li>Device and browser information</li>
                    <li>Page views and app usage</li>
                    <li>Operating system</li>
                </ul>

                <h2 class="mt-10 font-bold text-2xl">Why Do We Collect Your Data?</h2>

                <h3 class="mt-4 font-semibold">Transactional Purposes</h3>
                <p>To process and complete transactions and account operations.</p>

                <h3 class="mt-4 font-semibold">Regulatory & Legal Compliance</h3>
                <p>
                    To comply with AML, CTF, KYC, and financial regulations, including verifying your identity through facial
                    matching and document checks performed by our verification partner Sumsub. Biometric facial data is used
                    exclusively for identity verification and fraud prevention and is never used for advertising, profiling,
                    marketing, or any unrelated purpose.
                </p>

                <h3 class="mt-4 font-semibold">Marketing & Communication</h3>
                <p>To send relevant service updates and promotional communications (opt-out anytime). Face data is never used
                    for this purpose.</p>

                <h3 class="mt-4 font-semibold">Analytics & Improvement</h3>
                <p>To improve service quality, performance, and user experience. Face data is never used for this purpose.</p>

                <h2 class="mt-10 font-bold text-2xl">Data Storage and Security</h2>
                <p>
                    All personal data, including biometric and identity verification data, is encrypted and stored on secure
                    servers. Access is limited to authorised Flovide staff and our verification partner Sumsub, only for the
                    purposes described above. All communications are protected using TLS encryption.
                </p>
                <p>
                    Identity verification data, including your selfie, liveness video, and identity document, is securely
                    transmitted to and stored on Sumsub's secure infrastructure, in accordance with applicable privacy and
                    security regulations and only for the purposes described in this Policy.
                </p>

                <h2 class="mt-10 font-bold text-2xl">How Long Do We Keep Your Data?</h2>
                <p>
                    KYC and transaction records, including selfie and identity verification data, are retained for a minimum of
                    five (5) years in line with regulatory requirements. Identity verification records, including face data,
                    are retained only for as long as required to comply with applicable legal and regulatory obligations,
                    after which they are securely deleted. Data may be retained longer where legally required.
                </p>

                <h2 class="mt-10 font-bold text-2xl">Sharing of Information</h2>
                <p>
                    We do not sell personal data. Data is shared only with regulators, financial partners, and service
                    providers where required by law and protected by appropriate safeguards. This includes sharing your selfie
                    and identity documents with Sumsub strictly for the purpose of verifying your identity. Flovide does not
                    sell, rent, or share users' face data with advertisers or other unrelated third parties.
                </p>

                <h2 class="mt-10 font-bold text-2xl">Your Rights</h2>
                <ul>
                    <li>Right to be informed</li>
                    <li>Right of access and data portability</li>
                    <li>Right to rectification</li>
                    <li>Right to erasure</li>
                    <li>Right to restrict or object to processing</li>
                    <li>Right to lodge a complaint with a data protection authority</li>
                </ul>

                <h2 class="mt-10 font-bold text-2xl">Cookies</h2>
                <p>
                    We use necessary, functional, and analytical cookies to improve user experience. You may manage cookies through
                    your browser settings.
                </p>

                <h2 class="mt-10 font-bold text-2xl">Changes to This Privacy Policy</h2>
                <p>
                    This Privacy Policy may be updated from time to time. Continued use of Flovide constitutes acceptance of any changes.
                </p>

                <p class="mt-12 text-sm text-gray-500">
                    Last Updated: July 2026
                </p>
            </div>


            <!-- Referral -->
            {{-- <div id="referral-terms" class="policy-content prose prose-lg max-w-none hidden">
                <h1 class="text-3xl font-bold text-[#0F243D]">Referral Terms & Conditions</h1>
                <p>Referral rewards apply only to verified users.</p>
            </div> --}}

            <!-- UK -->
            <div id="uk-agreement" class="policy-content prose prose-lg max-w-none hidden">
                <h1 class="text-3xl font-bold text-[#0F243D]">Privacy Policy – United Kingdom / International</h1>
                <p><strong>Flovide</strong><br>Last updated: 30/03/2025</p>

                <h3>1. Introduction</h3>
                <p>
                    This Privacy Policy explains how Flovide (“Flovide”, “we”, “us”, or “our”) collects, uses, stores, 
                    discloses, and protects personal information when you access or use our website, mobile applications, 
                    and financial services (collectively, the “Services”).
                </p>
                <p>
                    Flovide provides financial technology and money transfer services and is committed to safeguarding 
                    your privacy in compliance with applicable data protection and financial regulations, including 
                    GDPR, UK Data Protection Act, AML, and KYC laws.
                </p>

                <h3>2. Scope of This Privacy Policy</h3>
                <ul>
                    <li>Users of Flovide’s Services</li>
                    <li>Website and mobile application visitors</li>
                    <li>Customers and transaction recipients</li>
                </ul>

                <h3>3. Information We Collect</h3>

                <h4>3.1 Personal Information</h4>
                <ul>
                    <li>Full name, date of birth, nationality</li>
                    <li>Email, phone number, residential address</li>
                    <li>Login credentials and security details</li>
                    <li>Government-issued identification</li>
                    <li>Bank and payment information</li>
                    <li>Facial biometric data (live selfie / liveness video) captured for identity verification</li>
                </ul>

                <h4>3.2 KYC & Compliance Data</h4>
                <p>
                    Identity documents, proof of address, source of funds, and transaction monitoring data in line with 
                    AML and Counter-Terrorism Financing regulations. Identity documents and facial biometric data are
                    verified through our identity verification partner, <strong>Sumsub</strong>, which processes this
                    data on our behalf solely to confirm your identity and detect fraud. Biometric facial information is
                    used exclusively for identity verification and fraud prevention and is never used for advertising,
                    profiling, marketing, or any unrelated purpose.
                </p>

                <h4>3.3 Automatically Collected Data</h4>
                <ul>
                    <li>IP address, device information</li>
                    <li>Browser and operating system</li>
                    <li>Usage logs and cookies</li>
                </ul>

                <h3>4. How We Use Your Information</h3>
                <ul>
                    <li>Account creation and transaction processing</li>
                    <li>Regulatory compliance and fraud prevention, including facial matching against your ID via Sumsub</li>
                    <li>Customer support and communications</li>
                    <li>Service improvement and analytics</li>
                </ul>

                <h3>5. Legal Basis for Processing</h3>
                <ul>
                    <li>Performance of a contract</li>
                    <li>Compliance with legal obligations</li>
                    <li>Legitimate business interests</li>
                    <li>User consent where required</li>
                </ul>

                <h3>6. Data Sharing</h3>
                <p>
                    We do not sell personal data. We may share data with licensed banks, payment processors, our identity 
                    verification provider Sumsub, regulators, and law enforcement where required by law. Identity verification
                    information, including face data, may be processed by trusted third-party identity verification service
                    providers acting on our behalf, solely to perform identity verification services; these providers are
                    contractually required to protect the confidentiality and security of the information.
                </p>

                <h3>7. International Transfers</h3>
                <p>
                    Where personal data is transferred outside the UK or EEA, appropriate safeguards such as contractual 
                    clauses and regulatory protections are applied.
                </p>

                <h3>8. Data Retention</h3>
                <p>
                    KYC and transaction records, including biometric verification data, are retained for a minimum of five (5)
                    years in line with financial regulatory requirements. Identity verification records, including face data,
                    are retained only for as long as required to comply with applicable legal and regulatory obligations,
                    after which they are securely deleted.
                </p>

                <h3>9. Data Security</h3>
                <ul>
                    <li>Encryption in transit and at rest</li>
                    <li>Restricted access controls</li>
                    <li>Continuous monitoring and audits</li>
                </ul>

                <h3>10. Your Rights</h3>
                <ul>
                    <li>Right to access, correct, or delete your data</li>
                    <li>Right to object to processing</li>
                    <li>Right to data portability</li>
                    <li>Right to lodge a complaint with a data protection authority</li>
                </ul>

                <h3>11. Cookies</h3>
                <p>
                    We use cookies to enhance functionality and analyze usage. You may control cookies via your browser 
                    settings.
                </p>

                <h3>12. Children’s Privacy</h3>
                <p>
                    Our services are not intended for individuals under 18 years of age.
                </p>

                <h3>13. Changes to This Policy</h3>
                <p>
                    This Privacy Policy may be updated from time to time. Updates take effect immediately upon publication.
                </p>

                <h3>14. Contact Us</h3>
                <p>
                    For privacy inquiries, please contact: <strong>support@flovide.com</strong>
                </p>

                <h3>15. Governing Law</h3>
                <p>
                    This Privacy Policy is governed by applicable UK and international data protection laws.
                </p>
            </div>

            <!-- US -->
            <div id="us-agreement" class="policy-content prose prose-lg max-w-none hidden">
                <h1 class="text-3xl font-bold text-[#0F243D]">Privacy Policy – United States</h1>
                <p><strong>Flovide</strong><br>Last updated: 30/03/2025</p>
                {{-- <p>Referral rewards apply only to verified users.</p> --}}

                <h3>1. Introduction</h3>
                <p>
                    This Privacy Policy explains how Flovide (“Flovide”, “we”, “us”, or “our”) collects, uses, stores, 
                    discloses, and protects personal information when you access or use our website, mobile applications, 
                    and financial services (collectively, the “Services”). This policy is designed to comply with applicable 
                    U.S. federal and state privacy laws including GLBA, CCPA, CPRA, and AML/KYC requirements.
                </p>

                <h3>2. Scope</h3>
                <ul>
                    <li>Individuals using Flovide’s Services</li>
                    <li>Visitors to our website or mobile applications</li>
                    <li>Customers, prospective customers, and transaction recipients</li>
                </ul>

                <h3>3. Information We Collect</h3>
                <ul>
                    <li>Personal info: name, date of birth, nationality, contact info, government ID</li>
                    <li>Account info: login credentials, security info, bank/debit card details</li>
                    <li>KYC/Compliance info: identity verification, proof of address, source of funds, and facial
                        biometric data (live selfie / liveness check) collected during onboarding. Biometric facial
                        information is used exclusively for identity verification and fraud prevention and is never
                        used for advertising, profiling, marketing, or any unrelated purpose.</li>
                    <li>Recipient info: name, contact, bank/wallet info, ID where required</li>
                    <li>Automatic data: IP address, device info, browser/OS, usage logs, cookies</li>
                    <li>Third-party data: our identity verification partner Sumsub (facial and document verification), banks/payment processors, fraud prevention, marketing/analytics</li>
                </ul>

                <h3>4. How We Use Your Information</h3>
                <ul>
                    <li>Create/manage accounts and process transactions</li>
                    <li>Verify identity (including facial matching against government ID via Sumsub), comply with AML, KYC, FinCEN, BSA, and CTF obligations</li>
                    <li>Prevent fraud and secure Services</li>
                    <li>Respond to customer inquiries and resolve disputes</li>
                    <li>Marketing, promotions, and internal analytics where permitted</li>
                </ul>

                <h3>5. Legal Bases for Processing</h3>
                <ul>
                    <li>Performance of a contract</li>
                    <li>Compliance with legal obligations</li>
                    <li>Legitimate business interests</li>
                    <li>User consent where required</li>
                </ul>

                <h3>6. Data Sharing</h3>
                <p>
                    We do not sell personal information. We may share it with partner banks, payment processors, our
                    identity verification provider Sumsub, cloud/IT vendors, and regulators or law enforcement when
                    legally required. Identity verification information, including face data, may be processed by
                    trusted third-party identity verification service providers acting on our behalf, solely to
                    perform identity verification services; these providers are contractually required to protect the
                    confidentiality and security of the information.
                </p>

                <h3>7. Cross-Border Transfers</h3>
                <p>
                    Personal data may be transferred outside the U.S., safeguarded according to applicable laws and contracts.
                </p>

                <h3>8. Data Retention</h3>
                <ul>
                    <li>KYC and transaction records, including biometric verification data: minimum 5 years after last transaction or account closure</li>
                    <li>Identity verification records, including face data, are retained only for as long as required to comply with applicable legal and regulatory obligations, after which they are securely deleted</li>
                    <li>Other data retained as needed for legal, business, or dispute resolution purposes</li>
                </ul>

                <h3>9. Data Security</h3>
                <ul>
                    <li>Encryption in transit and at rest</li>
                    <li>Role-based access controls</li>
                    <li>Secure infrastructure, monitoring, and regular audits</li>
                </ul>

                <h3>10. Your Privacy Rights</h3>
                <ul>
                    <li>Access, correct, or delete personal information</li>
                    <li>Object to processing</li>
                    <li>Limit use of sensitive info, including biometric information (where applicable)</li>
                    <li>Opt-out of certain processing activities</li>
                </ul>

                <h3>11. California Privacy Rights</h3>
                <p>
                    California residents may request information on data collected — including biometric data collected
                    for identity verification — correct inaccuracies, request deletion, and are protected under
                    CCPA/CPRA. Flovide does not sell personal information.
                </p>

                <h3>12. Cookies & Tracking</h3>
                <p>
                    Cookies and similar technologies are used for core functionality, remembering preferences, and analyzing usage. 
                    Browser/device settings control cookies; some features may not work if disabled.
                </p>

                <h3>13. Children’s Privacy</h3>
                <p>
                    Services are not intended for individuals under 18. No personal information is knowingly collected from minors.
                </p>

                <h3>14. Changes</h3>
                <p>
                    This Privacy Policy may be updated periodically. Changes take effect immediately upon posting.
                </p>

                <h3>15. Contact Us</h3>
                <p>
                    For questions or privacy requests, contact: <strong>support@flovide.com</strong>
                </p>

                <h3>16. Governing Law</h3>
                <p>
                    This Policy is governed by applicable U.S. federal and state laws where Flovide operates.
                </p>
            </div>


<!-- Canada -->
<div id="ca-agreement" class="policy-content prose prose-lg max-w-none hidden">
    <h1 class="text-3xl font-bold text-[#0F243D]">Privacy Policy – Canada</h1>
    <p><strong>Flovide</strong><br>Last updated: 30/02/2025</p>

    <h3>1. Introduction</h3>
    <p>
        This Privacy Policy describes how Flovide (“Flovide”, “we”, “us”, or “our”) collects, uses, discloses, 
        stores, and protects personal information when you access or use our website, mobile applications, 
        and financial services (collectively, the “Services”). We comply with Canadian privacy laws including 
        PIPEDA, applicable provincial laws (e.g., Quebec Law 25), and AML/KYC obligations under FINTRAC.
    </p>

    <h3>2. Scope</h3>
    <ul>
        <li>Customers using Flovide’s Services</li>
        <li>Visitors to our website or mobile applications</li>
        <li>Individuals whose personal information is processed in connection with transactions (including recipients)</li>
    </ul>

    <h3>3. Personal Information We Collect</h3>
    <ul>
        <li><strong>Provided directly:</strong> identity, contact, account credentials, government ID, financial info, communications</li>
        <li><strong>KYC/Compliance:</strong> identity verification, proof of address, source of funds/wealth, transaction
            monitoring, and facial biometric data (live selfie and, where required, a short liveness video) collected to
            confirm your identity. Biometric facial information is used exclusively for identity verification and
            fraud prevention and is never used for advertising, profiling, marketing, or any unrelated purpose.</li>
        <li><strong>Recipient info:</strong> name, contact, bank/wallet info, ID where required</li>
        <li><strong>Automatically collected:</strong> IP address, device info, browser/OS, app usage, cookies</li>
        <li><strong>Third-party info:</strong> our identity verification partner Sumsub (facial and document
            verification), banks/payment processors, fraud prevention, analytics providers</li>
    </ul>

    <h3>4. Purposes for Collecting Personal Information</h3>
    <ul>
        <li>Create/manage accounts and process domestic/international transfers</li>
        <li>Verify identity, conduct KYC/Customer Due Diligence — including matching your selfie against your government
            ID through Sumsub — and comply with FINTRAC</li>
        <li>Detect and prevent fraud, money laundering, and other financial crimes</li>
        <li>Respond to customer inquiries and disputes</li>
        <li>Analyze usage, improve services, and develop new features (with consent where required)</li>
    </ul>

    <h3>5. Consent</h3>
    <p>
        Consent is obtained under PIPEDA and provincial laws, including express consent before we collect your
        biometric data for identity verification. Consent may be express or implied. You may withdraw consent
        subject to legal/contractual limitations and reasonable notice.
    </p>

    <h3>6. Limiting Use, Disclosure, and Retention</h3>
    <p>
        Personal information is used only for appropriate purposes and retained only as long as necessary 
        to meet legal or business requirements.
    </p>

    <h3>7. Data Retention</h3>
    <ul>
        <li><strong>Regulatory:</strong> KYC and transaction records, including biometric verification data, retained ≥5 years post-account closure or transaction</li>
        <li><strong>Identity verification records:</strong> including face data, are retained only for as long as required to comply with applicable legal and regulatory obligations, after which they are securely deleted</li>
        <li><strong>Business/Legal:</strong> retained longer if needed for disputes, agreements, or legal obligations; securely deleted/anonymized afterward</li>
    </ul>

    <h3>8. Safeguards and Security Measures</h3>
    <ul>
        <li>Encryption in transit and at rest</li>
        <li>Role-based access and secure servers</li>
        <li>Monitoring and security audits</li>
    </ul>

    <h3>9. Disclosure of Personal Information</h3>
    <p>
        We do not sell personal information. We may disclose it to banks, payment processors, our identity
        verification partner Sumsub, fraud prevention partners, regulators, courts, or law enforcement as required.
        Identity verification information, including face data, may be processed by trusted third-party identity
        verification service providers acting on our behalf, solely to perform identity verification services; these
        providers are contractually required to protect the confidentiality and security of the information.
    </p>

    <h3>10. Cross-Border Data Transfers</h3>
    <p>
        Personal information may be processed outside Canada (e.g., the U.S.) with contractual safeguards 
        and protection according to Canadian privacy standards.
    </p>

    <h3>11. Your Privacy Rights (Canada)</h3>
    <ul>
        <li>Access personal information held by Flovide</li>
        <li>Request correction of inaccurate/incomplete info</li>
        <li>Withdraw consent (subject to legal limits)</li>
        <li>Challenge compliance with privacy laws</li>
    </ul>

    <h3>12. Quebec Privacy Rights (Law 25)</h3>
    <ul>
        <li>Right to data portability</li>
        <li>Right to request deletion</li>
        <li>Right to be informed of automated decision-making</li>
    </ul>

    <h3>13. Cookies and Tracking</h3>
    <p>
        Used for functionality, preferences, and usage analytics. Preferences managed via browser/device settings. 
        Disabling may affect some features.
    </p>

    <h3>14. Children’s Privacy</h3>
    <p>
        Services not intended for under 18. Personal information from minors is not knowingly collected.
    </p>

    <h3>15. Changes</h3>
    <p>
        Privacy Policy may be updated periodically. Updates take effect upon posting.
    </p>

    <h3>16. Contact</h3>
    <p>
        Questions or requests: <strong>support@flovide.com</strong>
    </p>

    <h3>17. Governing Law</h3>
    <p>
        Governed by Canadian federal laws and applicable provincial laws where Flovide operates.
    </p>
</div>



            <!-- Nigeria -->
            <!-- Nigeria -->
            <div id="ng-agreement" class="policy-content prose prose-lg max-w-none hidden">
                <h1 class="text-3xl font-bold text-[#0F243D]">Privacy Policy & Terms and Conditions — Nigeria</h1>
                <p class="text-sm text-gray-500">Flovide • Last Updated: 23 January 2026</p>

                <h2 class="mt-6 font-bold text-2xl">1. Introduction</h2>
                <p>
                    This Privacy Policy and Terms and Conditions (“Policy”) explain how Flovide (“we”, “us”, “our”) collects, uses,
                    stores, and shares personal data, and the terms governing your use of our services in Nigeria.
                </p>
                <p>
                    We operate in compliance with the Nigeria Data Protection Regulation (NDPR), Central Bank of Nigeria (CBN)
                    regulations, Anti-Money Laundering (AML), and Counter-Terrorism Financing (CTF) laws.
                </p>

                <h2 class="mt-6 font-bold text-2xl">2. What Data We Collect</h2>

                <h3 class="mt-4 font-semibold">2.1 Personal Data</h3>
                <ul>
                    <li>Full name</li>
                    <li>Date of birth</li>
                    <li>Phone number</li>
                    <li>Email address</li>
                    <li>Residential address</li>
                </ul>

                <h3 class="mt-4 font-semibold">2.2 KYC & Compliance Data</h3>
                <ul>
                    <li>National Identification Number (NIN)</li>
                    <li>International passport, driver’s licence, or voter’s card</li>
                    <li>Utility bill or bank statement</li>
                    <li>Source of funds documentation</li>
                    <li>A live selfie/facial photo (and, where required, a short liveness video), used to verify your
                        identity against your ID document. Biometric facial information is used exclusively for
                        identity verification and fraud prevention and is never used for advertising, profiling,
                        marketing, or any unrelated purpose.</li>
                </ul>

                <h3 class="mt-4 font-semibold">2.3 Recipient Information</h3>
                <ul>
                    <li>Recipient full name</li>
                    <li>Phone number</li>
                    <li>Bank or wallet details</li>
                </ul>

                <h3 class="mt-4 font-semibold">2.4 Technical Data</h3>
                <ul>
                    <li>IP address</li>
                    <li>Device and browser type</li>
                    <li>App usage and page views</li>
                </ul>

                <h2 class="mt-6 font-bold text-2xl">3. Why We Collect Your Data</h2>
                <ul>
                    <li>Account creation and management</li>
                    <li>Processing local and international transfers</li>
                    <li>AML, CTF, and CBN compliance, including facial verification against your ID</li>
                    <li>Fraud prevention and security monitoring</li>
                    <li>Service communication and improvement</li>
                </ul>

                <h2 class="mt-6 font-bold text-2xl">4. Legal Basis (NDPR)</h2>
                <ul>
                    <li>Your consent</li>
                    <li>Contract performance</li>
                    <li>Legal obligations</li>
                    <li>Legitimate business interest</li>
                </ul>

                <h2 class="mt-6 font-bold text-2xl">5. Data Security</h2>
                <p>
                    We protect your data using encryption, secure servers, access control, and TLS-secured communication.
                </p>

                <h2 class="mt-6 font-bold text-2xl">6. Data Retention</h2>
                <p>
                    KYC and transaction records, including selfie and identity verification data, are retained for a
                    minimum of five (5) years in accordance with Nigerian regulations. Identity verification records,
                    including face data, are retained only for as long as required to comply with applicable legal and
                    regulatory obligations, after which they are securely deleted.
                </p>

                <h2 class="mt-6 font-bold text-2xl">7. Data Sharing</h2>
                <p>
                    We do not sell personal data. Information, including your selfie and identity documents for
                    verification purposes, may be shared with our identity verification partner <strong>Sumsub</strong>,
                    banks, regulators, compliance providers, and law enforcement where legally required. Identity
                    verification information, including face data, may be processed by trusted third-party identity
                    verification service providers acting on our behalf, solely to perform identity verification
                    services; these providers are contractually required to protect the confidentiality and security of
                    the information.
                </p>

                <h2 class="mt-6 font-bold text-2xl">8. Your Rights Under NDPR</h2>
                <ul>
                    <li>Right to access</li>
                    <li>Right to correction</li>
                    <li>Right to deletion (where legally permitted)</li>
                    <li>Right to object to processing</li>
                    <li>Right to lodge a complaint with the Nigeria Data Protection Bureau (NDPB)</li>
                </ul>

                <h2 class="mt-6 font-bold text-2xl">9. Cookies</h2>
                <p>
                    We use cookies for essential functionality, preference storage, and analytics. You may manage cookies in your
                    browser settings.
                </p>

                <h2 class="mt-6 font-bold text-2xl">10. Terms of Use</h2>

                <h3 class="mt-4 font-semibold">Eligibility</h3>
                <p>You must be at least 18 years old to use Flovide.</p>

                <h3 class="mt-4 font-semibold">User Obligations</h3>
                <ul>
                    <li>Provide accurate information</li>
                    <li>Use the service lawfully</li>
                    <li>Avoid fraud and prohibited activities</li>
                </ul>

                <h3 class="mt-4 font-semibold">Suspension & Termination</h3>
                <p>
                    Accounts may be suspended or terminated for regulatory reasons, fraud, or breach of this Policy.
                </p>

                <h3 class="mt-4 font-semibold">Governing Law</h3>
                <p>
                    This Policy is governed by the laws of the Federal Republic of Nigeria.
                </p>

                <h2 class="mt-6 font-bold text-2xl">11. Policy Updates</h2>
                <p>
                    This Policy may be updated periodically. Continued use of Flovide constitutes acceptance of any changes.
                </p>
            </div>

            <div id="ng-dispute" class="policy-content prose prose-lg max-w-none hidden">
                <h1 class="text-3xl font-bold text-[#0F243D]">Dispute Resolution & Arbitration</h1>

                <h3>1. Informal Resolution</h3>
                <p>
                    If you have any complaint or dispute arising out of or relating to your use of Flovide’s services, 
                    you agree to first contact Flovide and attempt to resolve the issue informally. Complaints should be 
                    submitted through our customer support channels or via email to our designated support address at 
                    <strong>support@flovide.com</strong>.
                </p>
                <p>
                    Flovide will acknowledge and investigate complaints promptly and will make reasonable efforts to 
                    resolve the dispute within a commercially reasonable timeframe, in line with applicable Central Bank 
                    of Nigeria (CBN) consumer protection guidelines.
                </p>

                <h3>2. Escalation</h3>
                <p>
                    Where a dispute cannot be resolved through informal means, either party may escalate the matter in writing 
                    for further review. Flovide may request additional information to facilitate resolution.
                </p>
                <p>
                    If the dispute remains unresolved after escalation, it shall be referred to arbitration in accordance 
                    with the provisions below.
                </p>

                <h3>3. Arbitration</h3>
                <ul>
                    <li>All disputes shall be finally settled by arbitration and not through court proceedings, except where prohibited by law.</li>
                    <li>The arbitration shall be conducted in accordance with the Arbitration and Mediation Act, 2023 (Nigeria).</li>
                    <li>The seat and venue of arbitration shall be Lagos State, Nigeria.</li>
                    <li>A single arbitrator shall be appointed by mutual agreement of the parties or in accordance with the Act.</li>
                    <li>The arbitration proceedings shall be conducted in English.</li>
                </ul>

                <h3>4. Costs</h3>
                <p>
                    Each party shall bear its own legal costs and expenses. The costs of the arbitrator and arbitration proceedings 
                    shall be shared equally unless the arbitrator determines otherwise in the final award.
                </p>

                <h3>5. Confidentiality</h3>
                <p>
                    All arbitration proceedings, documents, submissions, and awards shall be treated as confidential, except where 
                    disclosure is required by law, regulation, or by a competent regulatory authority.
                </p>

                <h3>6. Interim Relief</h3>
                <p>
                    Nothing in this clause shall prevent either party from seeking interim or injunctive relief from a court of 
                    competent jurisdiction where necessary to protect rights or prevent irreparable harm.
                </p>

                <h3>7. Regulatory & Consumer Rights</h3>
                <ul>
                    <li>Your right to submit a complaint to the Central Bank of Nigeria (CBN) under its Consumer Protection Framework.</li>
                    <li>Your statutory rights under Nigerian law that cannot be lawfully excluded.</li>
                </ul>

                <h3>8. Governing Law</h3>
                <p>
                    This Dispute Resolution & Arbitration clause shall be governed by and construed in accordance with the laws 
                    of the Federal Republic of Nigeria.
                </p>
            </div>


        </div>
</section>

<script>
document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        const icon = header.querySelector('i');

        document.querySelectorAll('.accordion-content').forEach(c => {
            if (c !== content) c.classList.add('hidden');
        });

        document.querySelectorAll('.accordion-header i').forEach(i => {
            if (i !== icon) i.classList.remove('rotate-180');
        });

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });
});

// Open the section containing the active link by default
document.addEventListener('DOMContentLoaded', () => {
    const activeLink = document.querySelector('.policy-link.is-active');
    if (activeLink) {
        const content = activeLink.closest('.accordion-content');
        if (content) {
            content.classList.remove('hidden');
            const header = content.previousElementSibling;
            if (header) header.querySelector('i')?.classList.add('rotate-180');
        }
    }
});

document.querySelectorAll('.policy-link').forEach(link => {
    link.addEventListener('click', () => {
        let target = link.dataset.target;

        document.querySelectorAll('.policy-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.getElementById(target).classList.remove('hidden');

        document.querySelectorAll('.policy-link').forEach(l => l.classList.remove('is-active'));
        link.classList.add('is-active');

        window.scrollTo({ top: document.getElementById('privacy-policy').offsetParent ? document.querySelector('main').offsetTop - 20 : 0, behavior: 'smooth' });
    });
});
</script>





     







        <!-- footer -->
        @include('mainpage.footer')

    </main>

    @include('mainpage.script')

</body>

</html>
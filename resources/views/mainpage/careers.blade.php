<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Careers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            color: #252525;
        }
    </style>
</head>

<body>
    <!-- navbar  -->


    @include('mainpage.navbar')

    <header class="">
        <section class="bg-[#0F243D] md:h-[750px] md:mx-10 md:rounded-2xl text-white" id="mobileMenuButton">
            <!-- mobile menu -->
            <section class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
                <section class="flex justify-between items-center w-full">
                    <div>
                        <img src="../asserts/mobileLogo.svg" alt="" />
                    </div>
                    <div id="openSidebarBtn">
                        <img src="../asserts/menu-icon.svg" alt="" />
                    </div>
                </section>
            </section>
            <!-- Mobile Dropdown Menu -->
            <section class="md:hidden px-4 py-3 text-white w-full flex justify-center items-center">
                <!-- Dropdown Content -->
                <div id="mobileMenuContent" class="mt-2 absolute top-[15vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
                    <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[20px] font-medium space-y-6 ">
                        <a href="{{ route('personal') }}"
                            class="block px-4 py-2 text-white hover:bg-[#3B82F6] border border-[#3380C4] p-4 bg-[#1E5186] rounded-xl">
                            {{ __('Personal') }}
                        </a>
                        <a href="{{ route('business') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Business') }}
                        </a>
                        <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Developer') }}
                        </a>
                        <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Blog') }}
                        </a>
                        <a href="{{route('contactUs')}}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Contact Us') }}
                        </a>

                        <button
                            class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                            <a href="{{ route('login') }}" class=""> {{ __('Login') }} </a>
                        </button>

                        <button
                            class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                            <a href="{{ route('register.saveStepData') }}" class=""> {{ __('Get Started') }} </a>
                        </button>

                        @php
                            $flags = [
                                'en' => 'gb.svg',
                                'es' => 'es.svg',
                                'fr' => 'fr.svg',
                                'lg' => 'ug.svg',
                            ];

                            $currentLocale = app()->getLocale();
                            $currentFlag = $flags[$currentLocale] ?? 'gb.svg';
                        @endphp
                        <div class="relative">
                            <button onclick="toggleLang_mobile()" class="flex items-center space-x-2 border border-[#1D4ED8] rounded-full px-3 py-1 text-[#252525]">
                                <span>{{ strtoupper(app()->getLocale()) }} </span>
                                <img class="w-6 h-6 rounded-full object-cover" src="{{asset('../asserts/homepage/' . $currentFlag) }}" alt="Flag" />
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <ul id="langMenu_mobile" class="absolute hidden bg-white shadow-md rounded-lg p-2 mt-2 right-0">
                                <li><a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 hover:bg-gray-100">English</a></li>
                                <li><a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 hover:bg-gray-100">Spanish</a></li>
                                <li><a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 hover:bg-gray-100">French</a></li>
                                <li><a href="{{ route('lang.switch', 'lg') }}" class="block px-4 py-2 hover:bg-gray-100">Luganda</a></li>
                            </ul>
                        </div>
                    </ul>
                </div>
            </section>


            <div class="max-w-7xl mx-auto px-6 py-12 rounded-[40px]">
                <div class="text-center max-w-3xl mx-auto">
                    <p class="text-[14px] text-[#7bcf9e] font-medium mb-2">
                        Join Our Team
                    </p>
                    <h1 class="font-extrabold text-[40px] leading-[48px] mb-4">
                        Empowering Your
                        <br />
                        Financial Journey
                    </h1>
                    <p class="text-[14px] max-w-[520px] mx-auto mb-8">
                        Fast, secure, and reliable digital solutions for payments, savings, and everyday transactions.
                    </p>
                </div>
                <div class="mt-12 flex flex-col md:flex-row md:justify-center md:gap-8 gap-8 items-center">
                    <!-- First card -->
                    <div class="relative rounded-3xl overflow-hidden md:max-w-[320px] mx-auto md:mx-0">
                        <img src="../asserts/Personal/headerImage1.svg" alt="" />
                    </div>
                    <!-- Second card -->
                    <div
                        class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block">
                        <img src="../asserts/image 2 (1).png" alt="" />
                    </div>
                    <!-- Third card -->
                    <div
                        class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block">
                        <img src="../asserts/image 3.png" alt="" />
                    </div>
                </div>

                <div class="mt-16 flex flex-col md:flex-row md:justify-between items-center gap-6 md:gap-0 mx-auto">
                    <div class="flex items-center gap-10">
                        {{-- <img alt="OhentPay company logo white on transparent background" class="h-30 w-30" height="40"
                            src="../asserts/Personal/company1.png" /> --}}
                        <img class="h-30 w-12 dark:invert" src="../asserts/Personal/Capture.png">

                        <img alt="Sound wave style company logo white on transparent background" class="h-30 w-30"
                            height="40" src="../asserts/Personal/company2.png" />
                        <img alt="CentaDesk company logo white on transparent background" class="w-28" height="40"
                            src="../asserts/Personal/company3.png" />
                        <img alt="EW company logo white on transparent background" class="h-30 w-30" height="40"
                            src="../asserts/Personal/company4.png" />
                    </div>
                </div>
            </div>
        </section>
    </header>

    <main class="right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">

        <!-- 1. Mission/Mantra & Core Values -->
        <section class="max-w-7xl mx-auto px-6 md:px-8 pt-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F243D] text-center mb-12">
                Our Engine: Core Values That Drive Change
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Value 1: Innovation -->
                <div class="p-6 rounded-2xl bg-white shadow-xl border-t-4 border-[#3B82F6]">
                    <i class="fas fa-lightbulb text-3xl text-[#3B82F6] mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">Build Ahead</h3>
                    <p class="text-gray-600 text-sm">
                        We challenge the status quo and commit to continuous R&D. We innovate daily to solve complex
                        problems and create future-proof financial tools.
                    </p>
                </div>
                <!-- Value 2: Integrity -->
                <div class="p-6 rounded-2xl bg-white shadow-xl border-t-4 border-[#7bcf9e]">
                    <i class="fas fa-shield-alt text-3xl text-[#7bcf9e] mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">Earn Trust</h3>
                    <p class="text-gray-600 text-sm">
                        Trust is our currency. We operate with radical transparency, security, and accountability in
                        every line of code and customer interaction.
                    </p>
                </div>
                <!-- Value 3: Ownership -->
                <div class="p-6 rounded-2xl bg-white shadow-xl border-t-4 border-[#F6B34A]">
                    <i class="fas fa-hand-holding-usd text-3xl text-[#F6B34A] mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">Own the Outcome</h3>
                    <p class="text-gray-600 text-sm">
                        From concept to launch, we take full responsibility. We are empowered to make decisions and
                        deliver tangible results for our users and partners.
                    </p>
                </div>
                <!-- Value 4: Collaboration -->
                <div class="p-6 rounded-2xl bg-white shadow-xl border-t-4 border-[#E74C3C]">
                    <i class="fas fa-users text-3xl text-[#E74C3C] mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">One Team, One Mission</h3>
                    <p class="text-gray-600 text-sm">
                        We believe diverse backgrounds fuel better financial products. We collaborate fiercely, sharing
                        knowledge to achieve a unified vision.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. Benefits & Perks -->
        <section class="max-w-7xl mx-auto px-6 md:px-8 pt-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F243D] text-center mb-6">
                Invest in Yourself: Perks & Benefits
            </h2>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12">
                We ensure our team has the resources and support needed to thrive, both professionally and personally.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-center">
                <!-- Benefit 1: Healthcare -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#3B82F6]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-notes-medical text-2xl text-[#3B82F6]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Premium Healthcare</h4>
                    <p class="text-sm text-gray-500">Comprehensive medical, dental, and vision coverage starting day
                        one.</p>
                </div>
                <!-- Benefit 2: Flexible Work -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#7bcf9e]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-home text-2xl text-[#7bcf9e]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Flexible Hybrid Work</h4>
                    <p class="text-sm text-gray-500">Empowerment to choose where you work best, blending office culture
                        and remote flexibility.</p>
                </div>
                <!-- Benefit 3: Retreats -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#F6B34A]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-plane-departure text-2xl text-[#F6B34A]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Annual Team Retreats</h4>
                    <p class="text-sm text-gray-500">Company-wide offsites to collaborate, relax, and build stronger
                        bonds outside the office.</p>
                </div>
                <!-- Benefit 4: Learning -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#E74C3C]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-2xl text-[#E74C3C]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Learning Stipends</h4>
                    <p class="text-sm text-gray-500">Generous budget for courses, certifications, and conferences to
                        accelerate your career trajectory.</p>
                </div>
                <!-- Benefit 5: Financial -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#3B82F6]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-2xl text-[#3B82F6]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Equity & Vesting</h4>
                    <p class="text-sm text-gray-500">Ownership stake in the company to invest in your long-term
                        financial future alongside ours.</p>
                </div>
                <!-- Benefit 6: Wellness -->
                <div class="p-6 bg-white rounded-xl shadow-md flex flex-col items-center">
                    <div class="w-14 h-14 bg-[#7bcf9e]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-mug-hot text-2xl text-[#7bcf9e]"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Wellness & Meals</h4>
                    <p class="text-sm text-gray-500">Daily catered lunches, stocked kitchens, and wellness programs for
                        physical and mental health.</p>
                </div>
            </div>
        </section>

        <!-- 3. Open Roles Section -->
        <section class="max-w-7xl mx-auto px-6 md:px-8 py-20 bg-gray-50 rounded-3xl">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F243D] text-center mb-4">
                Current Opportunities
            </h2>
            <p class="text-center text-gray-600 max-w-3xl mx-auto mb-12">
                Ready to make your mark? We are always looking for bold thinkers and builders who want to redefine how
                the world manages money.
            </p>

            <div class="space-y-6">
                <!-- Role 1: Senior Backend Engineer -->
                <div class="space-y-6">
    @forelse ($jobs as $job)
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="text-2xl font-bold text-[#0F243D] mb-2 flex justify-between items-center">
                <span>{{ $job->title }}</span>
                <span class="text-sm text-gray-500 font-medium">
                    {{ $job->job_type }} | {{ $job->location }}
                </span>
            </h3>

            <p class="text-gray-600 mb-4">
                {!! nl2br(e(Str::limit($job->description, 200))) !!}
            </p>

<div class="space-y-3 text-sm mb-6">
    <p class="font-semibold text-[#0F243D]">Key Requirements:</p>
    <ul class="list-disc pl-5 text-gray-600 space-y-1">
        @foreach(explode("\n", $job->requirements) as $req)
            @if(trim($req) != '')
                <li>{{ trim($req) }}</li>
            @endif
        @endforeach
    </ul>
</div>

            {{-- <a href="#"
                class="inline-block bg-[#3B82F6] text-white font-semibold py-3 px-8 rounded-full hover:bg-[#3B82F6]/90 transition-colors text-sm shadow-md">
                Apply Now <i class="fas fa-arrow-right ml-2"></i>
            </a> --}}

             <a href="mailto:career@flovide.com" class="inline-block bg-[#F6B34A] text-[#0F243D] font-semibold py-3 px-8 rounded-full hover:bg-[#F6B34A]/90 transition-colors text-lg shadow-md">
                    career@flovide.com <i class="fas fa-envelope ml-2"></i>
                </a>
        </div>
    @empty
        <p class="text-gray-500 text-center">No career openings at the moment.</p>
    @endforelse
</div>


            </div>
        </section>

        <!-- 4. Life at the Company & Testimonial -->
        <section class="max-w-7xl mx-auto px-6 md:px-8 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Life at the Company & Diversity -->
                <div>
                    <span class="text-sm font-semibold text-[#F6B34A] uppercase tracking-wider mb-2 block">Our
                        Culture</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F243D] mb-4">
                        Life at the Edge of Fintech
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Working here means contributing to a hyper-growth environment where intellectual curiosity is
                        rewarded, and speed matters. We balance ambitious targets with a human-first approach: expect
                        rapid iteration, high-impact projects, and a team that celebrates wins (and learns quickly from
                        mistakes). We foster an environment where your best work is ahead of you, surrounded by
                        global-class talent focused on a shared mission to democratise finance.
                    </p>

                    <!-- Diversity Statement -->
                    <div class="mt-8 p-6 bg-[#0F243D]/5 rounded-xl border-l-4 border-[#3B82F6]">
                        <h4 class="font-bold text-xl text-[#0F243D] mb-2 flex items-center gap-2">
                            <i class="fas fa-venus-mars text-[#3B82F6]"></i> Diversity & Inclusion
                        </h4>
                        <p class="text-sm text-gray-600">
                            We are dedicated to building a workplace that reflects the diverse world we serve. We
                            champion equal opportunity for all applicants and do not discriminate based on race,
                            religion, color, national origin, gender, sexual orientation, age, marital status, or
                            disability. Join us in building a truly global and inclusive financial ecosystem.
                        </p>
                    </div>
                </div>

                <!-- Employee Testimonial Mockup -->
                <div class="p-8 bg-[#0F243D] text-white rounded-2xl shadow-xl">
                    <i class="fas fa-quote-left text-4xl text-[#7bcf9e] mb-4"></i>
                    <blockquote class="text-xl italic mb-6">
                        "The pace is fast, but the impact is real. Every project directly challenges traditional finance
                        and empowers millions. It's the perfect mix of high-tech and human purpose."
                    </blockquote>
                    <div class="flex items-center">
                        <!-- Placeholder image with initials -->
                        <img src="https://placehold.co/56x56/F6B34A/0F243D?text=JD" onerror="this.onerror=null; this.src='https://placehold.co/56x56/F6B34A/0F243D?text=JD';"
                            alt="Employee Jane Doe" class="w-14 h-14 rounded-full mr-4 border-2 border-[#7bcf9e] object-cover">
                        <div>
                            <p class="font-bold text-lg">Jane Doe</p>
                            <p class="text-[#7bcf9e]">Lead Data Scientist</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="max-w-7xl mx-auto px-6 md:px-8 py-10 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#0F243D] mb-4">
                    Can't find what you're looking for?
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    We're always on the lookout for exceptional talent. Send us your resume and a cover letter to:
                </p>
                <a href="mailto:career@flovide.com" class="inline-block bg-[#F6B34A] text-[#0F243D] font-semibold py-3 px-8 rounded-full hover:bg-[#F6B34A]/90 transition-colors text-lg shadow-md">
                    career@flovide.com <i class="fas fa-envelope ml-2"></i>
                </a>
            </div>
        </section>



        <!-- footer -->
        @include('mainpage.footer')

    </main>

    @include('mainpage.script')

</body>

</html>
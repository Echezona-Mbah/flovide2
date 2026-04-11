<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Flovide Blog | Insights on Digital Payments & Remittances</title>
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
                --flovide-blue: #1E5186;
                --flovide-light-blue: #F0F7FF;
                --text-main: #1A1A1A;
                --text-muted: #666666;
            }
            body {
                color: #252525;
                font-family: 'Inter', sans-serif;
                /* color: var(--text-main); */
                background-color: #FFFFFF; 
            }
            .bg-flovide { background-color: var(--flovide-blue); }
            .text-flovide { color: var(--flovide-blue); }
            .border-flovide { border-color: var(--flovide-blue); }
            
            .premium-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            }

            .premium-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            }
            
            .category-tag {
                background-color: var(--flovide-light-blue);
                color: var(--flovide-blue);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            /* Hero subtle shapes */
            .hero-shape {
                position: absolute;
                z-index: -1;
                filter: blur(80px);
                opacity: 0.4;
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

        <main class="md:relative md:top-[1vh] right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">

            <!-- Hero Section -->
            <section class="relative overflow-hidden pt-20 pb-12 px-6">
                <div class="hero-shape bg-blue-200 w-64 h-64 top-[-50px] left-[-50px] rounded-full"></div>
                <div class="hero-shape bg-blue-100 w-96 h-96 bottom-[-100px] right-[-100px] rounded-full"></div>
                
                <div class="max-w-6xl mx-auto text-center">
                    <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6">Flovide Blog</h1>
                    <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                        Insights, updates, and guides on digital payments, money transfers, and financial growth.
                    </p>
                </div>
            </section>
            
            <!-- Categories Section -->
            <section class="max-w-6xl mx-auto px-6 mb-16">
                <div class="flex flex-wrap justify-center gap-3">
                    <button class="px-6 py-2 rounded-full border border-flovide bg-flovide text-white text-sm font-medium">All Topics</button>
                    {{-- <button class="px-6 py-2 rounded-full border border-gray-200 hover:border-flovide hover:text-flovide transition text-sm font-medium">Money Transfers</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 hover:border-flovide hover:text-flovide transition text-sm font-medium">Fintech Tips</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 hover:border-flovide hover:text-flovide transition text-sm font-medium">Business Payments</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 hover:border-flovide hover:text-flovide transition text-sm font-medium">Security</button>
                    <button class="px-6 py-2 rounded-full border border-gray-200 hover:border-flovide hover:text-flovide transition text-sm font-medium">Product Updates</button> --}}
                </div>
            </section> 

        
            <!-- Featured Blog Section -->
            <section class="max-w-6xl mx-auto px-6 md:mt-20 mb-20">
                <div class="premium-card bg-white rounded-3xl overflow-hidden flex flex-col md:flex-row border border-gray-50">
                    <div class="md:w-3/5 h-64 md:h-auto bg-gray-200 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=1200" alt="Featured Article" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="md:w-2/5 p-8 md:p-12 flex flex-col justify-center">
                        <span class="category-tag px-3 py-1 rounded-md inline-block mb-4 self-start">Remittance</span>
                        <h2 class="text-3xl font-bold mb-4 leading-tight">The Future of Cross-Border Payments in Africa</h2>
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            How digital wallets and real-time payment rails are reducing costs and increasing speed for millions of users across the continent.
                        </p>
                        {{-- <a href="#" class="text-flovide font-bold flex items-center group">
                            Read More 
                            <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a> --}}
                    </div>
                </div>
            </section>


            <!-- Latest Articles Grid -->
            <section class="max-w-6xl mx-auto px-6 mb-24">
                <div class="flex justify-between items-end mb-10">
                    <h3 class="text-2xl font-bold">Latest Articles</h3>
                    {{-- <a href="#" class="text-sm font-semibold text-flovide">View all →</a> --}}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Article 1 -->
                    <div class="premium-card rounded-2xl overflow-hidden border border-gray-50 flex flex-col">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1611974717483-5853dc9cce9c?auto=format&fit=crop&q=80&w=600" alt="Post" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="category-tag px-2 py-1 rounded inline-block mb-3 self-start">Security</span>
                            <h4 class="text-xl font-bold mb-3">5 Ways to Secure Your Online Transactions</h4>
                            <p class="text-gray-500 text-sm mb-6 flex-grow">Protecting your financial data is our top priority. Learn how to stay one step ahead of digital threats.</p>
                            <div class="flex items-center text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                                <span>Oct 24, 2023</span>
                                <span class="mx-2">•</span>
                                <span>5 min read</span>
                            </div>
                        </div>
                    </div>

                    <!-- Article 2 -->
                    <div class="premium-card rounded-2xl overflow-hidden border border-gray-50 flex flex-col">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&q=80&w=600" alt="Post" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="category-tag px-2 py-1 rounded inline-block mb-3 self-start">Business</span>
                            <h4 class="text-xl font-bold mb-3">Expanding Your Business Beyond Borders</h4>
                            <p class="text-gray-500 text-sm mb-6 flex-grow">The ultimate guide for African entrepreneurs looking to receive payments from international clients effortlessly.</p>
                            <div class="flex items-center text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                                <span>Oct 20, 2023</span>
                                <span class="mx-2">•</span>
                                <span>8 min read</span>
                            </div>
                        </div>
                    </div>

                    <!-- Article 3 -->
                    <div class="premium-card rounded-2xl overflow-hidden border border-gray-50 flex flex-col">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&q=80&w=600" alt="Post" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="category-tag px-2 py-1 rounded inline-block mb-3 self-start">Fintech Tips</span>
                            <h4 class="text-xl font-bold mb-3">Understanding Exchange Rates: What You Need to Know</h4>
                            <p class="text-gray-500 text-sm mb-6 flex-grow">Why rates fluctuate and how Flovide ensures you get the most value for your transfers every single time.</p>
                            <div class="flex items-center text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                                <span>Oct 18, 2023</span>
                                <span class="mx-2">•</span>
                                <span>4 min read</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action Section -->
            <section class="max-w-5xl mx-auto px-6 mb-24">
                <div class="bg-flovide rounded-[2rem] p-10 md:p-16 text-center text-white relative overflow-hidden">
                    <!-- Decorative circle -->
                    <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-white opacity-5 rounded-full"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">Send money globally with Flovide</h2>
                        <p class="text-blue-100 text-lg mb-10 max-w-xl mx-auto font-light">
                            Join thousands of users who trust Flovide for fast, secure, and affordable international payments.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            {{-- <button class="bg-white text-flovide px-8 py-4 rounded-full font-bold hover:bg-blue-50 transition">Get Started</button>
                            <button class="border border-white border-opacity-30 text-white px-8 py-4 rounded-full font-bold hover:bg-white hover:bg-opacity-10 transition">Contact Sales</button> --}}
                        </div>
                    </div>
                </div>
            </section>
            

            <!-- get app on store -->
            @include('mainpage.getapp')


            <!-- footer -->
            @include('mainpage.footer')

        </main>

        @include('mainpage.script')

    </body>
</html>
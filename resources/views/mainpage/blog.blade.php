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
                open: false,
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
            
            <!-- Categories Filter -->
            <section class="max-w-6xl mx-auto px-6 mb-16">
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('blog') }}"
                       class="px-6 py-2 rounded-full border text-sm font-medium transition {{ !$activeCategory ? 'border-flovide bg-flovide text-white' : 'border-gray-200 hover:border-flovide hover:text-flovide' }}">
                        All Topics
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('blog', ['category' => $cat->slug]) }}"
                       class="px-6 py-2 rounded-full border text-sm font-medium transition {{ $activeCategory && $activeCategory->id === $cat->id ? 'border-flovide bg-flovide text-white' : 'border-gray-200 hover:border-flovide hover:text-flovide' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </section>

        
            <!-- Featured Blog Section -->
            @if($featuredPost)
            <section class="max-w-6xl mx-auto px-6 md:mt-20 mb-20">
                <a href="{{ route('blog.show', $featuredPost->slug) }}" class="block">
                <div class="premium-card bg-white rounded-3xl overflow-hidden flex flex-col md:flex-row border border-gray-50">
                    <div class="md:w-3/5 h-64 md:h-auto bg-gray-200 relative overflow-hidden">
                        @if($featuredPost->featured_image)
                            <img src="{{ asset('storage/' . $featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 w-full h-full" style="background: linear-gradient(135deg, #1E5186 0%, #3b82f6 100%);"></div>
                        @endif
                    </div>
                    <div class="md:w-2/5 p-8 md:p-12 flex flex-col justify-center">
                        @if($featuredPost->category)
                            <span class="category-tag px-3 py-1 rounded-md inline-block mb-4 self-start">{{ $featuredPost->category->name }}</span>
                        @endif
                        <h2 class="text-3xl font-bold mb-4 leading-tight">{{ $featuredPost->title }}</h2>
                        @if($featuredPost->excerpt)
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            {{ Str::limit($featuredPost->excerpt, 180) }}
                        </p>
                        @endif
                        <div class="flex items-center text-xs text-gray-400 gap-2">
                            <span>{{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : $featuredPost->created_at->format('M d, Y') }}</span>
                            @if($featuredPost->read_time)
                                <span>•</span>
                                <span>{{ $featuredPost->read_time }} min read</span>
                            @endif
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-flovide font-semibold text-sm">
                            Read article
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
                </a>
            </section>
            @endif


            <!-- Latest Articles Grid -->
            <section class="max-w-6xl mx-auto px-6 mb-24">
                <div class="flex justify-between items-end mb-10">
                    <h3 class="text-2xl font-bold">
                        {{ $activeCategory ? $activeCategory->name : 'Latest Articles' }}
                    </h3>
                    <span class="text-sm text-gray-400">{{ $posts->total() }} {{ Str::plural('article', $posts->total()) }}</span>
                </div>

                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    @foreach($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="premium-card rounded-2xl overflow-hidden border border-gray-50 flex flex-col group" style="text-decoration: none; color: inherit;">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            @if($post->category)
                                <span class="category-tag px-2 py-1 rounded inline-block mb-3 self-start">{{ $post->category->name }}</span>
                            @endif
                            <h4 class="text-xl font-bold mb-3 leading-snug">{{ $post->title }}</h4>
                            @if($post->excerpt)
                                <p class="text-gray-500 text-sm mb-6 flex-grow">{{ Str::limit($post->excerpt, 120) }}</p>
                            @else
                                <p class="text-gray-500 text-sm mb-6 flex-grow">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                            @endif
                            <div class="flex items-center text-xs text-gray-400 mt-auto pt-4 border-t border-gray-50">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                                @if($post->read_time)
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->read_time }} min read</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                @else
                <div class="text-center py-20">
                    <svg class="mx-auto mb-4 text-gray-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p class="text-gray-400 text-lg">No articles found{{ $activeCategory ? ' in this category' : '' }}.</p>
                    @if($activeCategory)
                        <a href="{{ route('blog') }}" class="mt-4 inline-block text-sm font-semibold text-flovide">Browse all articles →</a>
                    @endif
                </div>
                @endif

                {{-- Pagination --}}
                @if($posts->hasPages())
                <div class="flex justify-center mt-14">
                    <nav class="flex items-center gap-1" aria-label="Pagination">
                        {{-- Previous --}}
                        @if($posts->onFirstPage())
                            <span class="px-4 py-2 rounded-full border border-gray-200 text-gray-300 text-sm cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 rounded-full border border-gray-200 text-gray-600 text-sm hover:border-flovide hover:text-flovide transition">← Prev</a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($posts->getUrlRange(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $page => $url)
                            @if($page == $posts->currentPage())
                                <span class="px-4 py-2 rounded-full text-sm font-semibold text-white bg-flovide border border-flovide">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-flovide hover:text-flovide transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 rounded-full border border-gray-200 text-gray-600 text-sm hover:border-flovide hover:text-flovide transition">Next →</a>
                        @else
                            <span class="px-4 py-2 rounded-full border border-gray-200 text-gray-300 text-sm cursor-not-allowed">Next →</span>
                        @endif
                    </nav>
                </div>
                <p class="text-center text-xs text-gray-400 mt-3">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }} &mdash; {{ $posts->total() }} total articles</p>
                @endif

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
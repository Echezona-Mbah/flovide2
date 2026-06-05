<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $post->meta_title ?? $post->title }} | Flovide Blog</title>
        <meta name="description" content="{{ $post->meta_description ?? $post->excerpt }}" />
        
        <!-- Open Graph / Social Media Meta Tags -->
        <meta property="og:type" content="article" />
        <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}" />
        <meta property="og:description" content="{{ $post->meta_description ?? $post->excerpt }}" />
        @if($post->featured_image)
            <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}" />
        @endif
        
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

            /* Article prose styling for rendered markdown content */
            .prose h1, .prose h2, .prose h3, .prose h4 {
                color: #1a202c;
                font-weight: 700;
                margin-top: 1.75em;
                margin-bottom: 0.5em;
                line-height: 1.25;
            }
            .prose h1 { font-size: 2.25rem; }
            .prose h2 { font-size: 1.75rem; border-bottom: 1px solid #edf2f7; padding-bottom: 0.3em; }
            .prose h3 { font-size: 1.4rem; }
            .prose p {
                margin-top: 0px;
                margin-bottom: 1.25rem;
                line-height: 1.75;
                color: #4a5568;
            }
            .prose ul, .prose ol {
                margin-top: 0px;
                margin-bottom: 1.25rem;
                padding-left: 1.5rem;
            }
            .prose ul { list-style-type: disc; }
            .prose ol { list-style-type: decimal; }
            .prose li {
                margin-top: 0.25rem;
                margin-bottom: 0.25rem;
                color: #4a5568;
            }
            .prose blockquote {
                font-style: italic;
                color: #4a5568;
                border-left: 4px solid #1E5186;
                padding-left: 1rem;
                margin: 1.5rem 0;
            }
            .prose strong {
                color: #1a202c;
                font-weight: 600;
            }
            .prose a {
                color: #1E5186;
                text-decoration: underline;
                font-weight: 500;
            }
            .prose a:hover {
                color: #2b6cb0;
            }
            .prose img {
                border-radius: 0.75rem;
                margin: 2rem auto;
                max-width: 100%;
                height: auto;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }
            .prose pre {
                background-color: #f7fafc;
                padding: 1rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                margin: 1.5rem 0;
                border: 1px solid #e2e8f0;
            }
            .prose code {
                font-family: monospace;
                background-color: #edf2f7;
                padding: 0.2rem 0.4rem;
                border-radius: 0.25rem;
                font-size: 0.875em;
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
                            <img src="{{ asset('../asserts/mobileLogo.svg') }}" alt="Flovide Logo" class="h-8">
                        </div>
                        <div id="openSidebarBtn">
                            <img src="{{ asset('../asserts/menu-icon.svg') }}" alt="Menu Icon" class="h-6">
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

        <main class="md:relative md:top-[1vh] right-0 left-0 mx-auto space-y-10 md:space-y-16 overflow-x-hidden pt-8">

            <!-- Back Button and Category Info -->
            <div class="max-w-4xl mx-auto px-6">
                <a href="{{ route('blog') }}" class="inline-flex items-center text-sm font-semibold text-flovide hover:underline gap-2 mb-6">
                    <i class="fas fa-arrow-left"></i> Back to Blog
                </a>
            </div>

            <!-- Blog Post Header Section -->
            <article class="max-w-4xl mx-auto px-6 pb-24">
                <header class="mb-10">
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        @if($post->category)
                            <a href="{{ route('blog', ['category' => $post->category->slug]) }}" class="category-tag px-3 py-1 rounded-md inline-block hover:opacity-90">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        <span class="text-xs text-gray-400">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </span>
                        @if($post->read_time)
                            <span class="text-gray-300">•</span>
                            <span class="text-xs text-gray-400">
                                {{ $post->read_time }} min read
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        {{ $post->title }}
                    </h1>

                    @if($post->excerpt)
                        <p class="text-lg md:text-xl text-gray-500 font-light leading-relaxed mb-8">
                            {{ $post->excerpt }}
                        </p>
                    @endif

                    <!-- Author Details Header -->
                    @if($post->author)
                        <div class="flex items-center gap-3 py-4 border-y border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                                @if($post->author->profile_picture)
                                    <img src="{{ asset('storage/' . $post->author->profile_picture) }}" alt="{{ $post->author->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $post->author->name }}</p>
                                <p class="text-xs text-gray-400">{{ $post->author->role ?? 'Contributor' }}</p>
                            </div>
                        </div>
                    @endif
                </header>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="w-full rounded-2xl overflow-hidden mb-12 shadow-sm">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-auto object-cover max-h-[480px]">
                    </div>
                @endif

                <!-- Post Body Content -->
                <div class="prose max-w-none mb-16">
                    {!! Illuminate\Support\Str::markdown($post->content) !!}
                </div>

                <!-- Tags Section -->
                @if($post->tags->count() > 0)
                    <div class="border-t border-gray-100 pt-8 mb-12">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Tags</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1.5 rounded-full font-medium">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Author Card Footer -->
                @if($post->author)
                    <div class="bg-gray-50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6 border border-gray-100">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white flex items-center justify-center overflow-hidden border border-gray-200 shrink-0">
                            @if($post->author->profile_picture)
                                <img src="{{ asset('storage/' . $post->author->profile_picture) }}" alt="{{ $post->author->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            @endif
                        </div>
                        <div class="text-center md:text-left flex-grow">
                            <span class="text-[10px] font-bold tracking-wider text-flovide uppercase">Written by</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $post->author->name }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $post->author->role ?? 'Contributor' }}</p>
                            @if($post->author->experience)
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ $post->author->experience }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </article>

            <!-- Related Articles Section -->
            @if($relatedPosts->count() > 0)
                <section class="bg-[#F8FAFC] py-20 border-t border-gray-100">
                    <div class="max-w-6xl mx-auto px-6">
                        <h3 class="text-2xl font-bold mb-10 text-center md:text-left">Related Articles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                            @foreach($relatedPosts as $rPost)
                                <a href="{{ route('blog.show', $rPost->slug) }}" class="premium-card bg-white rounded-2xl overflow-hidden border border-gray-50 flex flex-col group" style="text-decoration: none; color: inherit;">
                                    <div class="h-48 bg-gray-100 overflow-hidden">
                                        @if($rPost->featured_image)
                                            <img src="{{ asset('storage/' . $rPost->featured_image) }}" alt="{{ $rPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6 flex flex-col flex-grow">
                                        @if($rPost->category)
                                            <span class="category-tag px-2 py-1 rounded inline-block mb-3 self-start">{{ $rPost->category->name }}</span>
                                        @endif
                                        <h4 class="text-lg font-bold mb-2 leading-snug">{{ $rPost->title }}</h4>
                                        <p class="text-gray-500 text-xs mt-auto pt-4 border-t border-gray-50">
                                            {{ $rPost->published_at ? $rPost->published_at->format('M d, Y') : $rPost->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Get App Store CTA Section -->
            @include('mainpage.getapp')

            <!-- footer -->
            @include('mainpage.footer')

        </main>

        @include('mainpage.script')

    </body>
</html>

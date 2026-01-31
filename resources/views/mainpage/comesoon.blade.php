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

<section class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 px-6 text-center">

    <!-- Icon -->
    <div class="mb-6 w-16 h-16 flex items-center justify-center rounded-full bg-blue-100">
        <i class="fas fa-layer-group text-blue-600 text-2xl"></i>
    </div>

    <!-- Title -->
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">
        Coming Soon
    </h1>

    <!-- Subtitle -->
    <p class="text-blue-700 font-semibold text-lg mb-6">
        Our Impact, Documented
    </p>

    <!-- Divider -->
    <div class="w-24 h-1 bg-blue-600 rounded-full mb-8"></div>

    <!-- Content -->
    <p class="max-w-3xl text-gray-600 leading-relaxed text-base md:text-lg mb-10">
        At Flovide, we are carefully assembling in-depth case studies, project timelines,
        and verified success stories from our initiatives across world.
        <br class="hidden sm:block">
        This page will be updated shortly with transparent insights, measurable outcomes,
        and real-world impact data.
    </p>

    <!-- Back Button -->
    <button onclick="history.back()"
        class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-md">
        <i class="fas fa-arrow-left"></i>
        Go Back
    </button>

</section>





<script>
    function goBack() {
        if (document.referrer !== "") {
            window.history.back();
        } else {
            window.location.href = "/";
        }
    }
</script>

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


</script>





     







        <!-- footer -->
        @include('mainpage.footer')

    </main>

    @include('mainpage.script')

</body>

</html>
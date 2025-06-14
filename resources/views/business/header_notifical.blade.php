<div class="flex items-center gap-4 flex-wrap">
    <button aria-label="Notifications"
        class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
        <i class="fas fa-bell text-[#4B4B4B] text-lg">
        </i>
        <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]">
        </span>
    </button>
    <button aria-label="Select organization Nexus Global"
        class="bg-white rounded-full flex items-center gap-2 py-2 px-4 text-sm font-normal text-[#1E1E1E] whitespace-nowrap">
        <i class="fas fa-bullseye text-[#1E1E1E]">
        </i>
        Nexus Global
        <i class="fas fa-chevron-right text-[#1E1E1E]">
        </i>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" aria-label="Logout" class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-[#1E1E1E] text-lg"></i>
            </button>
        </form>
        
</div>
<x-guest-layout>
    <div class="relative min-h-screen bg-slate-950 text-white selection:bg-orange-500/30">
        <!-- Floating orbs -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[20%] right-[-10%] w-[30%] h-[30%] bg-orange-600/10 blur-[120px] rounded-full animate-pulse"></div>
            <div class="absolute bottom-[10%] left-[-5%] w-[40%] h-[40%] bg-blue-600/10 blur-[150px] rounded-full"></div>
        </div>

        <div class="relative z-10">
            <!-- Header Section -->
            <div class="pt-20 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div>
                        <nav class="flex mb-4 text-sm text-white/40 space-x-2">
                            <a href="{{ route('welcome') }}" class="hover:text-white transition">Home</a>
                            <span>/</span>
                            <span class="text-white">Events</span>
                        </nav>
                        <h1 class="text-4xl md:text-5xl lg:text-7xl font-black tracking-tighter">
                            Upcoming <span class="text-gradient">Events</span>
                        </h1>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-white/40 text-right max-w-xs font-medium uppercase tracking-widest text-[10px] mb-2">Current Activity</p>
                        <div class="flex -space-x-3 overflow-hidden">
                            @for($i=0; $i<5; $i++)
                                <img src="https://i.pravatar.cc/100?u={{ $i }}" class="inline-block h-10 w-10 rounded-full ring-2 ring-slate-950" alt="avatar">
                            @endfor
                            <div class="flex items-center justify-center h-10 w-10 rounded-full ring-2 ring-slate-950 bg-slate-800 text-xs font-bold">+12</div>
                        </div>
                    </div>
                </div>

                <livewire:event-search />
            </div>
        </div>

        <footer class="py-12 border-t border-white/5 text-center text-white/20 text-sm">
            <p>&copy; {{ date('Y') }} Eventix. Discover the extraordinary.</p>
        </footer>
    </div>
</x-guest-layout>

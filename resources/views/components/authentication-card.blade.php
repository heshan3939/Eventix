<div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950 text-white selection:bg-orange-500/30 selection:text-white">
    <!-- Background Decoration -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-orange-600/10 blur-[120px] rounded-full animate-float"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 blur-[120px] rounded-full" style="animation-delay: 3s;"></div>
    </div>

    <div class="relative z-10 w-full sm:max-w-md mt-6">
        <!-- Logo Container -->
        <div class="flex justify-center mb-10">
            <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-orange-500/20 group-hover:scale-110 transition-transform duration-500">
                    <span class="font-black text-2xl text-white">E</span>
                </div>
                <span class="text-3xl font-black tracking-tighter uppercase text-white">Eventix</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="w-full bg-white/5 backdrop-blur-3xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-[2.5rem] p-10">
            {{ $slot }}
        </div>
        
        <!-- Subtle Footer -->
        <div class="mt-8 text-center text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">
            Secure Access Protected by Eventix Security
        </div>
    </div>
</div>

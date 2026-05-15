@auth
    <div class="fixed bottom-6 right-6 z-50">
        <div class="group flex items-center space-x-3 bg-slate-900/80 backdrop-blur-xl border border-white/10 px-5 py-2.5 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-105 hover:border-green-500/50">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-20"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
            </span>
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] leading-none mb-1">Status</span>
                <span class="text-xs font-bold text-white leading-none">Authenticated</span>
            </div>
        </div>
    </div>
@endauth

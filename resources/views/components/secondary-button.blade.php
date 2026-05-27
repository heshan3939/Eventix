<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 rounded-xl font-black text-xs text-white/60 uppercase tracking-widest hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-white/20 disabled:opacity-25 transition-all duration-150']) }}>
    {{ $slot }}
</button>

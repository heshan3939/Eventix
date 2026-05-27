<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl font-black text-xs text-rose-400 uppercase tracking-widest hover:bg-rose-500/20 hover:text-rose-300 focus:outline-none focus:ring-1 focus:ring-rose-500/30 disabled:opacity-25 transition-all duration-150']) }}>
    {{ $slot }}
</button>

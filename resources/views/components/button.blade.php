<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-orange-500 to-rose-600 border border-transparent rounded-2xl font-black text-xs text-white uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-orange-500/20 disabled:opacity-50 transition-all duration-300 shadow-xl shadow-orange-500/20']) }}>
    {{ $slot }}
</button>

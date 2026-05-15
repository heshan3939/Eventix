@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-white/5 border border-white/10 text-white placeholder:text-white/20 focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-xl shadow-sm py-3 px-4 transition-all duration-300']) !!}>

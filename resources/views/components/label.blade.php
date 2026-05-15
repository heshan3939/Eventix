@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-[10px] uppercase tracking-[0.2em] text-white/40 mb-2 ms-1']) }}>
    {{ $value ?? $slot }}
</label>

@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-5 border-b border-white/8">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="text-base font-black text-white tracking-tight">
                {{ $title }}
            </div>
        </div>
    </div>

    <div class="px-6 py-5 text-sm text-white/50 font-medium">
        {{ $content }}
    </div>

    <div class="flex flex-row justify-end items-center gap-2 px-6 py-4 border-t border-white/8">
        {{ $footer }}
    </div>
</x-modal>

@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-5 border-b border-white/8">
        <div class="flex items-center gap-3">
            <div class="w-0.5 h-5 bg-gradient-to-b from-orange-500 to-rose-600 rounded-full flex-shrink-0"></div>
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

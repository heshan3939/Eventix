<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Payment <span class="text-rose-500">Cancelled</span>
        </h2>
        <p class="text-white/40 text-sm font-medium mt-2">Your payment was not completed. No charges have been made.</p>
    </x-slot>

    <div class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Cancel banner --}}
            <div class="glass-dark border border-rose-500/20 rounded-[2.5rem] p-10 shadow-2xl mb-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-rose-500/5 pointer-events-none"></div>
                <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-500/20">
                    <svg class="w-10 h-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white tracking-tight">Payment Not Completed</h3>
                <p class="text-white/40 text-sm mt-2 max-w-sm mx-auto">
                    You cancelled the checkout. Your pending booking(s) are still reserved — you can try again from your bookings page.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('bookings.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-600 text-white font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-orange-500/20">
                    View My Bookings
                </a>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-sm hover:bg-white/10 transition-all">
                    Browse Events
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

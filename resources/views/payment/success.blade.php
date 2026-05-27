<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Payment <span class="text-gradient">Confirmed</span>
        </h2>
        <p class="text-white/40 text-sm font-medium mt-2">Your tickets are booked — get ready for an unforgettable experience.</p>
    </x-slot>

    <div class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success banner --}}
            <div class="glass-dark border border-emerald-500/20 rounded-[2.5rem] p-10 shadow-2xl mb-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-emerald-500/5 pointer-events-none"></div>
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-500/20">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white tracking-tight">Payment Successful!</h3>
                <p class="text-white/40 text-sm mt-2">A confirmation has been recorded for your booking(s) below.</p>
            </div>

            {{-- Booking list --}}
            @if($bookings && $bookings->isNotEmpty())
                <div class="space-y-6 mb-10">
                    @foreach($bookings as $booking)
                        <div class="glass-dark border border-white/5 rounded-[2rem] p-8 flex items-center justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-orange-500/20 flex-shrink-0">🎫</div>
                                <div>
                                    <h4 class="text-lg font-bold text-white leading-tight">{{ $booking->ticketType->event->title }}</h4>
                                    <p class="text-orange-500 text-xs font-black uppercase tracking-widest mt-1">{{ $booking->ticketType->name }} × {{ $booking->quantity }}</p>
                                    <p class="text-white/30 text-[11px] font-mono mt-1">Ref: {{ $booking->reference }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-white font-black text-lg">£{{ number_format($booking->total_price, 2) }}</p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-widest">Confirmed</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('bookings.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-sm hover:bg-white/10 transition-all">
                    View My Bookings
                </a>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-600 text-white font-bold text-sm hover:opacity-90 transition-all shadow-lg shadow-orange-500/20">
                    Explore More Events
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

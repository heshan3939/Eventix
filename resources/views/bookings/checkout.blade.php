<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Secure <span class="text-gradient">Checkout</span>
        </h2>
        <p class="text-white/40 text-sm font-medium mt-2">Complete your transaction to secure your spot</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Order Summary -->
                <div class="lg:col-span-7">
                    <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden h-full">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] pointer-events-none"></div>
                        
                        <h3 class="text-2xl font-bold text-white tracking-tight mb-10 flex items-center gap-3">
                            <span class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center text-sm font-black text-white/40">01</span>
                            Review Selections
                        </h3>

                        <div class="space-y-6">
                            @foreach($bookings as $booking)
                                <div class="flex items-center justify-between p-6 rounded-3xl bg-white/[0.02] border border-white/5 group hover:bg-white/[0.04] transition-all">
                                    <div class="flex items-center gap-6">
                                        <div class="w-16 h-16 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-orange-500/10">
                                            🎫
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-white leading-tight">{{ $booking->ticketType->event->title }}</h4>
                                            <p class="text-orange-500 text-xs font-black uppercase tracking-widest mt-1">{{ $booking->ticketType->name }} × {{ $booking->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white font-black text-lg">£{{ number_format($booking->total_price, 2) }}</p>
                                        <p class="text-white/20 text-[10px] font-bold uppercase tracking-tighter">Incl. Platform Fee</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-12 pt-10 border-t border-white/5">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.3em] mb-1">Total Amount Due</p>
                                    <p class="text-5xl font-black tracking-tighter text-white">£{{ number_format($totalAmount, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white/20 text-xs font-medium">Standard Processing</p>
                                    <p class="text-emerald-400 text-[10px] font-black uppercase tracking-widest mt-1">Instant Confirmation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="lg:col-span-5">
                    <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl sticky top-8">
                        <h3 class="text-2xl font-bold text-white tracking-tight mb-10 flex items-center gap-3">
                            <span class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center text-sm font-black text-white/40">02</span>
                            Payment Details
                        </h3>

                        <form action="{{ route('bookings.pay') }}" method="POST" class="space-y-6">
                            @csrf
                            @foreach($bookings as $booking)
                                <input type="hidden" name="booking_ids[]" value="{{ $booking->id }}">
                            @endforeach

                            <div>
                                <x-label for="card_holder" value="Cardholder Name" />
                                <x-input id="card_holder" name="card_holder" type="text" class="w-full mt-2" required placeholder="Johnathan Doe" />
                            </div>

                            <div class="relative">
                                <x-label for="card_number" value="Card Number" />
                                <div class="relative mt-2">
                                    <x-input id="card_number" name="card_number" type="text" class="w-full pr-12" required placeholder="0000 0000 0000 0000" x-mask="9999 9999 9999 9999" />
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex gap-1 grayscale opacity-50">
                                        <span class="text-xs">💳</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <x-label for="expiry" value="Expiry Date" />
                                    <x-input id="expiry" name="expiry" type="text" class="w-full mt-2" required placeholder="MM/YY" x-mask="99/99" />
                                </div>
                                <div>
                                    <x-label for="cvv" value="CVV" />
                                    <x-input id="cvv" name="cvv" type="text" class="w-full mt-2" required placeholder="•••" x-mask="999" />
                                </div>
                            </div>

                            <div class="pt-6">
                                <x-button class="w-full justify-center py-5 text-lg group overflow-hidden relative">
                                    <span class="relative z-10">Pay £{{ number_format($totalAmount, 2) }}</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </x-button>
                            </div>

                            <p class="text-center text-[10px] text-white/20 font-medium uppercase tracking-[0.2em] pt-4">
                                🔒 Secure 256-bit SSL Encrypted Transaction
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

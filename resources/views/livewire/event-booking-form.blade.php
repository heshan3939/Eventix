<div class="glass-dark border border-white/10 rounded-[2.5rem] p-8 shadow-2xl overflow-hidden relative">
    <!-- Background glow -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/10 blur-[60px] -translate-y-1/2 translate-x-1/2 rounded-full"></div>
    
    <h3 class="text-3xl font-bold tracking-tighter mb-8 bg-clip-text text-transparent bg-gradient-to-r from-white to-white/40">Secure Tickets</h3>
    
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center space-x-3 italic">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        @foreach($event->ticketTypes as $type)
            @php
                $booked = $type->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity');
                $available = max(0, $type->quantity - $booked);
            @endphp
            
            <div class="bg-white/5 border border-white/10 rounded-3xl p-5 transition-all duration-300 {{ $selectedTickets[$type->id] > 0 ? 'border-orange-500/50 bg-orange-500/5' : '' }}">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold text-white">{{ $type->name }}</h4>
                        <p class="text-orange-500 font-black text-sm">Rs. {{ number_format($type->price, 2) }}</p>
                    </div>
                    @if($available > 0)
                        <div class="flex items-center bg-black/40 rounded-xl p-1 border border-white/10">
                            <button type="button" wire:click="decrement({{ $type->id }})" class="w-8 h-8 flex items-center justify-center text-white/40 hover:text-white transition">-</button>
                            <span class="w-8 text-center font-black text-white text-sm">{{ $selectedTickets[$type->id] }}</span>
                            <button type="button" wire:click="increment({{ $type->id }})" class="w-8 h-8 flex items-center justify-center text-white/40 hover:text-white transition">+</button>
                        </div>
                    @else
                        <span class="text-[10px] font-black uppercase tracking-widest text-rose-500 italic">Sold Out</span>
                    @endif
                </div>
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                    <span class="text-white/20">{{ $available }} Remaining</span>
                    @if($selectedTickets[$type->id] > 0)
                        <span class="text-orange-500">Rs. {{ number_format($type->price * $selectedTickets[$type->id], 2) }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 pt-8 border-t border-white/5">
        <div class="flex justify-between items-end mb-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-white/30">Total Investment</p>
                <p class="text-3xl font-black text-white tracking-tighter">Rs. {{ number_format($this->subtotal, 2) }}</p>
            </div>
            <p class="text-xs font-bold text-white/40">{{ $this->count }} Passes Selected</p>
        </div>

        @auth
            @if(auth()->user()->isCustomer())
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    @foreach($selectedTickets as $id => $qty)
                        @if($qty > 0)
                            <input type="hidden" name="tickets[{{ $id }}]" value="{{ $qty }}">
                        @endif
                    @endforeach
                    
                    <button type="submit" @if($this->count == 0) disabled @endif
                            class="w-full bg-gradient-to-r from-orange-500 to-rose-600 text-white font-black uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl shadow-orange-500/20 transition-all duration-300 enabled:hover:scale-[1.02] enabled:active:scale-[0.98] disabled:opacity-50 disabled:grayscale text-xs">
                        Book Selected Passes
                    </button>
                </form>
            @else
                <div class="w-full bg-white/5 border border-white/5 py-6 rounded-2xl text-center px-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-500/60 mb-2 italic">Access Restricted</p>
                    <p class="text-xs text-white/40 font-medium">As an {{ auth()->user()->role }}, you are restricted from purchasing passes.</p>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" 
               class="w-full bg-white/5 border border-white/10 text-white hover:bg-white/10 font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all duration-300 text-xs flex items-center justify-center">
                Sign in to Book Passes
            </a>
        @endauth

        <p class="mt-6 text-[10px] text-center text-white/20 font-black uppercase tracking-[0.2em] px-4">
            Secure Checkout Protected by Eventix Security
        </p>
    </div>
</div>

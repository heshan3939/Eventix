<div class="group relative bg-white/5 border border-white/10 rounded-3xl p-6 transition-all duration-300 hover:bg-white/[0.08] hover:border-orange-500/30">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h4 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-white/60 mb-1">{{ $type->name }}</h4>
            @if($available > 0)
                <span class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-green-400">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse mr-2"></span>
                    {{ $available }} available
                </span>
            @else
                <span class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-red-400">
                    Sold Out
                </span>
            @endif
        </div>
        <div class="text-right">
            <span class="text-2xl font-black tracking-tighter">£{{ number_format($type->price, 0) }}</span>
            <span class="block text-[10px] font-black text-white/20 uppercase tracking-widest">per pass</span>
        </div>
    </div>
    
    @if($available > 0)
        @auth
            @if(auth()->user()->isCustomer())
                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="ticket_type_id" value="{{ $type->id }}">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-black/20 rounded-2xl p-3 border border-white/5">
                            <label class="block text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">Quantity</label>
                            <input type="number" name="quantity" wire:model.live="quantity" min="1" max="{{ min($available, $type->max_per_booking) }}" 
                                   class="w-full bg-transparent border-none p-0 text-lg font-bold text-white focus:ring-0">
                        </div>
                        <div class="bg-orange-500/10 rounded-2xl p-3 border border-orange-500/20">
                            <label class="block text-[10px] font-black text-orange-400/60 uppercase tracking-widest mb-1">Total</label>
                            <span class="text-lg font-black text-orange-400">£{{ number_format($subtotal, 0) }}</span>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-orange-500 to-rose-600 text-white font-black uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl shadow-orange-500/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] text-xs">
                        Book Passes Now
                    </button>
                </form>
            @else
                <div class="w-full bg-white/5 border border-white/5 py-6 rounded-2xl text-center px-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-500/60 mb-2 italic">Access Restricted</p>
                    <p class="text-xs text-white/40 font-medium">As an {{ auth()->user()->role }}, you are restricted from purchasing passes.</p>
                </div>
            @endif
        @else
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 opacity-40">
                    <div class="bg-black/20 rounded-2xl p-3 border border-white/5">
                        <label class="block text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">Quantity</label>
                        <span class="text-lg font-bold text-white">1</span>
                    </div>
                    <div class="bg-orange-500/10 rounded-2xl p-3 border border-orange-500/20">
                        <label class="block text-[10px] font-black text-orange-400/60 uppercase tracking-widest mb-1">Total</label>
                        <span class="text-lg font-black text-orange-400">£{{ number_format($type->price, 0) }}</span>
                    </div>
                </div>
                
                <a href="{{ route('login') }}" 
                   class="w-full bg-white/5 border border-white/10 text-white hover:bg-white/10 font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all duration-300 text-xs flex items-center justify-center">
                    Sign in to Book Passes
                </a>
            </div>
        @endauth
    @else
        <div class="w-full bg-white/5 border border-white/5 py-4 rounded-2xl text-center opacity-40 grayscale">
            <span class="font-black uppercase tracking-[0.2em] text-xs">No Availability</span>
        </div>
    @endif
</div>

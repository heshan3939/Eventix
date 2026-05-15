<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">Experience <span class="text-gradient">Registry</span></h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-8 flex items-center space-x-3 backdrop-blur-xl">
                    <span class="text-xl">✅</span>
                    <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl mb-8 flex items-center space-x-3 backdrop-blur-xl">
                    <span class="text-xl">⚠️</span>
                    <p class="font-bold text-sm tracking-tight">{{ session('error') }}</p>
                </div>
            @endif

            <div class="space-y-8">
                @forelse($bookings as $booking)
                    <div class="group relative glass-dark border border-white/5 rounded-[2.5rem] p-10 flex flex-col md:flex-row justify-between items-center transition-all duration-500 hover:border-orange-500/20 overflow-hidden">
                        <!-- Background glow -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none"></div>

                        <div class="flex flex-col md:flex-row gap-8 items-center w-full md:w-auto">
                            <!-- Ticket Visual -->
                            <div class="relative w-24 h-24 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-3xl flex flex-col items-center justify-center p-2 shadow-2xl shadow-orange-500/20 shrink-0">
                                <span class="text-[10px] font-black uppercase text-white/60 mb-0.5 leading-none">{{ $booking->ticketType->event->starts_at->format('M') }}</span>
                                <span class="text-3xl font-black text-white leading-none tracking-tighter">{{ $booking->ticketType->event->starts_at->format('d') }}</span>
                                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-4 bg-slate-950 rounded-full"></div>
                                <div class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-4 bg-slate-950 rounded-full"></div>
                            </div>

                            <div class="text-center md:text-left">
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-3">
                                    <h3 class="text-2xl font-black tracking-tight text-white group-hover:text-orange-400 transition-colors uppercase leading-none">{{ $booking->ticketType->event->title }}</h3>
                                    
                                    @php
                                        $statusClasses = [
                                            'confirmed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'pending'   => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
                                            'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        ];
                                        $class = $statusClasses[$booking->status] ?? 'bg-white/5 text-white/40 border-white/10';
                                    @endphp
                                    <span class="px-3 py-1 text-[8px] font-black uppercase tracking-[0.2em] rounded-md border {{ $class }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                <p class="text-white/40 font-bold text-sm mb-4">
                                    📅 {{ $booking->ticketType->event->starts_at->format('D d M Y, g:ia') }}
                                </p>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <span class="bg-white/5 px-4 py-2 rounded-xl text-xs font-bold text-white/60 border border-white/5">
                                        🎟️ {{ $booking->ticketType->name }} &times; {{ $booking->quantity }}
                                    </span>
                                    <span class="text-xl font-black text-white tracking-tighter">
                                        £{{ number_format($booking->total_price, 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 md:mt-0 flex items-center space-x-6">
                            <div class="text-right hidden lg:block">
                                <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em] mb-1">Access Reference</p>
                                <p class="text-xs font-mono font-bold text-white/50 bg-white/5 px-3 py-1.5 rounded-lg border border-white/5">{{ $booking->reference }}</p>
                            </div>

                            @if($booking->status == 'pending')
                                <a href="{{ route('bookings.checkout', ['ids' => [$booking->id]]) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-lg shadow-orange-500/20">Complete Payment</a>
                            @elseif($booking->status == 'confirmed')
                                @php
                                    $canCancel = now()->lt($booking->ticketType->event->starts_at->subHours(48));
                                @endphp
                                @if($canCancel)
                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Revoke this access? This action initiates a standard refund protocol.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">Cancel Pass</button>
                                    </form>
                                @else
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest border border-white/5 px-4 py-2 rounded-xl">Non-Refundable</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="glass-dark border border-white/5 rounded-[3rem] py-32 text-center">
                        <div class="text-6xl mb-8 grayscale opacity-20 select-none">🎭</div>
                        <p class="text-white/30 font-bold uppercase tracking-[0.3em] text-sm mb-8 italic">Your ticket vault is currently empty.</p>
                        <a href="{{ route('events.index') }}" class="btn-premium bg-gradient-to-r from-orange-500 to-rose-600 text-white text-sm">Discover Experiences</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $bookings->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>

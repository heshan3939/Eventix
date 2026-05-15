<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-8 flex items-center space-x-3 backdrop-blur-xl">
                    <span class="text-xl">✨</span>
                    <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            @if(auth()->user()->isOrganiser())
                <!-- Organiser Dashboard -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
                    <div>
                        <h3 class="text-2xl font-bold tracking-tight text-white/90">My Hosted Experiences</h3>
                        <p class="text-white/40 text-sm">Manage your events, track bookings and analyze performance.</p>
                    </div>
                    <a href="{{ route('events.create') }}" class="btn-premium bg-gradient-to-r from-orange-500 to-rose-600 text-white text-sm">Create New Experience</a>
                </div>

                <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Experience</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Status</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Bookings</th>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Revenue</th>
                                    <th class="px-8 py-5"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($events as $event)
                                    <tr class="group hover:bg-white/[0.02] transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-white group-hover:text-orange-400 transition-colors">{{ $event->title }}</div>
                                            <div class="text-xs text-white/30 font-medium mt-1">{{ $event->starts_at->format('d M Y, g:ia') }}</div>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $status = $event->status;
                                                if ($event->is_expired) {
                                                    $status = 'expired';
                                                }
                                                $statusClasses = [
                                                    'published' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                    'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                    'expired' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                ];
                                                $class = $statusClasses[$status] ?? 'bg-white/5 text-white/40 border-white/10';
                                            @endphp
                                            <div class="flex flex-col">
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border w-fit {{ $class }}">
                                                    {{ $status }}
                                                </span>
                                                @if($event->status == 'rejected' && $event->rejection_reason)
                                                    <span class="text-[9px] text-rose-400/60 mt-2 max-w-[150px] leading-tight font-medium italic">"{{ $event->rejection_reason }}"</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="text-white font-black text-lg tracking-tighter">{{ $event->bookings_count }}</div>
                                            <a href="{{ route('events.bookings', $event) }}" class="text-[10px] text-white/30 font-black uppercase tracking-widest hover:text-orange-500 transition-colors">Audit Passes →</a>
                                        </td>
                                        <td class="px-8 py-6 text-orange-400 font-black text-lg tracking-tighter">
                                            £{{ number_format($event->total_revenue, 0) }}
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex gap-4 justify-end items-center">
                                                <a href="{{ route('events.show', $event) }}" class="text-white/40 hover:text-white transition font-bold text-xs uppercase tracking-widest">View</a>
                                                <a href="{{ route('events.edit', $event) }}" class="text-white/40 hover:text-orange-500 transition font-bold text-xs uppercase tracking-widest">Edit</a>
                                                <a href="{{ route('events.tickets', $event) }}" class="text-orange-500 hover:text-orange-400 transition font-bold text-xs uppercase tracking-widest">Tickets</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-20 text-center">
                                            <div class="text-4xl mb-4 grayscale opacity-30">🎭</div>
                                            <p class="text-white/30 font-bold uppercase tracking-widest text-sm">No experiences hosted yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <!-- Customer Dashboard -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
                    <div>
                        <h3 class="text-2xl font-bold tracking-tight text-white/90">My Secure Passes</h3>
                        <p class="text-white/40 text-sm">Your upcoming experiences and booking history.</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-orange-500 hover:text-orange-400 font-bold text-sm tracking-tight transition">Explore More Experiences →</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($bookings ?? [] as $booking)
                        <div class="group relative glass-dark border border-white/5 rounded-[2.5rem] p-8 flex items-center gap-8 transition-all duration-500 hover:border-orange-500/30 hover:-translate-y-1 overflow-hidden">
                            <!-- Background accent -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-600/5 blur-[40px] translate-x-12 -translate-y-12 rounded-full"></div>
                            
                            <div class="bg-gradient-to-b from-orange-500 to-rose-600 font-black p-4 rounded-3xl text-center min-w-[100px] shadow-2xl shadow-orange-500/20">
                                <div class="text-xs uppercase tracking-[0.2em] text-white/70">{{ $booking->ticketType->event->starts_at->format('M') }}</div>
                                <div class="text-4xl text-white tracking-tighter">{{ $booking->ticketType->event->starts_at->format('d') }}</div>
                            </div>
                            
                            <div class="flex-grow">
                                <span class="bg-white/5 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest text-white/40 mb-2 inline-block">Confirmed Pass</span>
                                <h4 class="text-xl font-bold text-white group-hover:text-orange-400 transition-colors line-clamp-1">{{ $booking->ticketType->event->title }}</h4>
                                <p class="text-white/40 text-sm mt-1 mb-4">🎟️ {{ $booking->quantity }}x {{ $booking->ticketType->name }}</p>
                                <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition-colors">
                                    <span>Manage Booking</span>
                                    <span class="ml-2 group-hover:translate-x-1 transition-transform">→</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 glass-dark border border-white/5 rounded-[3rem] py-32 text-center">
                            <div class="text-6xl mb-8 grayscale opacity-20 select-none">🎫</div>
                            <p class="text-white/30 font-bold uppercase tracking-[0.3em] text-sm mb-8">No upcoming experiences.</p>
                            <a href="{{ route('events.index') }}" class="btn-premium bg-gradient-to-r from-orange-500 to-rose-600 text-white text-sm">Discover Experiences</a>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

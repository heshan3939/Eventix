<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="w-12 h-12 glass-dark border border-white/10 rounded-2xl flex items-center justify-center text-white/50 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl md:text-4xl font-black tracking-tighter text-white">Event <span class="text-gradient">Registrations</span></h2>
                <p class="text-white/40 text-sm font-bold uppercase tracking-widest mt-1">{{ $event->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="glass-dark border border-white/5 p-8 rounded-[2rem]">
                    <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Total Attendees</p>
                    <p class="text-4xl font-black tracking-tighter text-white">{{ $bookings->total() }}</p>
                </div>
                <div class="glass-dark border border-white/5 p-8 rounded-[2rem]">
                    <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Net Revenue</p>
                    <p class="text-4xl font-black tracking-tighter text-orange-400">Rs. {{ number_format($event->bookings->sum('total_price'), 0) }}</p>
                </div>
                <div class="glass-dark border border-white/5 p-8 rounded-[2rem]">
                    <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Event Capacity</p>
                    <p class="text-4xl font-black tracking-tighter text-white">{{ $event->total_capacity }}</p>
                </div>
            </div>

            <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/[0.02]">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Attendee</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Ticket Tier</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Qty</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Amount</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Status</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($bookings as $booking)
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-white">{{ $booking->user->name }}</div>
                                        <div class="text-[10px] text-white/30 font-medium tracking-widest mt-0.5">{{ $booking->user->email }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-widest text-white/60">
                                            {{ $booking->ticketType->name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-white font-bold">{{ $booking->quantity }}</td>
                                    <td class="px-8 py-6 text-orange-400 font-black text-lg tracking-tighter">Rs. {{ number_format($booking->total_price, 0) }}</td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusClasses = [
                                                'confirmed' => 'text-emerald-400',
                                                'cancelled' => 'text-rose-400',
                                            ];
                                            $class = $statusClasses[$booking->status] ?? 'text-white/40';
                                        @endphp
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $class }} flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right font-mono text-[10px] text-white/20 group-hover:text-white/50 transition-colors">
                                        {{ $booking->reference }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <p class="text-white/20 font-black uppercase tracking-widest text-sm">No registrations documented yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

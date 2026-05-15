<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">Platform <span class="text-gradient">Control</span></h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-8 flex items-center space-x-3 backdrop-blur-xl">
                    <span class="text-xl">⚡</span>
                    <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Impact Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="glass-dark border border-white/5 p-8 rounded-[2rem] flex items-center justify-between group hover:border-orange-500/30 transition-all duration-500">
                    <div>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Pending Approvals</p>
                        <p class="text-4xl font-black tracking-tighter text-white">{{ $pendingEvents->count() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-orange-600/10 border border-orange-500/20 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">⏳</div>
                </div>
                
                <div class="glass-dark border border-white/5 p-8 rounded-[2rem] flex items-center justify-between group hover:border-green-500/30 transition-all duration-500">
                    <div>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Platform Revenue</p>
                        <p class="text-4xl font-black tracking-tighter text-green-400">£{{ number_format($totalPlatformRevenue, 0) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-600/10 border border-green-500/20 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">💰</div>
                </div>

                <div class="glass-dark border border-white/5 p-8 rounded-[2rem] flex items-center justify-between group hover:border-blue-500/30 transition-all duration-500">
                    <div>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Total Community</p>
                        <p class="text-4xl font-black tracking-tighter text-white">{{ $organisersCount + $customersCount }}</p>
                        <p class="text-[10px] text-white/20 font-bold mt-1 uppercase tracking-widest">{{ $organisersCount }} Orgs • {{ $customersCount }} Custs</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-600/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">👥</div>
                </div>
            </div>

            <!-- Pending Queue -->
            <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                    <h3 class="text-xl font-bold tracking-tight">Review Queue</h3>
                    <span class="bg-white/5 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white/40">Manual Verification Required</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Experience</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Host</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Lifecycle</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Decision</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($pendingEvents as $event)
                                <tr class="group hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-6">
                                        <a href="{{ route('events.show', $event) }}" target="_blank" class="block">
                                            <div class="font-bold text-white group-hover:text-orange-400 transition-colors">{{ $event->title }}</div>
                                            <div class="text-[10px] text-orange-500/60 font-black uppercase tracking-widest mt-1">{{ $event->category }}</div>
                                        </a>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center text-xs font-bold text-white/40">
                                                {{ substr($event->organiser->name, 0, 1) }}
                                            </div>
                                            <div class="text-sm font-bold text-white/70">{{ $event->organiser->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-white/60 tracking-tight">{{ $event->starts_at->format('d M y') }}</div>
                                        <div class="text-[10px] text-white/20 font-medium tracking-widest">{{ $event->starts_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end gap-3" x-data="{ confirmingReject: false }">
                                            <a href="{{ route('events.show', $event) }}" target="_blank" class="bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white border border-blue-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">Preview</a>

                                            <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-white border border-emerald-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">Approve</button>
                                            </form>
                                            
                                            <div class="relative">
                                                <button @click="confirmingReject = true" type="button" class="bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">Reject</button>
                                                
                                                <div x-show="confirmingReject" @click.away="confirmingReject = false" 
                                                     class="absolute right-0 mt-4 w-72 glass-dark border border-white/10 rounded-3xl shadow-2xl z-50 p-6 text-left" 
                                                     style="display: none;"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 translate-y-2"
                                                     x-transition:enter-end="opacity-100 translate-y-0">
                                                    <form action="{{ route('admin.events.reject', $event) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <h4 class="text-sm font-bold text-white mb-4">Rejection Protocol</h4>
                                                        <label class="block text-[10px] font-black text-white/30 uppercase tracking-widest mb-2 ms-1">Reason for Rejection</label>
                                                        <textarea name="rejection_reason" required 
                                                                  class="block w-full bg-black/20 border border-white/10 rounded-xl text-white text-xs p-3 mb-4 focus:ring-rose-500/30 focus:border-rose-500/50" 
                                                                  rows="3" placeholder="Explain why..."></textarea>
                                                        <div class="flex gap-2">
                                                            <button @click="confirmingReject = false" type="button" class="flex-1 bg-white/5 text-white/40 text-[10px] font-black uppercase py-3 rounded-xl border border-white/5">Cancel</button>
                                                            <button type="submit" class="flex-1 bg-rose-500 text-white text-[10px] font-black uppercase py-3 rounded-xl shadow-lg shadow-rose-500/20">Confirm</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="text-5xl mb-6 grayscale opacity-20">✅</div>
                                        <p class="text-white/30 font-bold uppercase tracking-[0.3em] text-sm italic">Clear Skies • No Pending Tasks</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Processing History -->
            <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl mt-12">
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                    <h3 class="text-xl font-bold tracking-tight">Processing History</h3>
                    <span class="bg-white/5 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white/40">Past Decisions & Performance</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Experience</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Status</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Total Sales</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Platform Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($processedEvents as $event)
                                <tr class="group hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-6">
                                        <a href="{{ route('events.show', $event) }}" target="_blank" class="block">
                                            <div class="font-bold text-white group-hover:text-orange-400 transition-colors">{{ $event->title }}</div>
                                            <div class="text-[10px] text-white/20 font-black uppercase tracking-widest mt-1">{{ $event->organiser->name }}</div>
                                        </a>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        @if($event->is_expired)
                                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Expired</span>
                                        @elseif($event->status === 'published')
                                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Approved</span>
                                        @else
                                            <div class="flex flex-col items-center">
                                                <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Rejected</span>
                                                @if($event->rejection_reason)
                                                    <p class="text-[8px] text-white/20 mt-1 max-w-[150px] truncate" title="{{ $event->rejection_reason }}">{{ $event->rejection_reason }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-center text-sm font-bold text-white/60">
                                        £{{ number_format($event->total_revenue, 2) }}
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="text-lg font-black text-white">£{{ number_format($event->platform_revenue, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <p class="text-white/30 font-bold uppercase tracking-[0.3em] text-sm italic">No processed events found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/5">
                    {{ $processedEvents->appends(['orgs' => $organisers->currentPage(), 'custs' => $customers->currentPage()])->links() }}
                </div>
            </div>
            
            <!-- Summary Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-12">
                <!-- Organizers Summary -->
                <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl h-fit">
                    <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                        <h3 class="text-xl font-bold tracking-tight">Organizers Insight</h3>
                        <span class="bg-orange-500/10 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-orange-400">Merchant Performance</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-white/[0.01]">
                                <tr>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Host</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Events</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Tickets</th>
                                    <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($organisers as $org)
                                    <tr class="group hover:bg-white/[0.02] transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-white">{{ $org->name }}</div>
                                            <div class="text-[10px] text-white/20 font-medium tracking-widest">{{ $org->email }}</div>
                                        </td>
                                        <td class="px-8 py-6 text-center text-white/60 font-bold">{{ $org->events_count }}</td>
                                        <td class="px-8 py-6 text-center text-white/60 font-bold">{{ $org->total_tickets }}</td>
                                        <td class="px-8 py-6 text-right text-orange-400 font-black">£{{ number_format($org->total_revenue, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-white/5">
                        {{ $organisers->appends(['custs' => $customers->currentPage()])->links() }}
                    </div>
                </div>

                <!-- Customers Summary -->
                <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl h-fit">
                    <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                        <h3 class="text-xl font-bold tracking-tight">Customers Insight</h3>
                        <span class="bg-blue-500/10 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-blue-400">User Activity</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-white/[0.01]">
                                <tr>
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">User</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Bookings</th>
                                    <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Spent</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($customers as $customer)
                                    <tr class="group hover:bg-white/[0.02] transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-white">{{ $customer->name }}</div>
                                            <div class="text-[10px] text-white/20 font-medium tracking-widest">{{ $customer->email }}</div>
                                        </td>
                                        <td class="px-8 py-6 text-center text-white/60 font-bold">{{ $customer->bookings_count }}</td>
                                        <td class="px-8 py-6 text-right text-emerald-400 font-black">£{{ number_format($customer->bookings_sum_total_price ?? 0, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-white/5">
                        {{ $customers->appends(['orgs' => $organisers->currentPage()])->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">Financial <span class="text-gradient">Intelligence</span></h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Strategic Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="glass-dark border border-white/5 p-10 rounded-[2.5rem] flex flex-col items-center justify-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-rose-600 opacity-20 group-hover:opacity-100 transition-opacity"></div>
                    <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-4">Total Gross Ticket Sales</p>
                    <p class="text-6xl font-black tracking-tighter text-white">£{{ number_format($totalSales, 0) }}</p>
                    <div class="mt-4 flex items-center space-x-2 text-emerald-400 text-xs font-bold">
                        <span>↑ 12% from last month</span>
                    </div>
                </div>
                
                <div class="glass-dark border border-white/5 p-10 rounded-[2.5rem] flex flex-col items-center justify-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-600 opacity-20 group-hover:opacity-100 transition-opacity"></div>
                    <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-4">Net Platform Revenue (5%)</p>
                    <p class="text-6xl font-black tracking-tighter text-green-400">£{{ number_format($platformRevenue, 0) }}</p>
                    <div class="mt-4 flex items-center space-x-2 text-white/20 text-xs font-bold uppercase tracking-widest">
                        <span>Global Fee Structure</span>
                    </div>
                </div>
            </div>

            <!-- Ledger -->
            <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                    <h3 class="text-xl font-bold tracking-tight">transactional Ledger</h3>
                    <span class="bg-white/5 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white/40">Real-time Financial Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Timestamp</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Experience / Client</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Pass Config</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Gross</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Platform Fee</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($transactions as $txn)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6 whitespace-nowrap text-[10px] font-black text-white/30 uppercase tracking-widest">
                                    {{ $txn->created_at->format('d M y H:i') }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-white group-hover:text-orange-400 transition-colors">{{ $txn->booking->ticketType->event->title ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-white/30 font-medium uppercase tracking-widest mt-0.5">{{ $txn->booking->user->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-xs font-bold text-white/50 tracking-tight">
                                    {{ $txn->booking->quantity ?? 0 }}x {{ $txn->booking->ticketType->name ?? 'N/A' }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-lg font-black text-white text-right tracking-tighter">
                                    £{{ number_format($txn->amount, 2) }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-lg font-black text-orange-400 text-right tracking-tighter">
                                    £{{ number_format($txn->platform_fee, 2) }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $txn->status == 'paid' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                        {{ $txn->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

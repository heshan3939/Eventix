<div class="space-y-12">
    <!-- Filters -->
    <div class="glass-dark rounded-3xl p-6 md:p-4 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative w-full">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">🔍</span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, city or venue..." 
                   class="w-full bg-white/5 border-none rounded-2xl pl-12 pr-4 py-4 text-white placeholder:text-white/30 focus:ring-2 focus:ring-orange-500/50 transition">
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-2 gap-4 w-full md:w-auto">
            <select wire:model.live="category" 
                    class="bg-white/5 border-none rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-orange-500/50 transition cursor-pointer appearance-none">
                <option value="" class="bg-slate-900">All Categories</option>
                <option value="music" class="bg-slate-900">Music</option>
                <option value="conference" class="bg-slate-900">Conference</option>
                <option value="culture" class="bg-slate-900">Culture</option>
                <option value="sports" class="bg-slate-900">Sports</option>
                <option value="education" class="bg-slate-900">Education</option>
            </select>
            
            <input type="text" wire:model.live.debounce.300ms="city" placeholder="City..." 
                   class="bg-white/5 border-none rounded-2xl px-6 py-4 text-white placeholder:text-white/30 focus:ring-2 focus:ring-orange-500/50 transition">
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="w-full text-center py-20">
        <div class="inline-block w-8 h-8 border-4 border-orange-500/30 border-t-orange-500 rounded-full animate-spin mb-4"></div>
        <p class="text-white/40 font-bold uppercase tracking-widest text-xs">Curating events for you...</p>
    </div>

    <!-- Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" wire:loading.remove>
        @forelse($events as $event)
            <div class="group relative bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden hover:bg-white/[0.07] transition-all duration-500 hover:-translate-y-2">
                <!-- Image Placeholder/Accent -->
                <div class="aspect-[16/10] bg-slate-800 relative overflow-hidden">
                    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent z-10"></div>
                    <div class="absolute top-4 left-4 z-20">
                        <span class="bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg shadow-orange-500/20">
                            {{ $event->category }}
                        </span>
                    </div>
                    @if($event->is_sold_out)
                        <div class="absolute inset-0 flex items-center justify-center z-20 backdrop-blur-[2px] bg-black/40">
                            <span class="border-2 border-red-500 text-red-500 font-black uppercase tracking-[0.2em] px-6 py-2 rounded-xl rotate-[-5deg]">Sold Out</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-2xl font-bold tracking-tight text-white group-hover:text-orange-400 transition-colors line-clamp-1">
                            {{ $event->title }}
                        </h3>
                    </div>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center text-white/50 text-sm">
                            <span class="w-5">📍</span>
                            <span class="font-medium truncate">{{ $event->venue }}, {{ $event->city }}</span>
                        </div>
                        <div class="flex items-center text-white/50 text-sm">
                            <span class="w-5">📅</span>
                            <span class="font-medium">{{ $event->starts_at->format('D d M Y • g:ia') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-white/5">
                        <div class="flex flex-col">
                            @if(!$event->is_sold_out)
                                <span class="text-[10px] uppercase font-black tracking-widest text-white/30">Availability</span>
                                <span class="text-lg font-bold text-green-400 tracking-tighter">🟢 {{ number_format($event->available_seats) }} seats left</span>
                            @else
                                <span class="text-[10px] uppercase font-black tracking-widest text-white/30">Status</span>
                                <span class="text-lg font-bold text-red-500 tracking-tighter">🔴 Sold Out</span>
                            @endif
                        </div>
                        <a href="{{ route('events.show', $event) }}" 
                           class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 group-hover:bg-orange-500 group-hover:border-orange-500 transition-all duration-300">
                            <span class="text-xl group-hover:scale-125 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center">
                <div class="text-6xl mb-6">🏜️</div>
                <h3 class="text-2xl font-bold text-white mb-2">No matching events found</h3>
                <p class="text-white/40">Try adjusting your filters or search terms.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $events->links() }}
    </div>
</div>

<x-guest-layout>
    <div class="relative min-h-screen bg-slate-950 text-white selection:bg-orange-500/30">
        @if(auth()->check() && auth()->user()->isAdmin() && $event->status === 'pending')
            <div class="fixed top-0 left-0 w-full z-[100] bg-orange-600 border-b border-orange-500 shadow-2xl py-3 px-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-white font-black uppercase tracking-widest text-[10px] bg-black/20 px-3 py-1 rounded-full">Admin Preview Mode</span>
                    <p class="text-white text-xs font-bold">This event is currently <span class="uppercase">Pending Approval</span>.</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-white text-orange-600 px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 shadow-xl shadow-black/10">Quick Approve</button>
                    </form>
                    <a href="{{ route('admin.index') }}" class="text-white/80 hover:text-white text-[10px] font-black uppercase tracking-widest px-4 transition-colors">Return to Dashboard</a>
                </div>
            </div>
            <div class="h-16"></div>
        @endif
        <!-- Background Elements -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-orange-600/5 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-600/5 blur-[120px] rounded-full"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Breadcrumbs / Back Button -->
            <nav class="flex items-center space-x-4 mb-8">
                <a href="{{ route('events.index') }}" class="group flex items-center space-x-2 bg-white/5 border border-white/10 px-4 py-2 rounded-xl text-sm font-bold text-white/50 hover:text-white hover:bg-white/10 transition-all duration-300">
                    <span class="group-hover:-translate-x-1 transition-transform">←</span>
                    <span>Back to Discovery</span>
                </a>
                <div class="h-4 w-px bg-white/10"></div>
                <span class="text-sm font-medium text-white/30 uppercase tracking-widest">{{ $event->category }}</span>
            </nav>

            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Left Column: Content -->
                <div class="lg:col-span-8 space-y-12">
                    <!-- Title & Identity -->
                    <div>
                        <div class="inline-flex items-center space-x-2 {{ $event->status === 'published' ? 'bg-orange-500/10 border-orange-500/20 text-orange-400' : 'bg-blue-500/10 border-blue-500/20 text-blue-400' }} border px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest mb-6">
                            <span class="w-1.5 h-1.5 {{ $event->status === 'published' ? 'bg-orange-500' : 'bg-blue-500' }} rounded-full {{ $event->status === 'published' ? 'animate-pulse' : '' }}"></span>
                            <span>{{ $event->status === 'published' ? 'Live Experience' : 'Internal Preview' }}</span>
                        </div>
                        <h1 class="text-5xl md:text-7xl font-black tracking-tighter leading-none mb-8">
                            {{ $event->title }}
                        </h1>
                        
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="glass-dark p-6 rounded-3xl border border-white/5">
                                <span class="text-white/30 text-[10px] font-black uppercase tracking-widest block mb-2">Location</span>
                                <p class="text-lg font-bold">📍 {{ $event->venue }}</p>
                                <p class="text-white/50 text-sm">{{ $event->city }}</p>
                            </div>
                            <div class="glass-dark p-6 rounded-3xl border border-white/5">
                                <span class="text-white/30 text-[10px] font-black uppercase tracking-widest block mb-2">Date & Time</span>
                                <p class="text-lg font-bold">📅 {{ $event->starts_at->format('D, d M') }}</p>
                                <p class="text-white/50 text-sm">{{ $event->starts_at->format('g:ia') }} Onwards</p>
                            </div>
                            <div id="weather-box" class="glass-dark p-6 rounded-3xl border border-white/5 relative overflow-hidden group">
                                <div class="relative z-10">
                                    <span class="text-white/30 text-[10px] font-black uppercase tracking-widest block mb-2">Weather</span>
                                    <div id="weather-loading" class="flex items-center space-x-2 text-white/40">
                                        <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                                        <span class="text-xs font-bold uppercase tracking-widest">Checking...</span>
                                    </div>
                                    <div id="weather-content" class="hidden">
                                        <p class="text-lg font-bold flex items-center gap-2">
                                            <span id="weather-emoji">⛅</span>
                                            <span id="weather-temp">--°C</span>
                                        </p>
                                        <p id="weather-desc" class="text-white/50 text-xs font-medium uppercase tracking-wider">Loading...</p>
                                    </div>
                                </div>
                                <!-- Advice Tooltip -->
                                <div id="weather-advice" class="absolute inset-0 bg-orange-500/90 p-4 flex items-center justify-center text-center text-[10px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                                    Loading advice...
                                </div>
                            </div>
                            <div class="glass-dark p-6 rounded-3xl border border-white/5">
                                <span class="text-white/30 text-[10px] font-black uppercase tracking-widest block mb-2">Organiser</span>
                                <p class="text-lg font-bold">👤 {{ $event->organiser->name }}</p>
                                <p class="text-white/50 text-sm">Verified Host</p>
                            </div>
                        </div>
                    </div>

                    <!-- About -->
                    <div class="prose prose-invert max-w-none">
                        <h3 class="text-2xl font-bold tracking-tight mb-6 flex items-center space-x-3">
                            <span>About this Event</span>
                            <div class="h-px flex-grow bg-white/5"></div>
                        </h3>
                        <p class="text-white/60 text-lg leading-relaxed whitespace-pre-line">
                            {{ $event->description }}
                        </p>
                    </div>

                    <!-- Host Info Section (Placeholder for aesthetic) -->
                    <div class="bg-gradient-to-r from-slate-900 to-slate-900/40 border border-white/5 rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center gap-8">
                        <div class="w-24 h-24 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-3xl flex items-center justify-center text-4xl shadow-2xl shadow-orange-500/20">
                            {{ substr($event->organiser->name, 0, 1) }}
                        </div>
                        <div class="flex-grow text-center md:text-left">
                            <h4 class="text-2xl font-bold mb-2">{{ $event->organiser->name }}</h4>
                            <p class="text-white/40 text-sm mb-4">Official Eventix Organiser • Joined {{ $event->organiser->created_at->format('M Y') }}</p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                                <span class="bg-white/5 px-4 py-1.5 rounded-full text-xs font-bold text-white/60">Verified Identity</span>
                                <span class="bg-white/5 px-4 py-1.5 rounded-full text-xs font-bold text-white/60">100% Booking Rate</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Booking Sidebar -->
                <div class="lg:col-span-4 overflow-visible">
                    <div class="sticky top-12 space-y-8">
                            <livewire:event-booking-form :event="$event" />
                        </div>

                        <!-- Secondary Info -->
                        <div class="bg-white/5 border border-white/5 rounded-[2rem] p-8 space-y-4">
                            <h4 class="font-bold text-white/60 text-sm uppercase tracking-widest">Platform Guarantee</h4>
                            <ul class="text-xs space-y-3 text-white/40">
                                <li class="flex items-center space-x-3">
                                    <span class="text-green-500">✓</span>
                                    <span>Instant Confirmation</span>
                                </li>
                                <li class="flex items-center space-x-3">
                                    <span class="text-green-500">✓</span>
                                    <span>100% Authentic Tickets</span>
                                </li>
                                <li class="flex items-center space-x-3">
                                    <span class="text-green-500">✓</span>
                                    <span>Verified Hosts Only</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $event->latitude ?? 'null' }};
            const lon = {{ $event->longitude ?? 'null' }};
            const date = "{{ $event->starts_at->format('Y-m-d') }}";
            const city = "{{ $event->city }}";

            if (!lat || !lon) {
                document.getElementById('weather-loading').innerHTML = '<span class="text-[10px] text-white/20">Data unavailable</span>';
                return;
            }

            const url = `/api/v1/external/weather?lat=${lat}&lon=${lon}&date=${date}&city=${encodeURIComponent(city)}`;

            fetch(url)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        document.getElementById('weather-loading').classList.add('hidden');
                        document.getElementById('weather-content').classList.remove('hidden');
                        
                        document.getElementById('weather-emoji').textContent = res.data.emoji;
                        document.getElementById('weather-temp').textContent = `${res.data.temperature_max}°C`;
                        document.getElementById('weather-desc').textContent = res.data.condition;
                        document.getElementById('weather-advice').textContent = res.data.advice;
                    } else {
                        throw new Error(res.message);
                    }
                })
                .catch(error => {
                    console.error('Weather Error:', error);
                    document.getElementById('weather-loading').innerHTML = '<span class="text-[10px] text-rose-500/50 uppercase">Offline</span>';
                });
        });
    </script>
</x-guest-layout>

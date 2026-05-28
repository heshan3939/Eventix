<x-guest-layout>
    <div class="relative min-h-screen bg-mesh overflow-hidden text-white">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-orange-600/20 blur-[120px] rounded-full animate-float"></div>
            <div class="absolute bottom-[10%] right-[-5%] w-[30%] h-[30%] bg-purple-600/10 blur-[100px] rounded-full" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Navbar -->
            <nav x-data="{ open: false }" class="py-8 relative">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <span class="font-black text-xl">E</span>
                        </div>
                        <span class="text-2xl font-black tracking-tighter uppercase">Eventix</span>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-white/70">
                        @if(!auth()->check() || auth()->user()->isCustomer())
                            <a href="{{ route('events.index') }}" class="hover:text-white transition">Explore</a>
                        @endif
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-white/10 hover:bg-white/20 px-6 py-2 rounded-full border border-white/10 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-white transition">Sign in</a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-orange-500 to-rose-600 px-6 py-2 rounded-full font-bold shadow-lg shadow-orange-500/20 hover:scale-105 transition text-white">Join Free</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open" class="text-white/70 hover:text-white focus:outline-none p-2 bg-white/5 rounded-xl border border-white/10">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu Dropdown -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 -translate-y-4" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="absolute top-24 left-0 w-full glass-dark border border-white/10 rounded-3xl p-6 z-50 flex flex-col space-y-4 md:hidden shadow-2xl" style="display: none;">
                    @if(!auth()->check() || auth()->user()->isCustomer())
                        <a href="{{ route('events.index') }}" class="text-white/70 hover:text-white font-medium px-4 py-2 bg-white/5 rounded-xl transition text-center">Explore</a>
                    @endif
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white font-medium bg-orange-500/20 hover:bg-orange-500/30 text-orange-400 px-4 py-2 rounded-xl border border-orange-500/20 transition text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white/70 hover:text-white font-medium px-4 py-2 bg-white/5 rounded-xl transition text-center">Sign in</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-orange-500 to-rose-600 px-4 py-3 rounded-xl font-bold shadow-lg shadow-orange-500/20 text-white text-center">Join Free</a>
                    @endauth
                </div>
            </nav>

            <!-- Hero -->
            <main class="py-20 lg:py-32 grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-widest text-orange-400 mb-8 backdrop-blur-md">
                        <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                        <span>Experience the extraordinary</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-8xl font-black tracking-tight leading-[0.9] mb-8">
                        Discover & <br/>
                        <span class="text-gradient">Experience</span>
                    </h1>
                    <p class="text-xl text-white/60 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        The ultimate destination for exclusive events, secret concerts, and high-energy festivals. Securing your spot has never been this seamless.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        @if(!auth()->check() || auth()->user()->isCustomer())
                            <a href="{{ route('events.index') }}" class="btn-premium bg-gradient-to-r from-orange-500 to-rose-600 text-white w-full sm:w-auto text-center">
                                Browse Events
                            </a>
                        @endif
                        @if(!auth()->check() || !auth()->user()->isCustomer())
                            <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn-premium bg-white/5 border border-white/10 text-white hover:bg-white/10 backdrop-blur-md w-full sm:w-auto text-center">
                                {{ auth()->check() ? 'Go to Dashboard' : 'Start Hosting' }}
                            </a>
                        @endif
                    </div>
                    
                    <div class="mt-12 flex items-center justify-center lg:justify-start space-x-4 grayscale opacity-40">
                        <span class="text-sm font-bold uppercase tracking-widest">Trusted By</span>
                        <div class="h-px w-12 bg-white/20"></div>
                        <span class="font-black italic">SONY</span>
                        <span class="font-black italic">LIVE NATION</span>
                        <span class="font-black italic">WARNER</span>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="glass-dark rounded-[40px] p-4 p-8 relative z-20 overflow-hidden shadow-2xl">
                        <!-- Simulated App Interface -->
                        <div class="flex justify-between items-center mb-10">
                            <h3 class="text-2xl font-bold">Featured Experiences</h3>
                            <div class="flex space-x-1">
                                <div class="w-1.5 h-1.5 bg-white/20 rounded-full"></div>
                                <div class="w-1.5 h-1.5 bg-orange-500 rounded-full"></div>
                                <div class="w-1.5 h-1.5 bg-white/20 rounded-full"></div>
                            </div>
                        </div>

                        <div id="featured-events-container" class="space-y-6">
                            <!-- Events will be loaded here via API -->
                            <div class="animate-pulse space-y-4">
                                <div class="h-24 bg-white/5 rounded-3xl"></div>
                                <div class="h-24 bg-white/5 rounded-3xl"></div>
                            </div>
                        </div>

                        @if($featuredEvents->count() > 0)
                            <div class="mt-10 p-6 bg-gradient-to-r from-orange-600/20 to-transparent border border-orange-500/20 rounded-3xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-orange-400 font-bold uppercase">Ready to join?</p>
                                        <p class="text-lg font-bold">Secure your spot today</p>
                                    </div>
                                    @if(!auth()->check() || auth()->user()->isCustomer())
                                        <a href="{{ route('events.index') }}" class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/40 hover:scale-110 transition">
                                            <span class="text-xl">→</span>
                                        </a>
                                    @else
                                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                                            <span>✨</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Glow behind -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-orange-600/10 blur-[150px] rounded-full z-10"></div>
                </div>
            </main>
        </div>

        <!-- Features Section -->
        <div class="relative py-32 overflow-hidden border-t border-white/5">
            <!-- Background Decoration -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-orange-600/5 blur-[120px] rounded-full"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-orange-500 mb-4">Core Technology</h2>
                    <h3 class="text-4xl md:text-5xl font-black tracking-tight">Built for the <span class="text-gradient">Next Generation</span></h3>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[3rem]"></div>
                        <div class="glass-dark p-10 rounded-[3rem] border border-white/5 hover:border-orange-500/30 transition-all duration-500 relative z-10 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-tr from-orange-500/20 to-orange-500/10 border border-orange-500/20 rounded-2xl flex items-center justify-center text-orange-500 mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-lg shadow-orange-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 group-hover:text-orange-400 transition-colors">Smart Ticketing</h3>
                            <p class="text-white/50 leading-relaxed text-sm">
                                Identity-linked tickets that prevent scalping and ensure you always have a valid pass. Securing your spot has never been more robust.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[3rem]"></div>
                        <div class="glass-dark p-10 rounded-[3rem] border border-white/5 hover:border-purple-500/30 transition-all duration-500 relative z-10 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-tr from-purple-500/20 to-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-lg shadow-purple-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 group-hover:text-purple-400 transition-colors">Event Insights</h3>
                            <p class="text-white/50 leading-relaxed text-sm">
                                Advanced analytics for organisers to track popularity, monitor traffic, and optimize booking flows in real-time.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[3rem]"></div>
                        <div class="glass-dark p-10 rounded-[3rem] border border-white/5 hover:border-blue-500/30 transition-all duration-500 relative z-10 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-tr from-blue-500/20 to-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-lg shadow-blue-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 group-hover:text-blue-400 transition-colors">Secure Checkout</h3>
                            <p class="text-white/50 leading-relaxed text-sm">
                                Military-grade encryption for every transaction. We prioritize your privacy and data security above all else.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <footer class="py-12 border-t border-white/5 text-center text-white/30 text-sm">
            <p>&copy; {{ date('Y') }} Eventix. All rights reserved.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('featured-events-container');

            // 1. Fetch data from our internal Discovery API URL
            fetch('/api/v1/discovery', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                if (res.success && res.data.trending.length > 0) {
                    container.innerHTML = ''; // Clear loading state
                    
                    // 2. Loop through trending events and inject them into the UI
                    res.data.trending.forEach(event => {
                        const eventDate = new Date(event.starts_at).toLocaleDateString('en-US', {
                            weekday: 'short',
                            hour: 'numeric'
                        });

                        const html = `
                            <a href="/events/${event.id}" class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:bg-white/10 transition group cursor-pointer block">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-orange-500">${event.category}</span>
                                        <h4 class="text-xl font-bold group-hover:text-orange-400 transition-colors">${event.title}</h4>
                                    </div>
                                    <span class="bg-green-500/20 text-green-400 text-[10px] px-2 py-1 rounded-md font-bold uppercase tracking-tighter">Available</span>
                                </div>
                                <div class="flex items-center text-sm text-white/40 space-x-4">
                                    <span>📍 ${event.city}</span>
                                    <span>📅 ${eventDate}</span>
                                </div>
                            </a>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                } else {
                    container.innerHTML = '<div class="text-center py-10"><p class="text-white/30 font-bold uppercase tracking-widest text-xs">No trending experiences yet.</p></div>';
                }
            })
            .catch(error => {
                console.error('API Error:', error);
                container.innerHTML = '<div class="text-center py-10 text-rose-400 text-xs font-bold">Failed to load experiences.</div>';
            });
        });
    </script>
</x-guest-layout>

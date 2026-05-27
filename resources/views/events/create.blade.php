<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Host an <span class="text-gradient">Experience</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
                <!-- Background glow -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/10 blur-[100px] translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none"></div>

                <form action="{{ route('events.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-2">
                            <x-label for="title" value="Experience Name" />
                            <x-input id="title" name="title" type="text" class="w-full" required value="{{ old('title') }}" placeholder="Midnight Jazz at the Crypt" />
                            @error('title') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <x-label for="description" value="Details & Context" />
                            <textarea id="description" name="description" rows="5" 
                                      class="bg-white/5 border border-white/10 text-white placeholder:text-white/20 focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-4 px-5 transition-all duration-300 w-full"
                                      placeholder="Describe the atmosphere, what to expect...">{{ old('description') }}</textarea>
                            @error('description') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="venue" value="Venue / Architecture" />
                            <x-input id="venue" name="venue" type="text" class="w-full" required value="{{ old('venue') }}" placeholder="Long Bar" />
                            @error('venue') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="city" value="City / Urban Hub" />
                            <x-input id="city" name="city" type="text" class="w-full" required value="{{ old('city') }}" placeholder="Colombo" />
                            @error('city') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-2xl bg-white/3 border border-white/5 relative overflow-hidden">
                                <div class="space-y-1">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-white">Auto-Locate Coordinates</h4>
                                    <p class="text-[10px] font-medium text-white/40">Find the exact coordinates in Sri Lanka using Nominatim.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" id="auto-locate-btn" onclick="autoGeocode(event)" class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500 hover:text-orange-400 transition flex items-center gap-2 bg-orange-500/10 hover:bg-orange-500/20 px-4 py-2.5 rounded-xl border border-orange-500/20 active:scale-[0.98] duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Auto-locate Venue
                                    </button>
                                </div>
                            </div>
                            <div id="geocode-feedback" class="hidden mt-2 text-[10px] font-bold uppercase tracking-widest"></div>
                        </div>

                        <div>
                            <x-label for="latitude" value="Latitude" />
                            <div class="relative">
                                <x-input id="latitude" name="latitude" type="number" step="0.00000001" class="w-full pr-10 cursor-not-allowed opacity-70" required value="{{ old('latitude') }}" placeholder="6.9271" readonly />
                                <button type="button" onclick="toggleReadonly('latitude')" id="latitude-lock-btn" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/30 hover:text-white transition" title="Unlock for manual edit">
                                    <svg id="latitude-lock-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('latitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="longitude" value="Longitude" />
                            <div class="relative">
                                <x-input id="longitude" name="longitude" type="number" step="0.00000001" class="w-full pr-10 cursor-not-allowed opacity-70" required value="{{ old('longitude') }}" placeholder="79.8612" readonly />
                                <button type="button" onclick="toggleReadonly('longitude')" id="longitude-lock-btn" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/30 hover:text-white transition" title="Unlock for manual edit">
                                    <svg id="longitude-lock-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('longitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="category" value="Nature of Experience" />
                            <select id="category" name="category" required 
                                    class="bg-white/5 border border-white/10 text-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-3.5 px-4 transition-all duration-300 w-full appearance-none cursor-pointer">
                                <option value="music" class="bg-slate-900" {{ old('category') == 'music' ? 'selected' : '' }}>Music</option>
                                <option value="conference" class="bg-slate-900" {{ old('category') == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="culture" class="bg-slate-900" {{ old('category') == 'culture' ? 'selected' : '' }}>Culture</option>
                                <option value="sports" class="bg-slate-900" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports</option>
                                <option value="education" class="bg-slate-900" {{ old('category') == 'education' ? 'selected' : '' }}>Education</option>
                            </select>
                            @error('category') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="total_capacity" value="Total Capacity" />
                            <x-input id="total_capacity" name="total_capacity" type="number" min="1" class="w-full" required value="{{ old('total_capacity') }}" placeholder="500" />
                            @error('total_capacity') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <x-label for="starts_at" value="Commencement" />
                            <x-input id="starts_at" name="starts_at" type="datetime-local" class="w-full" required value="{{ old('starts_at') }}" />
                            @error('starts_at') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="ends_at" value="Conclusion (Optional)" />
                            <x-input id="ends_at" name="ends_at" type="datetime-local" class="w-full" value="{{ old('ends_at') }}" />
                            @error('ends_at') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ticket Tiers Section -->
                        <div class="col-span-2 mt-8" x-data="{ 
                            tickets: {{ old('tickets') ? json_encode(old('tickets')) : '[{name: \'\', price: \'\', quantity: \'\'}]' }},
                            addTicket() {
                                this.tickets.push({name: '', price: '', quantity: ''});
                            },
                            removeTicket(index) {
                                if (this.tickets.length > 1) {
                                    this.tickets.splice(index, 1);
                                }
                            }
                        }">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-white tracking-tight">Ticket Tiers</h3>
                                <button type="button" @click="addTicket" class="text-xs font-black uppercase tracking-widest text-orange-500 hover:text-orange-400 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Tier
                                </button>
                            </div>

                            @if ($errors->has('tickets*'))
                                <div class="p-4 mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold space-y-1">
                                    <p class="font-black uppercase tracking-wider text-[10px]">Ticket Tier Errors:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->get('tickets*') as $key => $messages)
                                            @foreach ($messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <template x-for="(ticket, index) in tickets" :key="index">
                                    <div class="glass-dark border border-white/5 p-6 rounded-3xl relative group">
                                        <button type="button" @click="removeTicket(index)" x-show="tickets.length > 1" class="absolute -top-2 -right-2 bg-rose-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 shadow-lg shadow-rose-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <x-label ::for="'ticket_name_' + index" value="Tier Name" />
                                                <x-input ::id="'ticket_name_' + index" ::name="'tickets[' + index + '][name]'" x-model="ticket.name" type="text" class="w-full" required placeholder="General Admission" />
                                            </div>
                                            <div>
                                                <x-label ::for="'ticket_price_' + index" value="Price (Rs.)" />
                                                <x-input ::id="'ticket_price_' + index" ::name="'tickets[' + index + '][price]'" x-model="ticket.price" type="number" step="0.01" min="0" class="w-full" required placeholder="500.00" />
                                            </div>
                                            <div>
                                                <x-label ::for="'ticket_quantity_' + index" value="Quantity" />
                                                <x-input ::id="'ticket_quantity_' + index" ::name="'tickets[' + index + '][quantity]'" x-model="ticket.quantity" type="number" min="1" class="w-full" required placeholder="100" />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-10 border-t border-white/5 mt-10">
                        <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition">Cancel</a>
                        <x-button>
                            Submit for Authentication
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleReadonly(fieldId) {
            const input = document.getElementById(fieldId);
            const btn = document.getElementById(fieldId + '-lock-btn');
            const icon = document.getElementById(fieldId + '-lock-icon');

            if (input.hasAttribute('readonly')) {
                input.removeAttribute('readonly');
                input.classList.remove('cursor-not-allowed', 'opacity-70');
                btn.title = "Lock field";
                
                // Change icon to unlocked
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />`;
                icon.classList.remove('text-white/30');
                icon.classList.add('text-orange-500');
            } else {
                input.setAttribute('readonly', 'true');
                input.classList.add('cursor-not-allowed', 'opacity-70');
                btn.title = "Unlock for manual edit";

                // Change icon to locked
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />`;
                icon.classList.remove('text-orange-500');
                icon.classList.add('text-white/30');
            }
        }

        async function autoGeocode(event) {
            const venueInput = document.getElementById('venue');
            const cityInput = document.getElementById('city');
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');
            const feedbackDiv = document.getElementById('geocode-feedback');
            const btn = document.getElementById('auto-locate-btn');

            const venue = venueInput.value.trim();
            const city = cityInput.value.trim();

            // Reset feedback
            feedbackDiv.classList.remove('hidden');
            feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-white/50 block';
            feedbackDiv.innerHTML = '';

            if (!venue || !city) {
                feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-rose-400 block';
                feedbackDiv.innerHTML = '⚠ Please enter venue and city first';
                return;
            }

            // Set loading state
            const originalBtnHTML = btn.innerHTML;
            btn.innerHTML = `
                <svg class="animate-spin h-3.5 w-3.5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Locating venue...
            `;
            btn.disabled = true;
            feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-orange-400 animate-pulse block';
            feedbackDiv.innerHTML = '⏳ Locating venue...';

            try {
                const query = `${venue}, ${city}, Sri Lanka`;
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat).toFixed(8);
                    const lon = parseFloat(data[0].lon).toFixed(8);

                    latInput.value = lat;
                    lonInput.value = lon;

                    feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-emerald-400 block animate-none';
                    feedbackDiv.innerHTML = '✓ Coordinates located successfully!';
                    
                    // Reset lock states when auto-locating
                    if (!latInput.hasAttribute('readonly')) {
                        toggleReadonly('latitude');
                    }
                    if (!lonInput.hasAttribute('readonly')) {
                        toggleReadonly('longitude');
                    }

                    setTimeout(() => {
                        feedbackDiv.classList.add('opacity-0', 'transition-all', 'duration-500');
                        setTimeout(() => {
                            feedbackDiv.classList.remove('block');
                            feedbackDiv.classList.add('hidden');
                        }, 500);
                    }, 4000);
                } else {
                    feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-rose-400 block';
                    feedbackDiv.innerHTML = '⚠ Location not found';
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                feedbackDiv.className = 'mt-2 text-[10px] font-bold uppercase tracking-widest text-rose-400 block';
                feedbackDiv.innerHTML = '⚠ Unable to fetch coordinates';
            } finally {
                btn.innerHTML = originalBtnHTML;
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>

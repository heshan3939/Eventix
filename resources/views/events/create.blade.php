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
                                      placeholder="Describe the atmosphere, what to expect..."></textarea>
                        </div>

                        <div>
                            <x-label for="venue" value="Venue / Architecture" />
                            <x-input id="venue" name="venue" type="text" class="w-full" required value="{{ old('venue') }}" placeholder="The Silver Vaults" />
                        </div>

                        <div>
                            <x-label for="city" value="City / Urban Hub" />
                            <x-input id="city" name="city" type="text" class="w-full" required value="{{ old('city') }}" placeholder="London" />
                        </div>

                        <div>
                            <x-label for="latitude" value="Latitude" />
                            <x-input id="latitude" name="latitude" type="number" step="0.00000001" class="w-full" required value="{{ old('latitude') }}" placeholder="51.5074" />
                            @error('latitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="longitude" value="Longitude" />
                            <x-input id="longitude" name="longitude" type="number" step="0.00000001" class="w-full" required value="{{ old('longitude') }}" placeholder="-0.1278" />
                            @error('longitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="category" value="Nature of Experience" />
                            <select id="category" name="category" required 
                                    class="bg-white/5 border border-white/10 text-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-3.5 px-4 transition-all duration-300 w-full appearance-none cursor-pointer">
                                <option value="music" class="bg-slate-900">Music</option>
                                <option value="conference" class="bg-slate-900">Conference</option>
                                <option value="culture" class="bg-slate-900">Culture</option>
                                <option value="sports" class="bg-slate-900">Sports</option>
                                <option value="education" class="bg-slate-900">Education</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="total_capacity" value="Total Capacity" />
                            <x-input id="total_capacity" name="total_capacity" type="number" min="1" class="w-full" required value="{{ old('total_capacity') }}" placeholder="500" />
                            @error('total_capacity') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <x-label for="starts_at" value="Commencement" />
                            <x-input id="starts_at" name="starts_at" type="datetime-local" class="w-full" required value="{{ old('starts_at') }}" />
                        </div>

                        <div>
                            <x-label for="ends_at" value="Conclusion (Optional)" />
                            <x-input id="ends_at" name="ends_at" type="datetime-local" class="w-full" value="{{ old('ends_at') }}" />
                        </div>

                        <div class="col-span-2">
                            <button type="button" onclick="autoGeocode()" class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500 hover:text-orange-400 transition flex items-center gap-2 bg-orange-500/10 px-4 py-2 rounded-xl border border-orange-500/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Auto-locate Venue
                            </button>
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
                                                <x-label ::for="'ticket_price_' + index" value="Price ($)" />
                                                <x-input ::id="'ticket_price_' + index" ::name="'tickets[' + index + '][price]'" x-model="ticket.price" type="number" step="0.01" min="0" class="w-full" required placeholder="25.00" />
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
        async function autoGeocode() {
            const venue = document.getElementById('venue').value;
            const city = document.getElementById('city').value;
            
            if (!venue || !city) {
                alert('Please enter both Venue and City first.');
                return;
            }

            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            btn.innerHTML = 'Locating...';
            btn.disabled = true;

            try {
                const query = `${venue}, ${city}`;
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    document.getElementById('latitude').value = parseFloat(data[0].lat).toFixed(8);
                    document.getElementById('longitude').value = parseFloat(data[0].lon).toFixed(8);
                } else {
                    alert('Could not find coordinates for this location. Please enter them manually.');
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                alert('An error occurred while fetching coordinates.');
            } finally {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>

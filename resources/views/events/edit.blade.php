<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Refine <span class="text-gradient">Experience</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
                <!-- Background glow -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/10 blur-[100px] translate-x-1/2 -translate-y-1/2 rounded-full pointer-events-none"></div>

                <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-2">
                            <x-label for="title" value="Experience Name" />
                            <x-input id="title" name="title" type="text" class="w-full" required value="{{ old('title', $event->title) }}" />
                        </div>

                        <div class="col-span-2">
                            <x-label for="description" value="Details & Context" />
                            <textarea id="description" name="description" rows="5" 
                                      class="bg-white/5 border border-white/10 text-white placeholder:text-white/20 focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-4 px-5 transition-all duration-300 w-full">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div>
                            <x-label for="venue" value="Venue / Architecture" />
                            <x-input id="venue" name="venue" type="text" class="w-full" required value="{{ old('venue', $event->venue) }}" />
                        </div>

                        <div>
                            <x-label for="city" value="City / Urban Hub" />
                            <x-input id="city" name="city" type="text" class="w-full" required value="{{ old('city', $event->city) }}" />
                        </div>

                        <div>
                            <x-label for="latitude" value="Latitude" />
                            <x-input id="latitude" name="latitude" type="number" step="0.00000001" class="w-full" required value="{{ old('latitude', $event->latitude) }}" />
                            @error('latitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="longitude" value="Longitude" />
                            <x-input id="longitude" name="longitude" type="number" step="0.00000001" class="w-full" required value="{{ old('longitude', $event->longitude) }}" />
                            @error('longitude') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-label for="category" value="Nature of Experience" />
                            <select id="category" name="category" required 
                                    class="bg-white/5 border border-white/10 text-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-3.5 px-4 transition-all duration-300 w-full appearance-none cursor-pointer">
                                @foreach(['music', 'conference', 'culture', 'sports', 'education'] as $cat)
                                    <option value="{{ $cat }}" class="bg-slate-900" @if($event->category == $cat) selected @endif>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="total_capacity" value="Total Capacity" />
                            <x-input id="total_capacity" name="total_capacity" type="number" min="1" class="w-full" required value="{{ old('total_capacity', $event->total_capacity) }}" />
                            @error('total_capacity') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <x-label for="starts_at" value="Commencement" />
                            <x-input id="starts_at" name="starts_at" type="datetime-local" class="w-full" required value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}" />
                        </div>

                        <div>
                            <x-label for="ends_at" value="Conclusion (Optional)" />
                            <x-input id="ends_at" name="ends_at" type="datetime-local" class="w-full" value="{{ old('ends_at', $event->ends_at ? $event->ends_at->format('Y-m-d\TH:i') : '') }}" />
                        </div>

                        <div class="col-span-2">
                            <button type="button" onclick="autoGeocode()" class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500 hover:text-orange-400 transition flex items-center gap-2 bg-orange-500/10 px-4 py-2 rounded-xl border border-orange-500/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Auto-locate Venue
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-10 border-t border-white/5 mt-10">
                        <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition">Abandon Changes</a>
                        <x-button>
                            Submit Updates
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

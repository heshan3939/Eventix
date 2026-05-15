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
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            Manage <span class="text-gradient">Inventory</span>
        </h2>
        <p class="text-white/40 text-sm font-medium mt-2">Adjust ticket availability and types for "{{ $event->title }}"</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl flex items-center space-x-3 backdrop-blur-xl">
                    <span class="text-xl">✨</span>
                    <p class="font-bold text-sm tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Update Existing Tiers -->
                <div class="lg:col-span-2">
                    <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h3 class="text-2xl font-bold text-white tracking-tight">Active Tiers</h3>
                                <p class="text-white/30 text-xs mt-1 font-medium uppercase tracking-widest">Update capacity for existing tickets</p>
                            </div>
                        </div>                        <div id="active-tiers-list" class="space-y-6">
                            @foreach($event->ticketTypes as $index => $ticket)
                                <div id="ticket-row-{{ $ticket->id }}" class="flex flex-col md:flex-row md:items-center gap-6 p-6 rounded-3xl bg-white/[0.02] border border-white/5 group hover:bg-white/[0.04] transition-all">
                                    <div class="flex-grow">
                                        <div class="text-white font-bold text-lg">{{ $ticket->name }}</div>
                                        <div class="text-orange-500 font-black text-sm mt-1">Rs. {{ number_format($ticket->price, 2) }}</div>
                                        
                                        @php
                                            $booked = $ticket->bookings()->where('status', 'confirmed')->sum('quantity');
                                            $percentage = $ticket->quantity > 0 ? ($booked / $ticket->quantity) * 100 : 0;
                                        @endphp
                                        
                                        <div class="mt-4 space-y-2">
                                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                                <span class="text-white/30">Utilization</span>
                                                <span class="text-white/60"><span id="booked-{{ $ticket->id }}">{{ $booked }}</span> / <span id="capacity-display-{{ $ticket->id }}">{{ $ticket->quantity }}</span> Sold</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                                <div id="progress-{{ $ticket->id }}" class="h-full bg-gradient-to-r from-orange-500 to-rose-600 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:w-48 flex flex-col gap-2">
                                        <x-label value="Capacity" />
                                        <div class="flex gap-2">
                                            <x-input id="input-{{ $ticket->id }}" type="number" min="{{ $booked }}" class="w-full" value="{{ $ticket->quantity }}" />
                                            <button onclick="updateTicket({{ $ticket->id }})" class="p-2 bg-orange-500 rounded-xl hover:bg-orange-600 transition">
                                                💾
                                            </button>
                                            <button onclick="deleteTicket({{ $ticket->id }})" class="p-2 bg-rose-500/20 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition">
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
m>
                    </div>
                </div>

                <!-- Add New Tier -->
                <div class="lg:col-span-1">
                    <div class="glass-dark border border-white/5 rounded-[2.5rem] p-10 shadow-2xl sticky top-8">
                        <h3 class="text-2xl font-bold text-white tracking-tight mb-2">New Tier</h3>
                        <p class="text-white/30 text-xs mb-10 font-medium uppercase tracking-widest">Add a new ticket category</p>

                        <div class="space-y-6">
                            <div>
                                <x-label for="new_name" value="Category Name" />
                                <x-input id="new_name" type="text" class="w-full" placeholder="e.g. Backstage Pass" />
                            </div>

                            <div>
                                <x-label for="new_price" value="Price (Rs.)" />
                                <x-input id="new_price" type="number" step="0.01" min="0" class="w-full" placeholder="1500.00" />
                            </div>

                            <div>
                                <x-label for="new_quantity" value="Initial Capacity" />
                                <x-input id="new_quantity" type="number" min="1" class="w-full" placeholder="50" />
                            </div>

                            <div class="pt-4">
                                <x-button id="add-ticket-btn" class="w-full justify-center" onclick="addTicket()">
                                    Add Ticket Tier
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
    </div>

    <script>
        const API_BASE = '/api/v1/ticket-types';
        const EVENT_ID = {{ $event->id }};

        // 1. Create a new Ticket Type via API
        async function addTicket() {
            const btn = document.getElementById('add-ticket-btn');
            const data = {
                event_id: EVENT_ID,
                name: document.getElementById('new_name').value,
                price: document.getElementById('new_price').value,
                quantity: document.getElementById('new_quantity').value
            };

            btn.disabled = true;
            try {
                const response = await axios.post(API_BASE, data);
                if (response.data.success) {
                    location.reload(); // Refresh to show new tier
                } else {
                    alert('Error: ' + (response.data.message || 'Check your inputs'));
                }
            } catch (e) {
                console.error(e);
                alert('Error: ' + (e.response?.data?.message || 'Check your inputs'));
            } finally {
                btn.disabled = false;
            }
        }

        // 2. Update existing Ticket Type via API (PUT)
        async function updateTicket(id) {
            const newQty = document.getElementById(`input-${id}`).value;
            
            try {
                const response = await axios.put(`${API_BASE}/${id}`, { quantity: newQty });
                if (response.data.success) {
                    // Update UI stats
                    const booked = parseInt(document.getElementById(`booked-${id}`).innerText);
                    const percentage = (booked / newQty) * 100;
                    document.getElementById(`capacity-display-${id}`).innerText = newQty;
                    document.getElementById(`progress-${id}`).style.width = `${percentage}%`;
                    alert('Capacity updated via API!');
                } else {
                    alert(response.data.message);
                }
            } catch (e) {
                console.error(e);
                alert('Error: ' + (e.response?.data?.message || 'Check your inputs'));
            }
        }

        // 3. Delete Ticket Type via API (DELETE)
        async function deleteTicket(id) {
            if (!confirm('Are you sure you want to delete this ticket tier?')) return;

            try {
                const response = await axios.delete(`${API_BASE}/${id}`);
                if (response.data.success) {
                    document.getElementById(`ticket-row-${id}`).remove();
                } else {
                    alert(response.data.message);
                }
            } catch (e) {
                console.error(e);
                alert('Error: ' + (e.response?.data?.message || 'Failed to delete ticket tier'));
            }
        }
    </script>
</x-app-layout>

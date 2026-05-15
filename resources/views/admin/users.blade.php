<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">Identity <span class="text-gradient">Vault</span></h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-dark border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold tracking-tight">Community Directory</h3>
                        <p class="text-white/30 text-xs mt-1">Manage and audit all registered identities across the platform.</p>
                    </div>
                    
                    <form method="GET" action="{{ route('admin.users') }}" x-data>
                        <select name="role" onchange="this.form.submit()" 
                                class="bg-white/5 border border-white/10 rounded-xl px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white/70 focus:ring-orange-500/30 focus:border-orange-500 transition cursor-pointer appearance-none">
                            <option value="" class="bg-slate-900">All Identities</option>
                            <option value="admin" {{ $role == 'admin' ? 'selected' : '' }} class="bg-slate-900">Admins</option>
                            <option value="organiser" {{ $role == 'organiser' ? 'selected' : '' }} class="bg-slate-900">Organisers</option>
                            <option value="customer" {{ $role == 'customer' ? 'selected' : '' }} class="bg-slate-900">Customers</option>
                        </select>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Identity</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Credentials</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Security Role</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Activation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($users as $user)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-tr from-white/10 to-white/5 border border-white/10 rounded-xl flex items-center justify-center font-bold text-white/50">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="font-bold text-white group-hover:text-orange-400 transition-colors">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white/50 tracking-tight">{{ $user->email }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    @php
                                        $roleClasses = [
                                            'admin' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            'organiser' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            'customer' => 'bg-white/5 text-white/40 border-white/10',
                                        ];
                                        $class = $roleClasses[$user->role] ?? 'bg-white/5 text-white/40 border-white/10';
                                    @endphp
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $class }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-bold text-white/30 tracking-tighter">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                {{ $users->appends(['role' => $role])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

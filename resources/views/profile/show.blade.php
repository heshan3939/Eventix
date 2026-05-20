<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
            {{ __('My Profile') }}
        </h2>
        <p class="text-white/40 text-sm mt-2 font-medium">Manage your personal information, password, and security settings.</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT SIDEBAR --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Avatar Card --}}
                    <div class="glass-dark border border-white/10 rounded-[2rem] p-8 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-orange-500/5 to-rose-600/5 pointer-events-none"></div>
                        
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div x-data="{ photoPreview: null }" class="relative z-10">
                                <input type="file" id="sidebar_photo" class="hidden"
                                    x-ref="photo"
                                    wire:model.live="photo"
                                    x-on:change="
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    " />
                                
                                <div class="relative inline-block mb-4">
                                    {{-- Current Photo --}}
                                    <div x-show="!photoPreview">
                                        <img src="{{ Auth::user()->profile_photo_url }}"
                                             alt="{{ Auth::user()->name }}"
                                             class="w-24 h-24 rounded-full object-cover mx-auto ring-4 ring-orange-500/30 shadow-xl shadow-orange-500/10">
                                    </div>
                                    {{-- Photo Preview --}}
                                    <div x-show="photoPreview" style="display:none">
                                        <span class="block w-24 h-24 rounded-full bg-cover bg-center mx-auto ring-4 ring-orange-500/50"
                                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                        </span>
                                    </div>
                                    {{-- Camera Badge --}}
                                    <button type="button"
                                            @click.prevent="document.getElementById('sidebar_photo').click()"
                                            class="absolute bottom-0 right-0 w-8 h-8 bg-gradient-to-br from-orange-500 to-rose-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-500 to-rose-600 flex items-center justify-center mx-auto mb-4 ring-4 ring-orange-500/20 text-3xl font-black text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <h3 class="text-xl font-black text-white tracking-tight">{{ Auth::user()->name }}</h3>
                        <p class="text-white/40 text-sm font-medium mt-1">{{ Auth::user()->email }}</p>

                        <div class="mt-4">
                            @php
                                $roleColors = [
                                    'admin'     => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                    'organiser' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'customer'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                ];
                                $roleClass = $roleColors[Auth::user()->role] ?? 'bg-white/5 text-white/40 border-white/10';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $roleClass }}">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="glass-dark border border-white/10 rounded-[2rem] p-6 space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30 mb-4">Quick Actions</p>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition group">
                            <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center group-hover:bg-orange-500/10 transition">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-orange-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            </div>
                            <span class="text-sm font-bold text-white/60 group-hover:text-white transition">Dashboard</span>
                        </a>
                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <a href="{{ route('api-tokens.index') }}" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition group">
                            <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center group-hover:bg-orange-500/10 transition">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-orange-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                            </div>
                            <span class="text-sm font-bold text-white/60 group-hover:text-white transition">API Tokens</span>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- RIGHT MAIN CONTENT --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Profile Information --}}
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        <div class="glass-dark border border-white/10 rounded-[2rem] overflow-hidden">
                            <div class="px-8 pt-8 pb-2">
                                <div class="flex items-center space-x-3 mb-1">
                                    <div class="w-1 h-6 bg-gradient-to-b from-orange-500 to-rose-600 rounded-full"></div>
                                    <h3 class="text-lg font-black text-white tracking-tight">Profile Information</h3>
                                </div>
                                <p class="text-white/30 text-xs font-medium ml-4">Update your display name and email address.</p>
                            </div>
                            <div class="px-8 pb-8 pt-6">
                                @livewire('profile.update-profile-information-form')
                            </div>
                        </div>
                    @endif

                    {{-- Change Password --}}
                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <div class="glass-dark border border-white/10 rounded-[2rem] overflow-hidden">
                            <div class="px-8 pt-8 pb-2">
                                <div class="flex items-center space-x-3 mb-1">
                                    <div class="w-1 h-6 bg-gradient-to-b from-orange-500 to-rose-600 rounded-full"></div>
                                    <h3 class="text-lg font-black text-white tracking-tight">Change Password</h3>
                                </div>
                                <p class="text-white/30 text-xs font-medium ml-4">Use a long, random password to stay secure.</p>
                            </div>
                            <div class="px-8 pb-8 pt-6">
                                @livewire('profile.update-password-form')
                            </div>
                        </div>
                    @endif

                    {{-- Two Factor Auth --}}
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div class="glass-dark border border-white/10 rounded-[2rem] overflow-hidden">
                            <div class="px-8 pt-8 pb-2">
                                <div class="flex items-center space-x-3 mb-1">
                                    <div class="w-1 h-6 bg-gradient-to-b from-orange-500 to-rose-600 rounded-full"></div>
                                    <h3 class="text-lg font-black text-white tracking-tight">Two-Factor Authentication</h3>
                                </div>
                                <p class="text-white/30 text-xs font-medium ml-4">Add an extra layer of security to your account.</p>
                            </div>
                            <div class="px-8 pb-8 pt-6">
                                @livewire('profile.two-factor-authentication-form')
                            </div>
                        </div>
                    @endif

                    {{-- Delete Account --}}
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div class="glass-dark border border-rose-500/20 rounded-[2rem] overflow-hidden">
                            <div class="px-8 pt-8 pb-2">
                                <div class="flex items-center space-x-3 mb-1">
                                    <div class="w-1 h-6 bg-rose-500 rounded-full"></div>
                                    <h3 class="text-lg font-black text-rose-400 tracking-tight">Danger Zone</h3>
                                </div>
                                <p class="text-white/30 text-xs font-medium ml-4">Permanently delete your account and all associated data.</p>
                            </div>
                            <div class="px-8 pb-8 pt-6">
                                @livewire('profile.delete-user-form')
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

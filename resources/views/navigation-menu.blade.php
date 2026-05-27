<nav x-data="{ open: false }" class="bg-slate-950/50 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-tr from-orange-500 to-rose-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <span class="font-black text-sm text-white">E</span>
                        </div>
                        <span class="text-xl font-black tracking-tighter uppercase text-white transition hover:text-orange-500">Eventix</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white/60 hover:text-white border-transparent hover:border-orange-500 transition">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->isCustomer())
                        <x-nav-link href="{{ route('events.index') }}" :active="request()->routeIs('events.index')" class="text-white/60 hover:text-white border-transparent hover:border-orange-500 transition">
                            {{ __('Discover') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('bookings.index') }}" :active="request()->routeIs('bookings.index')" class="text-white/60 hover:text-white border-transparent hover:border-orange-500 transition">
                            {{ __('My Tickets') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-white/10 rounded-2xl overflow-hidden focus:outline-none focus:border-orange-500 transition shadow-lg">
                                    <img class="size-9 object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-white/10 text-sm leading-4 font-bold rounded-xl text-white/70 bg-white/5 hover:text-white hover:bg-white/10 focus:outline-none transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white/30">
                                    {{ __('Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}" class="text-white/70 hover:bg-white/5 hover:text-white">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                           
                                <div class="border-t border-white/5"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                             @click.prevent="$root.submit();" class="text-rose-400 hover:bg-rose-500/10 hover:text-rose-300">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/40 hover:text-white hover:bg-white/5 focus:outline-none transition duration-150">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white/60 hover:text-white">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::user()->isCustomer())
                <x-responsive-nav-link href="{{ route('events.index') }}" :active="request()->routeIs('events.index')" class="text-white/60 hover:text-white">
                    {{ __('Discover') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('bookings.index') }}" :active="request()->routeIs('bookings.index')" class="text-white/60 hover:text-white">
                    {{ __('My Tickets') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5">
            <div class="flex items-center px-4 mb-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-xl object-cover ring-2 ring-white/10" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-white/40">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-white/60 hover:text-white">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-rose-400">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

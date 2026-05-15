<x-guest-layout>
    <x-authentication-card>
        <div class="mb-8">
            <h2 class="text-3xl font-black tracking-tighter text-white mb-2">Welcome Back</h2>
            <p class="text-white/40 text-sm font-medium">Log in to your account to manage your experiences.</p>
        </div>

        <x-validation-errors class="mb-6 bg-rose-500/10 border border-rose-500/20 p-4 rounded-2xl text-rose-400 text-xs" />

        @session('status')
            <div class="mb-6 font-bold text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-2xl">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <x-label for="password" value="{{ __('Password') }}" />
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-black uppercase tracking-widest text-orange-500 hover:text-orange-400 transition" href="{{ route('password.request') }}">
                            {{ __('Forgot?') }}
                        </a>
                    @endif
                </div>
                <x-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center">
                <x-checkbox id="remember_me" name="remember" />
                <span class="ms-3 text-xs font-bold text-white/40 uppercase tracking-widest">{{ __('Stay signed in') }}</span>
            </div>

            <div class="pt-4">
                <x-button class="w-full">
                    {{ __('Sign In to Eventix') }}
                </x-button>
            </div>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
            <p class="text-xs text-white/30 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-orange-500 font-black uppercase tracking-widest border-b border-orange-500/20 hover:border-orange-500 transition ml-2">Join for free</a>
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>

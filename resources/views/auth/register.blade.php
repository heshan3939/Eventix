<x-guest-layout>
    <x-authentication-card>
        <div class="mb-8">
            <h2 class="text-3xl font-black tracking-tighter text-white mb-2">Create Account</h2>
            <p class="text-white/40 text-sm font-medium">Join the community of extraordinary experience seekers.</p>
        </div>

        <x-validation-errors class="mb-6 bg-rose-500/10 border border-rose-500/20 p-4 rounded-2xl text-rose-400 text-xs" />

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-label for="name" value="{{ __('Full Name') }}" />
                <x-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>

            <div class="space-y-2">
                <x-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            </div>

            <div class="space-y-2">
                <x-label for="role" value="{{ __('Join As') }}" />
                <select id="role" name="role" required class="bg-white/5 border border-white/10 text-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 rounded-2xl shadow-sm py-3 px-4 transition-all duration-300 w-full appearance-none cursor-pointer">
                    <option value="customer" class="bg-slate-900" selected>Experience Seeker (Customer)</option>
                    <option value="organiser" class="bg-slate-900">Experience Creator (Organiser)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                </div>

                <div class="space-y-2">
                    <x-label for="password_confirmation" value="{{ __('Confirm') }}" />
                    <x-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="pt-2">
                    <label class="flex items-start">
                        <x-checkbox name="terms" id="terms" required class="mt-0.5" />

                        <div class="ms-3 text-xs text-white/30 font-medium leading-relaxed">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-orange-500 hover:text-orange-400 font-bold transition">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-orange-500 hover:text-orange-400 font-bold transition">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </div>
                    </label>
                </div>
            @endif

            <div class="pt-4">
                <x-button class="w-full">
                    {{ __('Create My Account') }}
                </x-button>
            </div>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
            <p class="text-xs text-white/30 font-medium">
                Already a member? 
                <a href="{{ route('login') }}" class="text-orange-500 font-black uppercase tracking-widest border-b border-orange-500/20 hover:border-orange-500 transition ml-2">Sign In</a>
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>

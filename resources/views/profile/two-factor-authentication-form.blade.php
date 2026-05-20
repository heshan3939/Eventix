<div>
    {{-- Status Header --}}
    <div class="flex items-center space-x-3 mb-6">
        @if ($this->enabled)
            @if ($showingConfirmation)
                <div class="w-3 h-3 bg-amber-400 rounded-full animate-pulse"></div>
                <p class="text-sm font-bold text-amber-400">Finish enabling two-factor authentication.</p>
            @else
                <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                <p class="text-sm font-bold text-emerald-400">Two-factor authentication is active.</p>
            @endif
        @else
            <div class="w-3 h-3 bg-white/20 rounded-full"></div>
            <p class="text-sm font-bold text-white/40">Two-factor authentication is not enabled.</p>
        @endif
    </div>

    <p class="text-xs text-white/30 font-medium mb-6 leading-relaxed">
        When enabled, you will be prompted for a secure token during sign-in. Retrieve this token from your phone's Google Authenticator app.
    </p>

    @if ($this->enabled)
        {{-- QR Code Section --}}
        @if ($showingQrCode)
            <div class="mb-6">
                <p class="text-xs font-bold text-white/50 mb-4">
                    @if ($showingConfirmation)
                        Scan the QR code below using your authenticator app, then enter the generated code to confirm.
                    @else
                        Two-factor authentication is now enabled. Scan this QR code using your authenticator app.
                    @endif
                </p>

                <div class="inline-block p-3 bg-white rounded-2xl mb-4">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-white/30 mb-1">Setup Key</p>
                    <p class="font-mono text-sm text-orange-400 font-bold tracking-wider">{{ decrypt($this->user->two_factor_secret) }}</p>
                </div>

                @if ($showingConfirmation)
                    <div class="mb-4">
                        <label for="code" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Authenticator Code</label>
                        <input id="code" type="text" name="code"
                               wire:model="code"
                               wire:keydown.enter="confirmTwoFactorAuthentication"
                               inputmode="numeric" autofocus autocomplete="one-time-code"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-mono font-bold placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
                        @error('code')
                            <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        @endif

        {{-- Recovery Codes --}}
        @if ($showingRecoveryCodes)
            <div class="mb-6">
                <p class="text-xs font-bold text-amber-400/80 mb-3">
                    ⚠️ Store these recovery codes in a secure place. They can be used to recover access if you lose your device.
                </p>
                <div class="bg-black/30 border border-white/10 rounded-xl p-4 grid grid-cols-2 gap-2">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <span class="font-mono text-xs text-white/60 tracking-wider">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3">
        @if (! $this->enabled)
            <x-confirms-password wire:then="enableTwoFactorAuthentication">
                <button type="button" wire:loading.attr="disabled"
                        class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    Enable 2FA
                </button>
            </x-confirms-password>
        @else
            @if ($showingRecoveryCodes)
                <x-confirms-password wire:then="regenerateRecoveryCodes">
                    <button type="button"
                            class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white/70 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:text-white transition">
                        Regenerate Codes
                    </button>
                </x-confirms-password>
            @elseif ($showingConfirmation)
                <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                        Confirm & Activate
                    </button>
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="showRecoveryCodes">
                    <button type="button"
                            class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white/70 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:text-white transition">
                        Show Recovery Codes
                    </button>
                </x-confirms-password>
            @endif

            @if ($showingConfirmation)
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled"
                            class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white/40 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:text-white transition">
                        Cancel
                    </button>
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled"
                            class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-rose-400 bg-rose-500/5 border border-rose-500/20 rounded-xl hover:bg-rose-500/10 transition">
                        Disable 2FA
                    </button>
                </x-confirms-password>
            @endif
        @endif
    </div>
</div>

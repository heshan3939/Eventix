<div>
    <form wire:submit.prevent="updatePassword">

        {{-- Current Password --}}
        <div class="mb-5">
            <label for="current_password" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Current Password</label>
            <input id="current_password" type="password" wire:model="state.current_password" autocomplete="current-password"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
            @error('current_password')
                <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="mb-5">
            <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">New Password</label>
            <input id="password" type="password" wire:model="state.password" autocomplete="new-password"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
            @error('password')
                <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Confirm New Password</label>
            <input id="password_confirmation" type="password" wire:model="state.password_confirmation" autocomplete="new-password"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
            @error('password_confirmation')
                <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <x-action-message class="text-emerald-400 text-xs font-bold" on="saved">
                ✓ Password updated successfully.
            </x-action-message>
            <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                Update Password
            </button>
        </div>
    </form>
</div>

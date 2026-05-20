<div x-data="{ photoPreview: null }">
    <form wire:submit.prevent="updateProfileInformation">

        {{-- Profile Photo --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="mb-6">
                <label class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-3">Profile Photo</label>

                <input type="file" id="photo" class="hidden"
                    wire:model.live="photo"
                    x-ref="photo"
                    x-on:change="
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL($refs.photo.files[0]);
                    " />

                <div class="flex items-center space-x-5">
                    {{-- Current Photo --}}
                    <div x-show="!photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}"
                             alt="{{ $this->user->name }}"
                             class="w-16 h-16 rounded-2xl object-cover ring-2 ring-orange-500/30">
                    </div>
                    {{-- Preview --}}
                    <div x-show="photoPreview" style="display:none">
                        <span class="block w-16 h-16 rounded-2xl bg-cover bg-center ring-2 ring-orange-500/50"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <div class="space-x-2">
                        <button type="button"
                                @click.prevent="$refs.photo.click()"
                                class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white/70 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:text-white transition">
                            Change Photo
                        </button>
                        @if ($this->user->profile_photo_path)
                            <button type="button"
                                    wire:click="deleteProfilePhoto"
                                    class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-rose-400 bg-rose-500/5 border border-rose-500/10 rounded-xl hover:bg-rose-500/10 transition">
                                Remove
                            </button>
                        @endif
                    </div>
                </div>

                @error('photo')
                    <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
                @enderror
            </div>
        @endif

        {{-- Name --}}
        <div class="mb-5">
            <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Full Name</label>
            <input id="name" type="text" wire:model="state.name" required autocomplete="name"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
            @error('name')
                <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-6">
            <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-white/30 mb-2">Email Address</label>
            <input id="email" type="email" wire:model="state.email" required autocomplete="username"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-white/20 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition" />
            @error('email')
                <p class="mt-2 text-xs text-rose-400 font-bold">{{ $message }}</p>
            @enderror

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                    <p class="text-xs font-bold text-amber-400">
                        Your email address is unverified.
                        <button type="button" wire:click.prevent="sendEmailVerification" class="underline hover:text-amber-300 transition ml-1">
                            Resend verification email.
                        </button>
                    </p>
                    @if ($this->verificationLinkSent)
                        <p class="mt-1 text-xs font-bold text-emerald-400">Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <x-action-message class="text-emerald-400 text-xs font-bold" on="saved">
                ✓ Profile saved successfully.
            </x-action-message>
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="photo"
                    class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 disabled:opacity-50 disabled:grayscale">
                Save Changes
            </button>
        </div>
    </form>
</div>

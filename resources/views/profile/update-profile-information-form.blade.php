<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Información del perfil') }}</h5>
        
        {{-- Foto de perfil --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="mb-4">
                <label for="photo" class="form-label">{{ __('Foto de perfil') }}</label>
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-circle me-3" width="80" height="80">
                    <div>
                        <input type="file" id="photo" class="form-control" wire:model="photo" accept="image/*">
                        @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                @if ($this->user->profile_photo_path)
                    <button type="button" class="btn btn-outline-danger btn-sm mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Quitar foto') }}
                    </button>
                @endif
            </div>
        @endif

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input id="name" type="text" class="form-control" wire:model.defer="state.name" required autocomplete="name">
            @error('state.name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control" wire:model.defer="state.email" required autocomplete="username">
            @error('state.email') <div class="text-danger">{{ $message }}</div> @enderror

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2 p-2">
                    {{ __('Tu dirección de correo no está verificada.') }}
                    <button type="button" class="btn btn-link p-0 ms-2" wire:click.prevent="sendEmailVerification">
                        {{ __('Haz clic aquí para reenviar el email de verificación.') }}
                    </button>
                </div>
                @if ($this->verificationLinkSent)
                    <div class="alert alert-success mt-2 p-2">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-4 d-flex align-items-center">
            <div wire:loading wire:target="photo" class="me-3">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ __('Guardando...') }}
            </div>
            @if (session()->has('message'))
                <span class="text-success me-3">{{ session('message') }}</span>
            @endif
            <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="photo">
                {{ __('Guardar') }}
            </button>
        </div>
    </div>
</div>
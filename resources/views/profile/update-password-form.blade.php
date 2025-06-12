<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Actualizar contraseña') }}</h5>
        <p class="mb-4 text-muted">{{ __('Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.') }}</p>

        <!-- Contraseña actual -->
        <div class="mb-3">
            <label for="current_password" class="form-label">{{ __('Contraseña actual') }}</label>
            <input id="current_password" type="password" class="form-control" wire:model.defer="state.current_password" autocomplete="current-password">
            @error('state.current_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nueva contraseña -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Nueva contraseña') }}</label>
            <input id="password" type="password" class="form-control" wire:model.defer="state.password" autocomplete="new-password">
            @error('state.password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmar nueva contraseña -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirmar contraseña') }}</label>
            <input id="password_confirmation" type="password" class="form-control" wire:model.defer="state.password_confirmation" autocomplete="new-password">
            @error('state.password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4 d-flex align-items-center">
            @if (session()->has('message'))
                <span class="text-success me-3">{{ session('message') }}</span>
            @endif
            <button class="btn btn-primary">
                {{ __('Guardar') }}
            </button>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Autenticación en dos pasos') }}</h5>
        <p class="card-text text-muted mb-4">
            {{ __('Agrega seguridad adicional a tu cuenta usando la autenticación en dos pasos.') }}
        </p>

        <h6>
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('Finaliza la activación de la autenticación en dos pasos.') }}
                @else
                    {{ __('Has activado la autenticación en dos pasos.') }}
                @endif
            @else
                {{ __('No has activado la autenticación en dos pasos.') }}
            @endif
        </h6>

        <p class="text-muted">
            {{ __('Cuando la autenticación en dos pasos está activada, se te pedirá un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.') }}
        </p>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="alert alert-info mt-4">
                    <strong>
                        @if ($showingConfirmation)
                            {{ __('Para finalizar la activación, escanea el siguiente código QR con tu aplicación de autenticador o introduce la clave de configuración y proporciona el código OTP generado.') }}
                        @else
                            {{ __('La autenticación en dos pasos está activada. Escanea el siguiente código QR con tu aplicación de autenticador o introduce la clave de configuración.') }}
                        @endif
                    </strong>
                </div>

                <div class="bg-white border rounded p-3 my-3 d-inline-block">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="alert alert-secondary mt-3">
                    <strong>{{ __('Clave de configuración') }}:</strong> {{ decrypt($this->user->two_factor_secret) }}
                </div>

                @if ($showingConfirmation)
                    <div class="mb-3">
                        <label for="code" class="form-label">{{ __('Código') }}</label>
                        <input id="code" type="text" name="code" class="form-control w-50" inputmode="numeric" autofocus autocomplete="one-time-code"
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="alert alert-warning mt-4">
                    <strong>
                        {{ __('Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Te permitirán recuperar el acceso si pierdes tu dispositivo de autenticación.') }}
                    </strong>
                </div>
                <div class="bg-light border rounded p-3 font-monospace mb-3">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-4">
            @if (! $this->enabled)
                <button type="button" class="btn btn-primary"
                    wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled">
                    {{ __('Activar') }}
                </button>
            @else
                <div class="d-flex flex-wrap gap-2">
                    @if ($showingRecoveryCodes)
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="regenerateRecoveryCodes" wire:loading.attr="disabled">
                            {{ __('Regenerar códigos de recuperación') }}
                        </button>
                    @elseif ($showingConfirmation)
                        <button type="button" class="btn btn-primary"
                            wire:click="confirmTwoFactorAuthentication" wire:loading.attr="disabled">
                            {{ __('Confirmar') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="showRecoveryCodes" wire:loading.attr="disabled">
                            {{ __('Mostrar códigos de recuperación') }}
                        </button>
                    @endif

                    @if ($showingConfirmation)
                        <button type="button" class="btn btn-outline-danger"
                            wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                            {{ __('Cancelar') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-danger"
                            wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                            {{ __('Desactivar') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

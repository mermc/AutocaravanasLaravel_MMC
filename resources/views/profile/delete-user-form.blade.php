<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title text-danger mb-3">{{ __('Eliminar cuenta') }}</h5>
        <p class="card-text text-muted mb-4">
            {{ __('Elimina permanentemente tu cuenta.') }}
        </p>
        <p class="text-muted">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.') }}
        </p>
        <div class="mt-4">
            <button type="button" class="btn btn-danger"
                wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Eliminar cuenta') }}
            </button>
        </div>

        <!-- Modal de confirmación para eliminar cuenta -->
        @if ($confirmingUserDeletion)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">{{ __('Eliminar cuenta') }}</h5>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ __('¿Estás seguro de que deseas eliminar tu cuenta? Una vez eliminada, todos sus recursos y datos serán borrados permanentemente.') }}
                                <br>
                                {{ __('Introduce tu contraseña para confirmar que quieres eliminar tu cuenta de forma permanente.') }}
                            </p>
                            <input type="password"
                                class="form-control"
                                autocomplete="current-password"
                                placeholder="{{ __('Contraseña') }}"
                                wire:model="password"
                                wire:keydown.enter="deleteUser"
                            >
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('confirmingUserDeletion', false)" wire:loading.attr="disabled">
                                {{ __('Cancelar') }}
                            </button>
                            <button type="button" class="btn btn-danger ms-2"
                                wire:click="deleteUser" wire:loading.attr="disabled">
                                {{ __('Eliminar cuenta') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ __('Sesiones del navegador') }}</h5>
        <p class="card-text text-muted mb-4">
            {{ __('Administra y cierra tus sesiones activas en otros navegadores y dispositivos.') }}
        </p>

        <p class="text-muted">
            {{ __('Si lo necesitas, puedes cerrar todas tus otras sesiones del navegador en todos tus dispositivos. Algunas de tus sesiones recientes se muestran abajo; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, deberías actualizar también tu contraseña.') }}
        </p>

        @if (count($this->sessions) > 0)
            <div class="mb-4">
                @foreach ($this->sessions as $session)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if ($session->agent->isDesktop())
                                {{-- Icono de escritorio --}}
                                <i class="bi bi-pc-display" style="font-size: 2rem; color: #6c757d;"></i>
                            @else
                                {{-- Icono de móvil --}}
                                <i class="bi bi-phone" style="font-size: 2rem; color: #6c757d;"></i>
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold">
                                {{ $session->agent->platform() ? $session->agent->platform() : __('Desconocido') }}
                                -
                                {{ $session->agent->browser() ? $session->agent->browser() : __('Desconocido') }}
                            </div>
                            <div class="text-muted small">
                                {{ $session->ip_address }},
                                @if ($session->is_current_device)
                                    <span class="text-success fw-semibold">{{ __('Este dispositivo') }}</span>
                                @else
                                    {{ __('Última actividad') }} {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex align-items-center mb-2">
            <button type="button" class="btn btn-warning"
                wire:click="confirmLogout" wire:loading.attr="disabled">
                {{ __('Cerrar otras sesiones de navegador') }}
            </button>
            @if (session()->has('loggedOut'))
                <span class="text-success ms-3">{{ __('Hecho.') }}</span>
            @endif
        </div>

        <!-- Modal de confirmación para cerrar otras sesiones -->
        @if ($confirmingLogout)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Cerrar otras sesiones de navegador') }}</h5>
                        </div>
                        <div class="modal-body">
                            <p>{{ __('Por favor, introduce tu contraseña para confirmar que deseas cerrar tus otras sesiones de navegador en todos tus dispositivos.') }}</p>
                            <input type="password"
                                class="form-control"
                                autocomplete="current-password"
                                placeholder="{{ __('Contraseña') }}"
                                wire:model="password"
                                wire:keydown.enter="logoutOtherBrowserSessions"
                            >
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('confirmingLogout', false)" wire:loading.attr="disabled">
                                {{ __('Cancelar') }}
                            </button>
                            <button type="button" class="btn btn-danger"
                                wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                                {{ __('Cerrar otras sesiones de navegador') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

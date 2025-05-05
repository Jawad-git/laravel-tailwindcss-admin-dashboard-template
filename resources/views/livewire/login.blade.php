<div class="container my-auto mt-5">
    <div class="row signin-margin">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">

                <div class="card-body">
                    <form wire:submit='store'>
                        <x-form-field>
                            <x-form-label for="loginEmail">
                                {{ __('auth.email') }}
                            </x-form-label>
                            <x-form-input name="email" id="loginEmail" wire:model.live="email" type="email" />
                            <x-form-error name="email" />
                        </x-form-field>
                        <x-form-field>
                            <x-form-label for="loginPassword">
                                {{ __('auth.password') }}
                            </x-form-label>
                            <x-form-input name="password" id="loginPassword" wire:model.live="password" type="password" />
                            <x-form-error name="password" />
                        </x-form-field>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="/" class="text-sm font-semibold leading-6 text-gray-900">{{ __('auth.cancel') }}</a>
                            <x-form-button>{{ __('auth.login') }}
                            </x-form-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
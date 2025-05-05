<div class="container my-auto">
    <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                        <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">{{ __('auth.change_your_password') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form wire:submit="update" class="text-start">

                        <x-form-field>
                            <x-form-label for="resetPasswordEmail">{{ __('auth.email') }}:</x-form-label>
                            <x-form-input name="email" id="resetPasswordEmail" wire:model.live="email" type="email" />
                            <x-form-error name="email" />
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="resetPasswordPassword">{{ __('auth.password') }}:</x-form-label>
                            <x-form-input name="newPassword" id="resetPasswordNewPassword" wire:model.live="newPassword" type="password" />
                            <x-form-error name="newPassword" />
                        </x-form-field>

                        <x-form-field>
                            <x-form-label for="resetPasswordPasswordConfirmation">{{ __('auth.confirm_password') }}:</x-form-label>
                            <x-form-input name="newPassword_confirmation" id="resetPasswordPasswordConfirmation" wire:model.live="newPassword_confirmation" type="password" />
                            <x-form-error name="newPassword_confirmation" />
                        </x-form-field>

                        <div class="text-center">
                            <x-form-button>{{ __('auth.confirm') }}</x-form-button>
                        </div>
                        <p class="mt-4 text-sm text-center">
                            {{ __('auth.dont_have_an_account') }}
                            <a href="{{ route('register') }}"
                                class="text-primary text-gradient font-weight-bold">{{ __('auth.sign_up') }}</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
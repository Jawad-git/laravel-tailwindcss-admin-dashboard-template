<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div
                        class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                        <div class="card card-plain">
                            <div class="card-header">
                                <h1 class="text-3xl font-weight-bolder mb-8">{{ __('auth.sign_up') }}</h1>
                            </div>
                            <div class="card-body">
                                <form wire:submit="store">
                                    <x-form-field>
                                        <x-form-label for="registerName">{{ __('auth.name') }}:</x-form-label>
                                        <x-form-input name="name" wire:model.live="name" id="registerName" />
                                        <x-form-error name="name" />
                                    </x-form-field>
                                    <x-form-field>
                                        <x-form-label for="registerEmail">{{ __('auth.email') }}:</x-form-label>
                                        <x-form-input name="email" wire:model.live="email" id="registerEmail" />
                                        <x-form-error name="email" />
                                    </x-form-field>
                                    <x-form-field>
                                        <x-form-label for="registerPassword">{{ __('auth.password') }}:</x-form-label>
                                        <x-form-input type="password" name="password" wire:model.live="password" id="registerPassword" />
                                        <x-form-error name="password" />
                                    </x-form-field>
                                    <x-form-field>
                                        <x-form-label for="registerPasswordConfirmation">{{ __('auth.password_confirmation') }}:</x-form-label>
                                        <x-form-input name="password_confirmation" wire:model.live="password_confirmation" id="registerPasswordConfirmation" />
                                        <x-form-error name="password_confirmation" />
                                    </x-form-field>

                                    <!--
                                    This is a checkbox that is always checked when the page loads. It's here so that the terms and conditions link appears in the form.
                                    For some reason, the "I agree the Terms and Conditions" checkbox is not set to required by default in the softUI template.
                                    -->
                                    <!--
                                    <div class="form-check form-check-info text-start ps-0 mt-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault" checked>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I agree the <a href="javascript:;"
                                                class="text-dark font-weight-bolder">Terms and Conditions</a>
                                        </label>
                                    </div>
                                    -->
                                    <div class="text-end">
                                        <x-form-button>{{ __('auth.sign_up') }}</x-form-button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-start mt-2 pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">
                                    {{ __('auth.already_have_an_account') }}
                                    <a href="{{ route('login') }}"
                                        class="text-primary text-gradient font-weight-bold">{{ __('auth.sign_in') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
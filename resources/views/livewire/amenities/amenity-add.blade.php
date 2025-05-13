<div class="container">
    <div class="justify-content-center">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible text-white" role="alert">
            <span class="text-lg">{{ session('success') }}</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible text-white" role="alert">
            <span class="text-lg">
                عفوا!
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                {{ __('messages.Create Amenity') }}
                <span class="float-right mt-3">
                    <span class="d-flex align-items-center">
                        <a class="btn bg-gradient-primary"
                            href="{{ route('amenities') }}">{{ __('messages.Amenities') }}</a>
                    </span>
                </span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="store">

                    @foreach ($languages['data'] as $lang)
                    
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label-multilingual :language="$lang" inputkey="name">
                                    {{ __('messages.' . $lang['name'] . ' Name') }}
                                </x-orion.form-label-multilingual>
                                <x-orion.form-input-multilingual :language="$lang" inputkey="name" objectName="name" />
                                <x-orion.form-error-multilingual :language="$lang" inputkey="name" objectName="name" />
                            </div>
                        </div>
                    </div>

                    @endforeach

                    <x-orion.form-submit>
                        {{ __('messages.Save') }}
                    </x-orion.form-submit>

                </form>
            </div>
        </div>
    </div>
</div>
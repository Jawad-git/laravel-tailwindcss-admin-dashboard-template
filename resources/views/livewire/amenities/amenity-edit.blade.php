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
                {{ __('messages.Edit Amenity') }}
                <span class="float-right mt-3">
                    <span class="d-flex align-items-center">
                        <a class="btn bg-gradient-primary"
                            href="{{ route('amenities') }}">{{ __('messages.Amenities') }}</a>
                    </span>
                </span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="update">

                    @foreach ($languages['data'] as $lang)
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <label for="name.name_{{ $lang['code'] }}"
                                    class="form-control-label">{{ __('messages.' . $lang['name'] . ' Name') }}</label>
                                <div
                                    class="@error('name.name_' . $lang['code']) border border-danger rounded-3 @enderror">

                                    <input wire:model="name.name_{{ $lang['code'] }}"
                                        direction="{{ $lang['direction'] }}" data="{!! $name['name_' . $lang['code']] !!}"
                                        code="{{ $lang['code'] }}" id="name_{{ $lang['code'] }}" prefix="name"
                                        class="form-control border border-2 p-2">
                                </div>

                                @error('name.name_' . $lang['code'])
                                <div class="text-danger">{{ explode('.', $message)[1] }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{--
<div class="d-flex justify-content-between align-items-center mt-4">
    <h5 class="m-0">{{ __('messages.Pages') }}</h5>
                    <button class="btn bg-gradient-primary" wire:click.prevent="addPage">+
                        {{ __('messages.Add More') }}</button>
            </div>

            <hr>

            @foreach ($pages as $index => $page)
            <div class="row  mt-3">
                @foreach ($languages['data'] as $lang)
                <div class="col-md-5 ">
                    <label for="pages.{{ $index }}.name_{{ $lang['code'] }}"
                        class="form-label">
                        {{ __('messages.' . $lang['name'] . ' Name') }}
                    </label>
                    <input type="text"
                        wire:model="pages.{{ $index }}.name_{{ $lang['code'] }}"
                        class="form-control border border-2 p-2">

                    <div class="text-danger">
                        @error('pages.' . $index . '.name_' . $lang['code'])
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                @endforeach


                <div class="col-md-1 " style="margin-top: 2.1rem">
                    <button class="btn btn-danger " style=" font-size: 0.85rem !important; "
                        wire:click.prevent="removePage({{ $index }})">X</button>
                </div>




            </div>
            @endforeach

            --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
                    {{ __('messages.Save') }}</button>
            </div>

            </form>
        </div>
    </div>
</div>
</div>
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
                {{ __('messages.Manage Social Media') }}
            </div>
            <div class="card-body">
                <form wire:submit.prevent="update">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <button class="btn bg-gradient-primary w-100" wire:click.prevent="addContactInfo">
                                {{__("messages.Add Social Media")}}
                            </button>
                        </div>
                    </div>

                    @foreach($contactInfos as $index => $info)
                    <div class="row mt-3 align-items-end">
                        <div class="col-md-3">
                            <x-orion.form-label inputkey="contactInfos.{{ $index }}.type">{{__("messages.Social Media")}}</x-orion.form-label>
                            <select wire:model.live="contactInfos.{{ $index }}.type"
                                class="form-select border border-2 p-2">

                                <option value="">{{__("messages.Select Type")}}</option>
                                @foreach ( $socialMedias as $socialMedia)
                                <option value="{{$socialMedia->name}}">{{ __('messages.' . $socialMedia->name) }}</option>
                                @endforeach
                            </select>

                            @error("contactInfos.{$index}.type")
                            <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-2">
                            <x-orion.form-label inputkey="contactInfos.{{ $index }}.language">{{__("messages.Language")}}</x-orion.form-label>
                            <select wire:model.live="contactInfos.{{ $index }}.language"
                                class="form-select border border-2 p-2">
                                <option value="">{{__("messages.Select Language")}}</option>
                                <option value="en">{{__('messages.English')}}</option>
                                <option value="ar">{{__('messages.Arabic')}}</option>
                                <option value="fa">{{__('messages.Farsi')}}</option>
                            </select>
                            <x-orion.form-error inputkey="contactInfos.{{ $index }}.language" />
                        </div>

                        @if($info['type'] && $info['language'])
                        <div class="col-md-5">
                            <x-form-label inputkey="contactInfos.{{ $index }}.language">{{ __('messages.' . $info['type'] . ' ' . $info['language']) }} {{ $this->getTypeNumber($index, $info['type'] ?? '', $info['language'] ?? '') }}</x-form-label>
                            <x-orion.form-input dir="{{ $info['language'] != 'en' ? 'rtl' : 'ltr' }}" inputkey="contactInfos.{{ $index }}.value" placeholder="{{__('messages.Enter ' . $info['type'])}}" />
                            <x-orion.form-error inputkey="contactInfos.{{ $index }}.value" />
                        </div>
                        @endif

                        <div class="col-md-2">
                            @if($index > 0)
                            <button class="btn btn-danger w-100 mb-1"
                                wire:click.prevent="removeContactInfo({{ $index }})">
                                {{__("messages.Remove")}}
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    {{--
                    @foreach ( $socialMedias as $socialMedia)
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="values.{{ $socialMedia->name }}">
                    {{ __('messages.' . $socialMedia->name) }}
                    </x-orion.form-label>
                    <x-orion.form-input placeholder="fweugwef" inputkey="values.{{ $socialMedia->name }}" />
                    <x-orion.form-error inputkey="values.{{ $socialMedia->name }}" />
            </div>
        </div>
    </div>

    @endforeach

    --}}

    @can('social-edit')
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
            {{ __('messages.Save') }}</button>
    </div>
    @endcan

    </form>
</div>
</div>
</div>
</div>


{{--

<h4>Contact Info</h4>

<div class="row mt-3">
    <div class="col-md-3">
        <button class="btn btn-success w-100" wire:click.prevent="addContactInfo">
            Add Contact Info
        </button>
    </div>
</div>

@foreach($contactInfos as $index => $info)
<div class="row mt-3 align-items-end">
    <div class="col-md-3">
        <select wire:model.live="contactInfos.{{ $index }}.type"
class="form-select border border-2 p-2">
<option value="">Select Type</option>
<option value="phone">Phone</option>
<option value="whatsapp">WhatsApp</option>
</select>
@error("contactInfos.{$index}.type")
<div class="text-danger">{{ $message }}</div> @enderror
</div>

@if($info['type'])
<div class="col-md-7">
    <label>
        @if($info['type'] === 'phone')
        Phone {{ $this->getPhoneLabel($index) }}
        @else
        {{ ucfirst($info['type']) }}
        @endif
    </label>
    <input wire:model.live="contactInfos.{{ $index }}.value"
        type="text"
        class="form-control border border-2 p-2"
        placeholder="Enter {{ $info['type'] }}">
    @error("contactInfos.{$index}.value")
    <div class="text-danger">{{ $message }}</div> @enderror
</div>
@endif

<div class="col-md-2">
    @if($index > 0)
    <button class="btn btn-danger w-100 mb-1"
        wire:click.prevent="removeContactInfo({{ $index }})">
        Remove
    </button>
    @endif
</div>
</div>
@endforeach
--}}
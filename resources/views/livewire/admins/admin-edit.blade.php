<div class="main-content">
    <div class="container">
        <div class="justify-content-center">
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible text-white" role="alert">
                <span class="text-lg">{{ session('success') }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if (count($errors) > 0)
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
                    {{ __('messages.Edit Admin') }}
                    <span class="float-right mt-3">
                        <span class="d-flex align-items-center">
                            <a class="btn bg-gradient-primary"
                                href="{{ route('admins') }}">{{ __('messages.Admins') }}</a>
                        </span>
                    </span>
                </div>
                <div class="card-body">
                    <div>
                        <div class=" col-md-12">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                    <h6 class="text-white text-capitalize pe-3 m-3">{{ __('messages.Personal Info') }}</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12 col-md-12 mt-3">
                                        <label for="name">{{ __('messages.Name') }}</label>
                                        <input wire:model="name" type="name" class="form-control border border-2 p-2"
                                            id="name" placeholder="{{ __('messages.Name') }}" value>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-12 mt-3">
                                        <label for="email">{{ __('messages.Email') }}</label>
                                        <input wire:model="email" type="email"
                                            class="form-control border border-2 p-2" id="email"
                                            placeholder="{{ __('messages.Email') }}" value>
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-12 mt-3">
                                            <label for="phoneNumber">{{ __('messages.Phone') }}</label>
                                            <x-tel-input name="phone" value="{{ old('phone') }}"
                                                id="phoneNumber" label="{{ __('messages.Phone') }}"
                                                placeholder="{{ __('messages.Phone') }}"
                                                countryIdField="phone_country_id" :selectedCountry="$selectedCountry" :error="$errors->first('phone')"
                                                :countries="$countries" />
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-12 mt-3">
                                        <label for="address">{{ __('messages.Address') }}</label>
                                        <input wire:model="address" type="text"
                                            class="form-control border border-2 p-2" id="address"
                                            placeholder="{{ __('messages.Address') }}" value>
                                        @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group col-12 col-md-12 mt-3">
                                        <div class="form-group ">
                                            <label for="expiry_date">تاريخ انتهاء الاشتراك</label>
                                            <input wire:model="expiry_date" type="date"
                                                class="form-control border border-2 p-2" id="expiry_date"
                                                placeholder="تاريخ انتهاء الاشتراك" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    @error('expiry_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="form-group col-12 col-md-12 mt-3">
                                <label for="selectedrole">{{ __('messages.Roles') }}</label>
                                <select wire:model="selectedrole" class="form-select border border-2 ps-2"
                                    data-style="select-with-transition" title data-size="100" id="role">
                                    <option selected>{{ __('messages.Select') }}</option>
                                    @foreach ($roles as $key => $r)
                                    <option value="{{ $key }}">{{ $r }}</option>
                                    @endforeach
                                </select>
                                @error('selectedrole')
                                <div class="text-danger">The role field is required.</div>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6 mt-3">

                                <label for="password">{{ __('messages.Password') }}</label>
                                <input wire:model="password" type="password"
                                    class="form-control border border-2 p-2" id="password"
                                    placeholder="{{ __('messages.Password') }}" value>

                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6 mt-3">
                                <label for="password_confirmation">{{ __('messages.Confirm Password') }}</label>
                                <input wire:model="password_confirmation" type="password"
                                    class="form-control border border-2 p-2" id="password_confirmation"
                                    placeholder="{{ __('messages.Confirm Password') }}" value>
                                @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button type="submit" wire:click="store" wire:loading.attr="disabled" wire:target="store"
                    class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('messages.Save') }}</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
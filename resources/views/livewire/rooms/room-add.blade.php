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
                {{ __('messages.Create Room') }}
                <span class="float-right mt-3">
                    <span class="d-flex align-items-center">
                        <a class="btn bg-gradient-primary"
                            href="{{ route('rooms') }}">{{ __('messages.Rooms') }}</a>
                    </span>
                </span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="store">


                    <div class="row ">
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="number">
                                    {{ __('messages.Number') }}
                                </x-orion.form-label>
                                <x-orion.form-input type="number" :language="$language" inputkey="number" />
                                <x-orion.form-error inputkey="number" />
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="floor">
                                    {{ __('messages.Floor') }}
                                </x-orion.form-label>
                                <x-orion.form-input type="number" inputkey="floor" />
                                <x-orion.form-error inputkey="floor" />
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="bedCount">
                                    {{ __('messages.Bed count') }}
                                </x-orion.form-label>
                                <x-orion.form-input type="number" inputkey="bedCount" />
                                <x-orion.form-error inputkey="bedCount" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="capacity">
                                    {{ __('messages.Capacity') }}
                                </x-orion.form-label>
                                <x-orion.form-input inputkey="capacity" />
                                <x-orion.form-error inputkey="capacity" />
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="pricePerNight">
                                    {{ __('messages.Price per night') }}
                                </x-orion.form-label>
                                <x-orion.form-input inputkey="pricePerNight" />
                                <x-orion.form-error inputkey="pricePerNight" />
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="size">
                                    {{ __('messages.Size/Area') }}
                                </x-orion.form-label>
                                <x-orion.form-input inputkey="size" />
                                <x-orion.form-error inputkey="size" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="occupationStartDate">
                                    {{ __('messages.Occupation start date') }}
                                </x-orion.form-label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input
                                        type="date"
                                        id="occupationStartDate"
                                        wire:model="occupationStartDate"
                                        class="form-control" />
                                </div> <x-orion.form-error inputkey="occupationStartDate" />
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="occupationEndDate">
                                    {{ __('messages.Occupation end date') }}
                                </x-orion.form-label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input
                                        type="date"
                                        id="occupationEndDate"
                                        wire:model="occupationEndDate"
                                        class="form-control" />
                                </div>
                                <x-orion.form-error inputkey="occupationEndDate" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        @foreach ($languages['data'] as $lang)
                        <div class="col-md-6 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label-multilingual :language="$lang" inputkey="view">
                                    {{ __('messages.' . $lang['name'] . ' View') }}
                                </x-orion.form-label-multilingual>
                                <x-orion.form-input-multilingual objectName="views" :language="$lang" inputkey="view" />
                                <x-orion.form-error-multilingual objectName="views" :language="$lang" inputkey="view" />
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label-multilingual :language="$lang" inputkey="description">
                                    {{ __('messages.' . $lang['name'] . ' Description') }}
                                </x-orion.form-label-multilingual>
                                <x-orion.form-input-multilingual objectName="descriptions" :language="$lang" inputkey="description" />
                                <x-orion.form-error-multilingual objectName="descriptions" :language="$lang" inputkey="description" />
                            </div>
                        </div>
                        @endforeach
                        <div class="row ">
                            <div class="col-md-4 ">
                                <div class="form-group mt-4">
                                    <div class="form-group mt-4">
                                        <x-orion.form-label inputkey="selectedCategory">{{ __("messages.Category") }}</x-orion.form-label>
                                        <x-orion.form-select-selectize defaultOption="Select Category" inputkey="selectedCategory" :options="$categoryOptions" class="selectizeCategory" />
                                        <x-orion.form-error inputkey="selectedCategory" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 ">
                                <div class="form-group mt-4">
                                    <x-orion.form-label inputkey="selectedAmenities">
                                        {{ __('messages.Amenities') }}
                                    </x-orion.form-label>
                                    <x-orion.form-select-selectize multiple defaultOption="Select Amenities" inputkey="selectedAmenities" :options="$amenityOptions" class="selectizeAmenities" />
                                    
                                    <x-orion.form-error inputkey="selectedAmenities" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <x-orion.form-submit>
                        {{ __('messages.Save') }}
                    </x-orion.form-submit>

                </form>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    document.addEventListener('livewire:init', () => {
        const selectizeInstances = {};

        function initializeSelectize({
            selector,
            livewireEvent,
            valueField = 'id',
            labelField = 'name',
            searchField = 'name',
            options = [],
            selected = null,
            clearOption = false,
            maxItems = null,
        }) {
            let $select = $(selector);
            if ($select[0]?.selectize) {
                $select[0].selectize.destroy();
            }

            $select.selectize({
                persist: false,
                plugins: ['remove_button', 'clear_button'],
                valueField,
                labelField,
                searchField,
                maxItems,
                create: false,
                placeholder: 'Select materials...',
                onChange: function(values) {
                    Livewire.dispatch(livewireEvent, [values]);
                    console.log(values);
                },
                onInitialize: function() {
                    selectizeInstances[selector] = this;

                    if (clearOption) {
                        this.clear();
                        this.clearOptions();
                    }

                    if (options && options.length) {
                        options.forEach(option => {
                            this.addOption(option);
                        });
                    }

                    this.refreshOptions(false);

                    if (selected && selected.length) {
                        this.setValue(selected);
                    }
                }

            });
        }

        initializeSelectize({
            selector: '.selectizeCategory',
            livewireEvent: 'categorySelectize',
            maxItems: 1,
        });

        initializeSelectize({
            selector: '.selectizeAmenities',
            livewireEvent: 'amenitySelectize',
        });

    });
</script>


@endpush

{{--
    category -> 1 image
    room -> multiple images
    food -> multiple images
    change menu to MenuCategory
    
--}}
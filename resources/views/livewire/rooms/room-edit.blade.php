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
                {{ __('messages.Create Food') }}
                <span class="float-right mt-3">
                    <span class="d-flex align-items-center">
                        <a class="btn bg-gradient-primary"
                            href="{{ route('foods') }}">{{ __('messages.Foods') }}</a>
                    </span>
                </span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="update">

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
                                    <div class="form-group mt-4" wire:ignore>
                                        <x-orion.form-label inputkey="selectedCategory">{{ __("messages.Category") }}</x-orion.form-label>
                                        <x-orion.form-select-selectize defaultOption="Select Category" inputkey="selectedCategory" :options="$categoryOptions" class="selectizeCategory" />
                                        <x-orion.form-error inputkey="selectedCategory" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 ">
                                <div class="form-group mt-4" wire:ignore>
                                    <x-orion.form-label inputkey="selectedAmenities">
                                        {{ __('messages.Amenities') }}
                                    </x-orion.form-label>
                                    <x-orion.form-select-selectize multiple defaultOption="Select Amenities" inputkey="selectedAmenities" :options="$amenityOptions" class="selectizeAmenities" />
                                    <x-orion.form-error inputkey="selectedAmenities" />
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-9 mt-4">
                                <livewire:dropzone
                                    wire:model="photos"
                                    :oldDocs="$oldDocs"
                                    :rules="['image','mimes:png,jpeg','max:10420']"
                                    :multiple="true" />
                            </div>
                            <div class="col-md-3 ms-auto d-flex align-items-end justify-content-start gap-2 ">
                                <div class="form-check form-switch h-100 w-100 d-flex justify-content-center">
                                    <div class="gap-1 mt-6">

                                        <label for="isAvailableRoomAdd">Available?</label>
                                        <input class="form-check-input "
                                            id="isAvailableRoomAdd"
                                            {{ $isAvailable ? 'checked' : '' }}
                                            type="checkbox"
                                            role="switch"
                                            wire:click="toggleAvailability()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group mt-4">
                                    <div wire:ignore
                                        class="@error('paths') border border-danger rounded-3 @enderror">
                                        <label for="exampleInputName">{{ __('messages.Image') }}:</label>
                        <input type="file" class="form-control border border-2 p-2 file-input-with-hidden-text"
                            id="exampleInputName" wire:model="paths" multiple placeholder="{{ $fileCount . 'files chosen' }}">
                    </div>
                    @error('paths')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
            </div>
        </div>
        <div class="col-md-3 ms-auto d-flex align-items-end justify-content-center gap-2 ">
            <div class="form-check mt-4">
                <label class="form-check-label role-check-label me-2"
                    for="isAvailable">
                    {{ __("messages.Available?") }}
                </label>
                <input wire:model="isAvailable"
                    class="form-check-input" type="checkbox" value=""
                    {{ $isAvailable ? 'checked' : '' }}
                    id="isAvailable">
            </div>
        </div>
    </div>

    @if ($paths)
    <div class="d-flex flex-wrap justify-content-start align-items-center mt-1 gap-2">
        @foreach ($paths as $index=> $path)

        @if (!is_string($path))
        <div class="position-relative">
            <img src="{{ $path->temporaryUrl() }}" width="100"
                height="100" class="rounded-circle">
            <button type="button"
                class="btn btn-danger btn-sm position-absolute top-0 end-0"
                wire:click="removeImage()">X</button>
        </div>
        @else
        <div class="position-relative">
            <img src="{{ asset('storage/' . $path) }}" width="100"
                height="100" class="rounded-circle">
            <button type="button"
                class="btn btn-danger btn-sm position-absolute top-0 end-0"
                wire:click="removeImage({{ $index }})">X</button>
        </div>
        @endif
        @endforeach
    </div>
    @endif

</div>

--}}
</div>
<div class="d-flex justify-content-end">
    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">
        {{ __('messages.Save') }}</button>
</div>

</form>
</div>
</div>
</div>
</div>

@push('js')
<script>
    document.addEventListener('livewire:init', () => {
        const selectedCategory = @json($selectedCategory);
        const selectedAmenities = @json($selectedAmenities);


        const selectizeInstances = {};

        function initializeSelectize({
            selector,
            livewireEvent,
            valueField = 'id',
            labelField = 'name',
            searchField = 'name',
            options = [],
            selected = null,
            clearOption = false
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
                maxItems: null,
                allowEmptyOption: true,
                showEmptyOptionInDropdown: true,
                create: false,
                placeholder: 'Select menu...',
                onChange: function(values) {
                    const visibleSelectedItems = this.items;
                    Livewire.dispatch(livewireEvent, [visibleSelectedItems]);
                    console.log(visibleSelectedItems); // e.g., ['2', '4']
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

                    if (selected) {
                        this.setValue(selected);
                    }
                }
            });
        }

        initializeSelectize({
            selector: '.selectizeAmenities',
            livewireEvent: 'amenitySelectize',
            selected: selectedAmenities,
        });

        initializeSelectize({
            selector: '.selectizeCategory',
            livewireEvent: 'categorySelectize',
            maxItems: 1,
            selected: selectedCategory,
        });
    });
</script>
@endpush
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
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label inputkey="price">{{ __("messages.Price") }}</x-orion.form-label>
                                <x-orion.form-input type="number" inputkey="price" />
                                <x-orion.form-error inputkey="price" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4" wire:ignore>
                                <x-orion.form-label inputkey="selectedMenu">{{ __("messages.Menu") }}</x-orion.form-label>
                                <x-orion.form-select-selectize placeholder="choose an element" inputkey="selectedMenu" :options="$menuOptions" class="selectizeMenus" />
                                <x-orion.form-error inputkey="selectedMenu" />
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
                maxItems: 1,
                allowEmptyOption: true,
                showEmptyOptionInDropdown: true,
                create: false,
                placeholder: 'Select materials...',
                onChange: function(values) {
                    //console.log('Dispatching:', livewireEvent, [values]);
                    Livewire.dispatch(livewireEvent, [values]);
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
            selector: '.selectizeMenus',
            livewireEvent: 'menuSelectize',
        });

    });
</script>


@endpush
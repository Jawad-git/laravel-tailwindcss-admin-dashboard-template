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
                {{ __('messages.Manage About-us section') }}
            </div>
            <div class="card-body">
                <form wire:submit.prevent="update">

                    @foreach ($languages['data'] as $lang)
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <x-orion.form-label-multilingual :language="$lang" inputkey="description">
                                    {{ __('messages.' . $lang['name'] . ' Description') }}
                                </x-orion.form-label-multilingual>
                                <x-orion.form-input-multilingual :language="$lang" inputkey="description" objectName="descriptions" />
                                <x-orion.form-error-multilingual :language="$lang" inputkey="description" objectName="descriptions" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="form-group mt-4">
                                <livewire:dropzone
                                    wire:model="photos"
                                    :oldDocs="$oldDocs"
                                    :rules="['image','mimes:png,jpeg','max:10420']"
                                    :multiple="true" />
                            </div>
                        </div>
                    </div>
                    @can('about-edit')
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

@push('js')
<script>
    document.addEventListener('livewire:init', () => {
        const selectedMenu = @json($selectedMenu);

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
                placeholder: 'Select menu...',
                onChange: function(values) {
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

                    if (selected) {
                        this.setValue(selected);
                    }
                }
            });
        }

        initializeSelectize({
            selector: '.selectizeMenus',
            livewireEvent: 'menuSelectize',
            selected: selectedMenu,
        });
    });
</script>
@endpush
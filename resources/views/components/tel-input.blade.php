@props([
'name' => 'phone',
'value' => '',
'id' => 'phoneNumber',
'label' => 'Phone',
'placeholder' => 'Enter phone number',
'countryIdField' => 'phone_country_id',
'selectedCountry' => '',
'countries' => [],
'error'=>''
])

<div class="row">
    <div class="col-12 col-md-3" wire:ignore>
        <select name="{{ $countryIdField }}" id="countryCode" class="form-select " wire:model="selectedCountry">
            @foreach ($countries as $country)
            <option value="{{ $country['id'] }}" data-flag="{{ asset($country['flag']) }}"
                data-country-name="{{ $country['name'] }}" @if ($selectedCountry==$country['id']) selected @endif>
                {{ str_contains($country['phonecode'], '+') ? $country['phonecode'] : '+' . $country['phonecode'] }}
                - {{ $country['name'] }}
            </option>
            @endforeach
        </select>

    </div>


    <div class="col-12 col-md-9">
        <input name="{{ $name }}" value="{{ $value }}" type="text"
            class="form-control border border-2 p-2" id="{{ $id }}" placeholder="{{ $placeholder }}"
            wire:model="phone">

        <div class="text-danger">
            @if ($error!='') {{ $error }} @endif
        </div>


    </div>
</div>


<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

<style>
    .selectize-dropdown .selectize-dropdown-content div {
        display: flex;
        align-items: center;
        padding: 8px;
        font-size: 18px;
    }


    .selectize-dropdown img,
    .selectize-input img {
        width: 24px;
        height: 16px;
        margin-right: 8px;
        border-radius: 3px;
        border: 1px solid #ddd;
    }

    .selectize-input {
        display: flex !important;
        align-items: center;
        border: 1px solid #dee2e6 !important;
        border-width: 2px !important;
        border-radius: 0.375rem;
        font-size: 18px;
        padding: 8px 12px;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectElement = $('#countryCode');

        let selectizeInstance = selectElement.selectize({
            render: {
                option: function(data, escape) {
                    let flagUrl = escape(data.flag);
                    let countryCode = escape(data.text);

                    return `<div style="display: flex; align-items: center;">
                                <img src="${flagUrl}" width="24" height="16">
                                <span style="margin-left: 10px; font-size: 18px;">${countryCode}</span>
                            </div>`;
                },
                item: function(data, escape) {
                    let flagUrl = escape(data.flag);
                    let countryCode = escape(data.text);

                    return `<div style="display: flex; align-items: center;">
                                <img src="${flagUrl}" width="24" height="16">
                                <span style="margin-left: 10px; font-size: 18px;">${countryCode}</span>
                            </div>`;

                },


            },
            searchField: ['text', 'name'],
            allowEmptyOption: false,
            onChange: function(value) {
                @this.set('selectedCountry', value)
            },
            onInitialize: function() {
                let selectize = this;
                setTimeout(function() {
                    let selectedCountry = "{{ $selectedCountry }}";

                    selectize.setValue(selectedCountry, true); // Set default value to 121
                    @this.set('selectedCountry', selectedCountry); // Sync with Livewire
                }, 500);
            }

        });



    });
</script>
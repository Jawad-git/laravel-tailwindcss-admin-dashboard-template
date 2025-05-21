@props(['inputkey'])

<label {{ $attributes->merge(attributeDefaults: ['class' => 'form-control-label']) }} for="{{ $inputkey }}">
    {{ $slot }}
</label>
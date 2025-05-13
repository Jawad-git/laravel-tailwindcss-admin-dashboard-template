@props([
'inputkey',
'options',
'defaultOption' => 'Select an option',
'valueField' => 'id',
'labelField' => 'name'
])


<select wire:model="{{ $inputkey }}"
    id="{{ $inputkey }}"
    {{ $attributes->merge(attributeDefaults: ['class' => '']) }}>
    <option value="">Select Category</option>
    @foreach($options as $option)
    <option value="{{ $option[$valueField] }}">{{ $option[$labelField] }}</option>
    @endforeach
</select>
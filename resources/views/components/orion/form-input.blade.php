@props(['inputkey', 'disabled' => false])

<div class="@error($inputkey) border border-danger rounded-3 @enderror">
    <input
        {{ $disabled ? 'disabled' : '' }}
        wire:model="{{$inputkey}}"
        {{ $attributes->merge(attributeDefaults: ['class' => 'form-control border border-2 p-2']) }}
        data="{!! $inputkey ?? '' !!}"
        id="{{ $inputkey }}"
        prefix="{{ $inputkey }}">
</div>
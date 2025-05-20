@props(['inputkey', 'objectName', 'isTextarea' => false])

<div class="@error($objectName . '.' . $inputkey) border border-danger rounded-3 @enderror">
    <input
        wire:model="{{$objectName . '.' . $inputkey}}"
        {{ $attributes->merge(attributeDefaults: ['class' => 'form-control border border-2 p-2']) }}
        data="{!! $objectName[$inputkey] ?? '' !!}"
        id="{{ $objectName . '.' . $inputkey }}"
        prefix="{{ $inputkey }}">
</div>
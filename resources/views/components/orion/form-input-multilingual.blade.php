@props(['language', 'inputkey', 'objectName', 'isTextarea' => false])
@php
$key = $inputkey . '_' . $language['code'];
@endphp

@if ($isTextarea == false)
<div class="@error($objectName . '.' . $inputkey . '_' . $language['code']) border border-danger rounded-3 @enderror">
    <input
        wire:model="{{$objectName . '.' . $inputkey . '_' . $language['code'] }}"
        {{ $attributes->merge(attributeDefaults: ['class' => 'form-control border border-2 p-2']) }}
        direction="{{ $language['direction'] }}"
        data="{!! $objectName[$key] ?? '' !!}"
        code="{{ $language['code'] }}"
        id="{{ $key }}"
        prefix="{{ $inputkey }}">
</div>

@else
<flux:textarea />
@endif
{{-- -
<div class="@error($objectName . '.' . $inputkey . '_' . $language['code']) border border-danger rounded-3 @enderror">
    <flux:textarea
        wire:model="{{$objectName . '.' . $inputkey . '_' . $language['code'] }}"
{{ $attributes->merge(attributeDefaults: ['class' => 'form-control border border-2 p-2']) }}
direction="{{ $language['direction'] }}"
data="{!! $objectName[$key] ?? '' !!}"
code="{{ $language['code'] }}"
id="{{ $key }}"
prefix="{{ $inputkey }}">
</div>


===========================================
wire:model="name.name_{{ $lang['code'] }}"
<input
    direction="{{ $language['direction'] }}" data="{!! $objectName[ {{ $inputkey }} . '_' . $language['code']] !!}"
    code="{{ $language['code'] }}" id="{{ $inputkey . '_' . $language['code'] }}" prefix={{$inputkey}}
    class="form-control border border-2 p-2">
--}}

{{-- objectName is the master object array..
    $input key is the key for the input, meaning, what are we entering? room description? its status? name?
    $language is always $lang.

    example, we have a master array called amenities, its input key mixed with lang is the key to the value
    description["description" . "en"] = "hello welcome to etc... "
--}}
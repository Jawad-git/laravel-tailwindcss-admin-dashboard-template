@props(['objectName', 'inputkey'])

@php
$key = $objectName . '.' . $inputkey;
@endphp


<label for="{{ $key }}" class="form-control-label">{{ $slot }}</label>
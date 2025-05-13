@props(['language', 'inputkey'])

@php
$key = $inputkey . '_' . $language['code'];
@endphp


<label for="{{ $key }}" class="form-control-label">{{ $slot }}</label>
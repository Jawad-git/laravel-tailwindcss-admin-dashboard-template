@props(['language', 'inputkey', 'objectName'])

@php
$key = $inputkey . '_' . $language['code'];
@endphp

@error($objectName . '.' . $inputkey . '_' . $language['code'])
<div class="text-danger">{{ explode('.', $message)[1] }}</div>
@enderror
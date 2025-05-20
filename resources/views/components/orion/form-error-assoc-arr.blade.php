@props(['inputkey', 'objectName'])

@php
$key = $objectName . '.' . $inputkey;
@endphp

@error($objectName . '.' . $inputkey)
<div class="text-danger">{{ explode('.', $message)[1] }}</div>
@enderror
@props(['inputkey'])

@error($inputkey)
<div class="text-danger">{{ explode('.', $message)[1] }}</div>
@enderror
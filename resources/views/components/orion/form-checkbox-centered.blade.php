@props(['inputkey', 'label', 'functionName' => '', 'functionArguments' => ''])

<div class="ms-auto d-flex ">
    <div class="form-check form-switch h-100 w-100 d-flex justify-content-center">
        <div class="gap-1 mt-6">

            <label for="{{ $inputkey }}">{{ $label }}</label>
            <input class="form-check-input "
                id="{{ $inputkey }}"
                type="checkbox"
                role="switch"
                wire:click="{{ $functionName }}{{ $functionArguments ? "($functionArguments)" : '()' }}">
        </div>
    </div>
</div>
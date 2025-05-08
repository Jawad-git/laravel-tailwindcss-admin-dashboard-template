<a {{ $attributes->merge(attributeDefaults: ['class' => 'btn bg-gradient-dark mb-0']) }}>
    <i class="material-icons text-sm">add</i> {{ $slot }}
</a>
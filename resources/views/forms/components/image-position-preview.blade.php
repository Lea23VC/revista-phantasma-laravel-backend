<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        @if (isset($getState()['featuredImage']))
        <div class="aspect-square md:aspect-auto overflow-hidden group">
            <div class="relative p-6" style="height: {{ $getState()['type'] == 'preview' ? '448px' : '180px' }}">
                <img src=" {{ $getState()['featuredImage'] }}"
                    style="object-position: {{ str_replace('-', ' ',$getState()['imagePosition']) }};"
                    alt="Image preview"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-125 transition duration-500 cursor-pointer" />
            </div>
        </div>
        @endif

    </div>
</x-dynamic-component>
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">

        <div class="items-center mt-2">
            <label for="position" class="block text-sm font-medium text-gray-700">{{ __('Position')
                }}</label>
            <select id="position" name="position" x-model="state"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="left">{{ __('Left') }}</option>
                <option value="center">{{ __('Center') }}</option>
                <option value="right">{{ __('Right') }}</option>
            </select>
        </div>

</x-dynamic-component>
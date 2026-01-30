@props([
    'options' => [],
    'modelKey' => 'value',
    'label' => '',
    'placeholder' => '',
    'selectPlaceholder' => 'Seleccionar',
])

@php
    $inputId = 'input-select-' . $modelKey;
    $selectId = 'select-' . $modelKey;
@endphp

<div x-data="{ modelKey: @js($modelKey) }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    @endif
    <input
        type="text"
        id="{{ $inputId }}"
        name="{{ $modelKey }}"
        wire:model.live="{{ $modelKey }}"
        placeholder="{{ $placeholder }}"
        class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2"
    >
    <select
        id="{{ $selectId }}"
        @change="if (typeof $wire !== 'undefined') $wire.set(modelKey, $event.target.value)"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500"
    >
        <option value="">{{ $selectPlaceholder }}</option>
        @foreach($options as $option)
            @php
                $value = is_object($option) ? ($option->name ?? $option->title ?? $option->model ?? (string)($option->volt_measurement ?? $option->amperage_measurement ?? '')) : ($option['name'] ?? $option['title'] ?? $option['model'] ?? '');
                $text = is_object($option) ? ($option->name ?? $option->title ?? $option->model ?? (string)($option->volt_measurement ?? $option->amperage_measurement ?? '')) : ($option['name'] ?? $option['title'] ?? $option['model'] ?? '');
            @endphp
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
</div>

@props([
    'id',
    'label' => 'Search',
    'name' => 'q',
    'value' => '',
    'placeholder' => 'Search...',
])

<div class="space-y-2">
    <label for="{{ $id }}" class="text-sm font-semibold text-gray-700">{{ $label }}</label>
    <div class="relative">
        <input
            type="text"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            id="{{ $id }}"
            placeholder="{{ $placeholder }}"
            class="crm-search-input w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
        />
        <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
    </div>
</div>

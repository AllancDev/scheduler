@props([
    'disabled' => false,
    'id' => null,
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => null
])

<input 
    @disabled($disabled) 
    id="{{ $id }}"
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    @if($required) required @endif
    @if($autofocus) autofocus @endif
    @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    {{ $attributes->class('w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition') }}
>

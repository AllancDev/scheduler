@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full border-gray-300 focus:border-gray-400 focus:ring-gray-400 rounded-md shadow-sm']) !!}>
    {{ $slot }}
</select> 
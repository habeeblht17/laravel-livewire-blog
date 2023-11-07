@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-3 border-l-2 border-purple-400 bg-gray-100 rounded-lg  text-normal font-medium leading-5 text-gray-700 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-3 border-l-2 border-transparent rounded-lg text-normal font-medium leading-5 text-gray-400 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

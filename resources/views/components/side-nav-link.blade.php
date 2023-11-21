@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-3 border-l-2 border-purple-950 bg-gray-100 rounded-lg  text-normal font-medium leading-5 text-purple-950 focus:outline-none focus:border-purple-900 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-3 border-l-2 border-transparent rounded-lg text-normal font-medium leading-5 text-purple-700 hover:text-purple-800 hover:bg-gray-50 hover:border-purple-950 focus:outline-none focus:text-purple-800 focus:border-purple-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

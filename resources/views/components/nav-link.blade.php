@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-purple-950 text-sm font-medium leading-5 text-purple-950 focus:outline-none focus:border-purple-950 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-purple-800 hover:text-purple-700 hover:border-purple-700 focus:outline-none focus:text-purple-700 focus:border-purple-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

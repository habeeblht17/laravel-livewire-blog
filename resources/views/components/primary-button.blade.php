<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-purple-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800 focus:bg-purple-950 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-950 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

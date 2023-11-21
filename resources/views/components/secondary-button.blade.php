<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-purple-500 rounded-md font-semibold text-xs text-purple-950 uppercase tracking-widest shadow-sm hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-800 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

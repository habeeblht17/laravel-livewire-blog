<div >
    <nav class="h-screen w-64 bg-white">
        <!--Logo-->
        <div class="mb-5 py-5 shadow-sm shadow-gray-100">
            <div class="flex items-center justify-center">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>

        </div>

        <!--  Side Nav links-->
        <div class="flex flex-col space-y-2 px-2">

            <x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-side-nav-link>
            <x-side-nav-link :href="route('category')" :active="request()->routeIs('category')" wire:navigate>
                {{ __('Category') }}
            </x-side-nav-link>
            <x-side-nav-link :href="route('post')" :active="request()->routeIs('post')" wire:navigate>
                {{ __('Post') }}
            </x-side-nav-link>

        </div>
    </nav>

</div>

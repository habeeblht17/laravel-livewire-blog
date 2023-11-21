<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function logout(): void
    {
        auth()->guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-purple-50 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-purple-950" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('blog')" :active="request()->routeIs('blog')" wire:navigate>
                        {{ __('Blog') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex space-x-2 sm:top-0 sm:right-0 p-6 text-right">
                <!-- Search -->
                <div>
                    <form action="" class="relative mx-auto -mt-1">
                        <input type="search"
                            class="peer cursor-pointer relative z-10 h-8 w-8 rounded-full border border-purple-950 bg-transparent pr-4 outline-none focus:w-full focus:cursor-text focus:border-purple-950 focus:pl-16 focus:pr-4" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-y-0 my-auto h-4 w-13 border-r border-transparent stroke-purple-950 px-2 peer-focus:border-purple-950 peer-focus:stroke-purple-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>

                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-semibold text-purple-950 hover:text-purple-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-purple-950" wire:navigate>Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-purple-950 hover:text-purple-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-purple-950" wire:navigate>Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-purple-950 hover:text-purple-800 focus:outline focus:outline-2 focus:rounded-sm focus:outline-purple-950" wire:navigate>Register</a>
                        @endif
                    @endauth
                </div>

            </div>

        </div>
    </div>

</nav>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <!-- Messages -->
    @if (session()->has('success'))
        <livewire:messages.success-message />
    @endif

    @if (session()->has('error'))
        <livewire:messages.error-message />
    @endif
    <!--End Messages -->


    <!-- Main Content -->
    <div>
        <livewire:posts.create-post />
    </div>
    <!--End Main Content -->

</x-app-layout>

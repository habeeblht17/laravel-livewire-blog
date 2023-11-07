<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         <!-- Styles -->
         <style>
            [x-cloak]{
                display: none !important;
            }

            /* scroll bar */
            ::-webkit-scrollbar {
                height: 12px;
                width: 16px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #bbbbbb;
            }

            ::-webkit-scrollbar-thumb {
                background: rgba(0,0,0,.2);
                border: 4px solid #fff;
                border-radius: 50px;
            }

            ::-webkit-scrollbar-track {
                background: transparent;
            }
            /* end of scroll bar */
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <livewire:layout.navigation />

            <div class="w-full overflow-auto">
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

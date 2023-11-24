<!DOCTYPE html>
@props(['title'])
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> {{ isset($title) ? $title . ' - ' : '' }} {{ config('app.name', 'Laravel') }}</title>

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

        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen bg-gray-100">
            <div>
                <livewire:layout.sideNavigation />
            </div>

            <div class="w-full overflow-auto">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="flex justify-between max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            <div>
                                {{ $header }}
                            </div>

                            <livewire:layout.logout />
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

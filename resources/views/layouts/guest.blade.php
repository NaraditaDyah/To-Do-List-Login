<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'To-Do List') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('aurora.jpg') }}');">
            
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div class="relative z-10 w-full flex flex-col items-center">
                
                <div class="flex flex-col items-center mb-6 text-center">
                    <a href="/">
                        <x-application-logo class="w-32 h-32 fill-current text-gray-500" />
                    </a>
                    
                    <h1 class="text-5xl font-extrabold text-white mt-5 tracking-wide shadow-lg">
                        To-Do List
                    </h1>
                    
                    <p class="text-lg font-medium text-gray-200 mt-2 shadow-lg">
                        Kelola tugas harianmu dengan mudah!
                    </p>
                </div>

                <div class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white shadow-2xl overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
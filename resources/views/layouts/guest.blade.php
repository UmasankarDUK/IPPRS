<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IPPRS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Left Side Branding (Kerala Health Theme) -->
            <div class="md:w-1/2 bg-gradient-to-br from-kerala-primary to-kerala-600 text-white flex flex-col justify-center items-center p-12 shadow-2xl relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-[40rem] h-[40rem] bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>
                
                <div class="z-10 text-center">
                    <div class="mb-8 flex justify-center">
                        <!-- Government of Kerala Logo -->
                        <img src="{{ asset('images/gok_logo1.png') }}" alt="Government of Kerala Logo" style="width: 50%; max-width: 250px;" class="h-auto object-contain drop-shadow-lg">
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">IPPRS</h1>
                    <p class="text-lg md:text-xl font-medium text-kerala-100" style="white-space: nowrap;">Integrated Pandemic Preparedness & Response System</p>
                    <div class="mt-8 border-t border-kerala-400 pt-8 w-16 mx-auto"></div>
                    <div class="mt-2 text-sm text-kerala-200 uppercase tracking-widest leading-relaxed">
                        <p>Department of Health and Family Welfare</p>
                        <p>Government of Kerala</p>
                    </div>
                    
                    <div class="mt-8 flex justify-center">
                        <img src="{{ asset('images/log-n.png') }}" alt="Logo" style="width: 30%; max-width: 150px;" class="h-auto object-contain rounded bg-white p-2">
                    </div>
                </div>
            </div>

            <!-- Right Side Content -->
            <div class="md:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 bg-white">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

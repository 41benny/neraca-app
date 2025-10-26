<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Neraca App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @unless (app()->environment('testing'))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endunless
    </head>
    <body class="font-sans antialiased h-full transition-colors duration-200 bg-gray-50 dark:bg-dark-bg-primary text-gray-900 dark:text-dark-text-primary">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 hidden md:flex flex-col fixed inset-y-0 z-10 bg-white dark:bg-dark-bg-secondary shadow-md transition-all duration-300">
                <div class="flex items-center justify-between h-16 px-4 border-b dark:border-gray-700">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">Neraca</span>
                    </div>
                    <button @click="darkMode = !darkMode" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto py-4 px-2">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-gray-800 text-primary-600 dark:text-primary-400' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Akun
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Laporan
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pengaturan
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="md:pl-64 flex flex-col flex-1">
                <!-- Top Navigation -->
                <header class="sticky top-0 z-10 bg-white dark:bg-dark-bg-secondary shadow-sm">
                    <div class="flex items-center justify-between h-16 px-4">
                        <button type="button" class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center">
                            <div class="relative">
                                <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white">
                                        <span>{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-dark-bg-secondary shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 p-4 bg-gray-50 dark:bg-dark-bg-primary">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }"
     class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-100" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cash.create')" :active="request()->routeIs('cash.*')">
                        {{ __('Kas & Bank') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button
                    onclick="(function(){const r=document.documentElement;const d=r.classList.toggle('dark');localStorage.setItem('theme', d?'dark':'light')})()"
                    class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium
                           bg-gray-200 hover:bg-gray-300 text-gray-800
                           dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100
                           transition">
                    🌙 / ☀️
                </button>

                <!-- Settings Dropdown (only when logged in) -->
                @auth
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent
                                           text-sm leading-4 font-medium rounded-md
                                           text-gray-600 bg-white hover:text-gray-800
                                           dark:text-gray-200 dark:bg-slate-900 dark:hover:text-white
                                           focus:outline-none transition">
                                    <div>{{ auth()->user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth

                <!-- When guest (not logged in) show Login/Register buttons -->
                @guest
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="{{ route('login') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium
                                  bg-gray-100 hover:bg-gray-200 text-gray-700
                                  dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200 transition">
                            {{ __('Login') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium
                                      bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @endguest

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md
                                   text-gray-400 hover:text-gray-500 hover:bg-gray-100
                                   focus:outline-none focus:bg-gray-100 focus:text-gray-500
                                   dark:text-gray-300 dark:hover:text-white dark:hover:bg-slate-800
                                   dark:focus:bg-slate-800 dark:focus:text-white
                                   transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden dark:bg-slate-900">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cash.create')" :active="request()->routeIs('cash.*')">
                {{ __('Kas & Bank') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-white/10">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-100">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-300">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="pt-4 pb-4 border-t border-gray-200 dark:border-white/10">
                <div class="px-4 space-y-2">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            </div>
        @endguest
    </div>
</nav>

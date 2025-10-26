<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neraca Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            background-size: 200% 200%;
            animation: gradient-animation 15s ease infinite;
        }
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-gradient {
            background: linear-gradient(to right, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(to right, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="min-h-screen flex w-full max-w-6xl">
        <!-- Left Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md login-card p-8">
                <!-- Header -->
                <div class="mb-8 text-center">
                    <div class="flex justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Neraca Keuangan</h1>
                    <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="nama@example.com"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                :type="showPassword ? 'text' : 'password'"
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember_me"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition-colors"
                            >
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full btn-gradient text-white py-3 px-4 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-lg transform hover:scale-105"
                    >
                        Masuk
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Atau masuk dengan</span>
                        </div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            type="button" 
                            class="flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#EA4335" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#4285F4" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#34A853" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Google</span>
                        </button>
                        <button 
                            type="button" 
                            class="flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">GitHub</span>
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center mt-6 text-sm">
                        <span class="text-gray-600">Belum punya akun? </span>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700 hover:underline transition-colors font-medium">
                            Daftar sekarang
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side - Decorative (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 items-center justify-center p-12 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 text-white max-w-lg">
                <div class="mb-8">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-4xl font-bold mb-4">Kelola Bisnis Anda dengan Mudah</h2>
                    <p class="text-white/90 text-lg">
                        Platform terpercaya untuk mengelola semua kebutuhan bisnis Anda dalam satu tempat.
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Keamanan Terjamin</h3>
                            <p class="text-white/80 text-sm">Enkripsi tingkat enterprise untuk data Anda</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Akses Kapan Saja</h3>
                            <p class="text-white/80 text-sm">Bekerja dari mana saja, kapan saja</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Support 24/7</h3>
                            <p class="text-white/80 text-sm">Tim support siap membantu Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

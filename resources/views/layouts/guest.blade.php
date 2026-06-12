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

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                        colors: {
                            brand: {
                                50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                                400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                                800: '#3730a3', 900: '#312e81', 950: '#1e1b4b',
                            },
                            surface: {
                                50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                                700: '#1e293b', 800: '#0f172a', 900: '#020617',
                            }
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-surface-900 text-gray-200 min-h-screen antialiased flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
        
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-brand-600/20 blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[5%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[100px]"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-purple-600 shadow-xl shadow-brand-500/30 mb-4">
                    <span class="text-3xl font-bold text-white">G</span>
                </div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-brand-400 to-purple-400">GetConnect ERP</h1>
                <p class="text-gray-400 mt-2 text-sm">Sign in to manage your leads and campaigns.</p>
            </div>

            <div class="bg-surface-800/80 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-8">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} GetConnect. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex">
            
            <!-- Left Side: Branding and Hero Panel (Visible on lg screens) -->
            <div class="hidden lg:flex lg:w-[55%] xl:w-[60%] bg-[#081a30] relative overflow-hidden flex-col justify-between p-12 text-white">
                <!-- Decorative background elements -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(12,77,162,0.15),transparent_50%)]"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-[#0c4da2]/5 blur-3xl"></div>
                
                <!-- Top Brand Logo/Name -->
                <div class="relative z-10 flex items-center gap-3">
                    <img src="/assets/image/logo-promise.png" alt="Promise Logo" class="h-8 w-auto filter brightness-0 invert object-contain">
                    <div class="w-px h-5 bg-white/20"></div>
                    <span class="text-xs font-bold tracking-wider uppercase text-white/80">Promise Console</span>
                </div>

                <!-- Center Quote/Tagline -->
                <div class="relative z-10 my-auto max-w-lg space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-400">Next-Generation Admin</span>
                        <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight">
                            Streamline Enterprise Operations with Precision
                        </h2>
                    </div>
                    <p class="text-sm text-slate-300/90 leading-relaxed font-light">
                        Access unified drawing management, inventory control, and role-based permissions in one secure dashboard.
                    </p>
                    <div class="flex items-center gap-6 pt-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-sky-400">01</span>
                            <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Role Matrix</span>
                        </div>
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-sky-400">02</span>
                            <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Inventory Control</span>
                        </div>
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-sky-400">03</span>
                            <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Drawing Logs</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="relative z-10 text-xs text-slate-400/80">
                    &copy; {{ date('Y') }} PROMISE APPS. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Login Form Panel -->
            <div class="w-full lg:w-[45%] xl:w-[40%] flex flex-col justify-center bg-white p-8 md:p-12 xl:p-16 relative">
                
                <!-- Floating Mobile Header (visible only on smaller screens) -->
                <div class="lg:hidden absolute top-8 left-8 right-8 flex justify-between items-center">
                    <img src="/assets/image/logo-promise.png" alt="Promise Logo" class="h-6 w-auto object-contain">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Admin Console</span>
                </div>

                <!-- Inner Form Container -->
                <div class="w-full max-w-[360px] mx-auto">
                    {{ $slot }}
                </div>

                <!-- Mobile Footer (visible only on smaller screens) -->
                <div class="lg:hidden text-center text-[10px] text-slate-400 mt-12">
                    &copy; {{ date('Y') }} PROMISE APPS. All rights reserved.
                </div>

            </div>

        </div>
    </body>
</html>

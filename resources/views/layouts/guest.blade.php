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
            <div class="hidden lg:flex lg:w-[55%] xl:w-[60%] relative overflow-hidden flex-col justify-between p-12 text-white">
                <!-- Decorative background elements -->
                <div class="absolute inset-0 bg-gradient-to-b from-[#123b96] to-[#0f2f7a]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(59,130,246,0.18),transparent_18rem),radial-gradient(circle_at_80%_85%,rgba(14,165,233,0.16),transparent_16rem)]"></div>
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.07)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.07)_1px,transparent_1px)] bg-[size:52px_52px] opacity-55"></div>
                <div class="absolute right-[5.5rem] top-[2.8rem] h-[52px] w-[52px] bg-white/8"></div>
                <div class="absolute right-[8.75rem] top-[5.95rem] h-[52px] w-[52px] bg-white/8"></div>
                <div class="absolute bottom-24 left-[6.25rem] h-[52px] w-[52px] bg-white/8"></div>
                <div class="absolute bottom-[2.8rem] left-[9.5rem] h-[52px] w-[52px] bg-white/8"></div>
                
                <!-- Top Brand Logo/Name -->
                <div class="relative z-10 flex items-center gap-3">
                    <img src="{{ asset('assets/image/logo-promise.png') }}" alt="Promise Logo" class="h-8 w-auto filter brightness-0 invert object-contain">
                    <div class="w-px h-5 bg-white/20"></div>
                    <span class="text-xs font-bold tracking-wider uppercase text-white/80">Promise Console</span>
                </div>

                <!-- Center Quote/Tagline -->
                <div class="relative z-10 my-auto max-w-xl space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-400">Promise Admin Console</span>
                        <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight text-white">
                            Promise Admin
                        </h2>
                        <p class="text-sm text-slate-200/80 leading-relaxed font-light">
                            Centralized administrator panel for managing users, scopes, departments, and role-based permissions across Summit Adyawinsa internal applications.
                        </p>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-4 pt-4">
                        <div class="rounded-xs border border-white/10 bg-white/7 p-4 text-left">
                            <strong class="block text-[1.05rem] font-bold text-white">01</strong>
                            <span class="mt-1 block text-[10px] sm:text-xs leading-normal text-slate-200/75 font-light">Unified RBAC & user permission matrix configuration.</span>
                        </div>
                        <div class="rounded-xs border border-white/10 bg-white/7 p-4 text-left">
                            <strong class="block text-[1.05rem] font-bold text-white">02</strong>
                            <span class="mt-1 block text-[10px] sm:text-xs leading-normal text-slate-200/75 font-light">Manage active application scopes and departments.</span>
                        </div>
                        <div class="rounded-xs border border-white/10 bg-white/7 p-4 text-left">
                            <strong class="block text-[1.05rem] font-bold text-white">03</strong>
                            <span class="mt-1 block text-[10px] sm:text-xs leading-normal text-slate-200/75 font-light">Monitor application stats and system logs in real-time.</span>
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
                    <img src="{{ asset('assets/image/logo-promise.png') }}" alt="Promise Logo" class="h-6 w-auto object-contain">
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

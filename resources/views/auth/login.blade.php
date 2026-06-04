<x-guest-layout>
    
    <!-- Title / Logo Header (Desktop) -->
    <div class="mb-8">
        <div class="hidden lg:block mb-4">
            <img src="{{ asset('assets/image/logo-promise.png') }}" alt="Promise Logo" class="h-10 w-auto object-contain">
        </div>
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Sign In</h2>
        <p class="text-xs text-gray-400 mt-1">Please enter your enterprise credentials to access the console.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">NIK / Employee ID</label>
            <div class="relative flex items-center">
                <span class="absolute left-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-user"></i>
                </span>
                <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required autofocus autocomplete="username"
                       placeholder="Enter your NIK"
                       class="w-full text-xs border border-gray-300 py-2.5 pl-9 pr-3 focus:border-[#0c4da2] focus:ring-1 focus:ring-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
            </div>
            @if ($errors->get('nik'))
                <p class="text-[10px] text-rose-600 mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i>
                    <span>{{ $errors->first('nik') }}</span>
                </p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] text-slate-400 hover:text-[#0c4da2] transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative flex items-center">
                <span class="absolute left-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full text-xs border border-gray-300 py-2.5 pl-9 pr-3 focus:border-[#0c4da2] focus:ring-1 focus:ring-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
            </div>
            @if ($errors->get('password'))
                <p class="text-[10px] text-rose-600 mt-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i>
                    <span>{{ $errors->first('password') }}</span>
                </p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember"
                       class="h-4 w-4 border-gray-300 text-[#0c4da2] focus:ring-0 cursor-pointer">
                <span class="ms-2 text-xs text-slate-500 font-medium">Keep me signed in</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" :disabled="loading"
                    class="w-full py-2.5 px-4 text-xs font-bold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors tracking-wider uppercase flex items-center justify-center gap-2 disabled:opacity-50">
                <template x-if="loading">
                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </template>
                <span x-text="loading ? 'Signing In...' : 'Sign In to Console'">Sign In to Console</span>
                <i x-show="!loading" class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>
    </form>
</x-guest-layout>

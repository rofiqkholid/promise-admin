<nav class="border-b sticky top-0 z-50" x-data="{ open: false }" style="background-color: #0c4da2; border-color: #083c80;">
    <div class="w-full px-6">
        <div class="flex items-stretch h-12">

            <!-- Brand / Logo -->
            <div class="flex items-center pr-8 border-r border-white/20">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/image/logo-promise.png') }}" alt="PROMISE" style="height: 32px;" class="w-auto">
                    <div class="flex flex-col justify-center">
                        <span class="text-sm font-bold text-white uppercase tracking-widest leading-none">PROMISE</span>
                        <span class="text-[10px] font-bold text-blue-100 uppercase tracking-widest border border-white/20 px-1.5 py-0.5 mt-1 rounded-xs leading-none text-center">Admin</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex items-stretch ml-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 text-xs font-medium border-b-2 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white border-white' : 'text-blue-100 border-transparent hover:text-white hover:border-white/50' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.index') }}"
                   class="flex items-center px-4 text-xs font-medium border-b-2 transition-colors {{ request()->routeIs('admin.index') ? 'text-white border-white' : 'text-blue-100 border-transparent hover:text-white hover:border-white/50' }}">
                    User Management
                </a>
                <a href="{{ route('admin.roles.index') }}"
                   class="flex items-center px-4 text-xs font-medium border-b-2 transition-colors {{ request()->routeIs('admin.roles.*') ? 'text-white border-white' : 'text-blue-100 border-transparent hover:text-white hover:border-white/50' }}">
                    Roles & Permissions
                </a>
                <a href="{{ route('admin.menus.index') }}"
                   class="flex items-center px-4 text-xs font-medium border-b-2 transition-colors {{ request()->routeIs('admin.menus.*') ? 'text-white border-white' : 'text-blue-100 border-transparent hover:text-white hover:border-white/50' }}">
                    Menus Master
                </a>
                <a href="{{ route('admin.masters.index') }}"
                   class="flex items-center px-4 text-xs font-medium border-b-2 transition-colors {{ request()->routeIs('admin.masters.*') ? 'text-white border-white' : 'text-blue-100 border-transparent hover:text-white hover:border-white/50' }}">
                    Master Data
                </a>
            </div>

            <!-- Right: User Menu Dropdown -->
            <div class="hidden sm:flex items-center ml-auto gap-4">
                <!-- 9-Dots Apps Menu -->
                <div x-data="{ appsDropdownOpen: false }" class="relative flex-shrink-0">
                    <button @click="appsDropdownOpen = !appsDropdownOpen" @click.outside="appsDropdownOpen = false"
                        class="flex items-center justify-center w-8 h-8 rounded-xs hover:bg-white/10 transition-colors duration-200 focus:outline-none text-blue-100 hover:text-white" title="Apps Menu">
                        <i class="fa-solid fa-grip text-lg"></i>
                    </button>

                    <!-- Desktop Apps Dropdown -->
                    <div x-show="appsDropdownOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-xs shadow-lg border border-slate-200 p-3 z-50 origin-top-right text-slate-800"
                        style="display: none;">
                        
                        <div class="grid grid-cols-3 gap-1">
                            <a href="{{ env('APP_DRAWING_URL', 'http://localhost:8081') }}"
                                class="flex flex-col items-center justify-center p-1.5 rounded-xs hover:bg-slate-50 transition-all duration-200 group text-center">
                                <div class="w-9 h-9 rounded-xs flex items-center justify-center bg-indigo-50 text-indigo-600 mb-1 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class="fa-solid fa-pen-ruler text-xs"></i>
                                </div>
                                <span class="text-[9px] font-semibold text-gray-700 leading-tight">Drawing</span>
                            </a>

                            <a href="{{ env('APP_INVENTORY_URL', 'http://localhost:8082') }}"
                                class="flex flex-col items-center justify-center p-1.5 rounded-xs hover:bg-slate-50 transition-all duration-200 group text-center">
                                <div class="w-9 h-9 rounded-xs flex items-center justify-center bg-blue-50 text-blue-600 mb-1 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class="fa-solid fa-boxes-stacked text-xs"></i>
                                </div>
                                <span class="text-[9px] font-semibold text-gray-700 leading-tight">Inventory</span>
                            </a>

                            <a href="{{ env('APP_NPC_URL', 'http://localhost:8083') }}"
                                class="flex flex-col items-center justify-center p-1.5 rounded-xs hover:bg-slate-50 transition-all duration-200 group text-center">
                                <div class="w-9 h-9 rounded-xs flex items-center justify-center bg-purple-50 text-purple-600 mb-1 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class="fa-solid fa-users-gear text-xs"></i>
                                </div>
                                <span class="text-[9px] font-semibold text-gray-700 leading-tight">NPC</span>
                            </a>

                            <a href="{{ env('APP_DASH_URL', 'http://localhost:8084') }}"
                                class="flex flex-col items-center justify-center p-1.5 rounded-xs hover:bg-slate-50 transition-all duration-200 group text-center">
                                <div class="w-9 h-9 rounded-xs flex items-center justify-center bg-teal-50 text-teal-600 mb-1 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class="fa-solid fa-chart-pie text-xs"></i>
                                </div>
                                <span class="text-[9px] font-semibold text-gray-700 leading-tight">All Dashboard</span>
                            </a>

                            <a href="{{ env('APP_PORTAL_URL', 'http://localhost:8080') }}"
                                class="flex flex-col items-center justify-center p-1.5 rounded-xs hover:bg-slate-50 transition-all duration-200 group text-center">
                                <div class="w-9 h-9 rounded-xs flex items-center justify-center bg-emerald-50 text-emerald-600 mb-1 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class="fa-solid fa-house text-xs"></i>
                                </div>
                                <span class="text-[9px] font-semibold text-gray-700 leading-tight">Portal</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div x-data="{ openProfile: false }" class="relative">
                    <button @click="openProfile = !openProfile" @click.outside="openProfile = false"
                            class="flex items-center gap-2.5 p-1 px-2.5 rounded-xs transition-colors bg-white/10 hover:bg-white/15 text-white border border-white/5">
                        <div class="h-7 w-7 rounded-xs bg-white/20 text-white flex items-center justify-center font-bold text-xs uppercase border border-white/20">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-left shrink-0">
                            <p class="text-xs font-semibold leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-blue-200 mt-1 leading-none">{{ Auth::user()->nik ?? 'Admin' }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-blue-200 transition-transform duration-200" :class="{'rotate-180': openProfile}"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openProfile" x-transition.origin.top.right
                         class="absolute right-0 mt-2 w-44 bg-white border border-slate-300 py-1 shadow-lg z-50 text-slate-800"
                         style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs hover:bg-slate-50 flex items-center gap-2 text-slate-700">
                            <i class="fa-regular fa-user w-4 text-slate-400"></i> Edit Profile
                        </a>
                        <div class="border-t border-slate-200 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile: Hamburger -->
            <div class="flex items-center ml-auto sm:hidden">
                <button @click="open = !open" class="text-slate-400 hover:text-white p-1.5 transition-colors">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-white/10 bg-blue-950">
        <div class="py-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-6 py-2.5 text-xs font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/5' }} transition-colors">
                Dashboard
            </a>
            <a href="{{ route('admin.index') }}" class="block px-6 py-2.5 text-xs font-medium {{ request()->routeIs('admin.index') ? 'text-white bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/5' }} transition-colors">
                User Management
            </a>
            <a href="{{ route('admin.roles.index') }}" class="block px-6 py-2.5 text-xs font-medium {{ request()->routeIs('admin.roles.*') ? 'text-white bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/5' }} transition-colors">
                Roles & Permissions
            </a>
            <a href="{{ route('admin.menus.index') }}" class="block px-6 py-2.5 text-xs font-medium {{ request()->routeIs('admin.menus.*') ? 'text-white bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/5' }} transition-colors">
                Menus Master
            </a>
            <a href="{{ route('admin.masters.index') }}" class="block px-6 py-2.5 text-xs font-medium {{ request()->routeIs('admin.masters.*') ? 'text-white bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/5' }} transition-colors">
                Master Data
            </a>
        </div>
        <!-- Switch App (Mobile) -->
        <div class="border-t border-white/10 py-3 px-6">
            <p class="text-[9px] font-bold text-blue-300 uppercase tracking-widest mb-2.5">Switch App</p>
            <div class="grid grid-cols-4 gap-2">
                <a href="{{ env('APP_DRAWING_URL', 'http://localhost:8081') }}" class="flex flex-col items-center justify-center p-2 rounded-xs bg-white/5 hover:bg-white/10 border border-white/5 transition-colors">
                    <i class="fa-solid fa-pen-ruler text-white text-xs mb-1"></i>
                    <span class="text-[8px] font-semibold text-blue-200">Drawing</span>
                </a>
                <a href="{{ env('APP_INVENTORY_URL', 'http://localhost:8082') }}" class="flex flex-col items-center justify-center p-2 rounded-xs bg-white/5 hover:bg-white/10 border border-white/5 transition-colors">
                    <i class="fa-solid fa-boxes-stacked text-white text-xs mb-1"></i>
                    <span class="text-[8px] font-semibold text-blue-200">Inventory</span>
                </a>
                <a href="{{ env('APP_NPC_URL', 'http://localhost:8083') }}" class="flex flex-col items-center justify-center p-2 rounded-xs bg-white/5 hover:bg-white/10 border border-white/5 transition-colors">
                    <i class="fa-solid fa-users-gear text-white text-xs mb-1"></i>
                    <span class="text-[8px] font-semibold text-blue-200">NPC</span>
                </a>
                <a href="{{ env('APP_PORTAL_URL', 'http://localhost:8080') }}" class="flex flex-col items-center justify-center p-2 rounded-xs bg-white/5 hover:bg-white/10 border border-white/5 transition-colors">
                    <i class="fa-solid fa-house text-white text-xs mb-1"></i>
                    <span class="text-[8px] font-semibold text-blue-200">Portal</span>
                </a>
            </div>
        </div>
        <div class="border-t border-white/10 py-2">
            <div class="px-6 py-2">
                <p class="text-xs text-white font-semibold">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-blue-200">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-6 py-2.5 text-xs text-blue-200 hover:text-white hover:bg-white/5 transition-colors">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-6 py-2.5 text-xs text-red-300 hover:text-red-200 hover:bg-white/5 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

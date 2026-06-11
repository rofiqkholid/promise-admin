<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide">User Management Dashboard</h2>
    </x-slot>

    <!-- Load Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="px-3 py-2.5 md:px-5 md:py-3.5 flex flex-col gap-3 h-auto lg:h-[calc(100vh-140px)] lg:max-h-[calc(100vh-140px)] overflow-y-auto lg:overflow-hidden" x-data="dashboardConsole()">
        
        <!-- Summary Stats Grid (High Density - 6 Items) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 shrink-0">
            <!-- Total Users -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Total Users</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_users"></span>
                </div>
            </div>
            
            <!-- Active Users -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-user-check text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Active Users</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.active_users"></span>
                </div>
            </div>

            <!-- Custom Overrides -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Custom Overrides</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_overrides"></span>
                </div>
            </div>

            <!-- Active Scopes -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-blue-50 text-[#0c4da2] flex items-center justify-center shrink-0 border border-blue-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Active Scopes</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_scopes"></span>
                </div>
            </div>

            <!-- Access Roles -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Access Roles</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_roles"></span>
                </div>
            </div>

            <!-- Departments -->
            <div class="bg-white border border-slate-300 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="w-9 h-9 bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-500 text-gray-400 tracking-wider block">Departments</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_departments"></span>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Workspace -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-3 min-h-0">
            
            <!-- Left Side: User Application, Department Mapping & System Monitor -->
            <div class="lg:col-span-8 flex flex-col gap-3 min-h-0 h-full">
                
                <!-- Top Row: Scope distribution, Departments & Roles breakdown (3 Columns) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 shrink-0 lg:h-[200px]">
                    <!-- 1. User Scope Distribution -->
                    <div class="bg-white border border-slate-300 flex flex-col shadow-[0_1px_2px_rgba(0,0,0,0.02)] h-full overflow-hidden">
                        <div class="px-3 py-2 border-b border-slate-300 bg-slate-50 flex items-center gap-2 shrink-0">
                            <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">User Scope Distribution</span>
                        </div>
                        <div class="flex-1 min-h-0 p-3 flex items-center gap-3 justify-between">
                            <!-- Doughnut Container -->
                            <div class="relative w-24 h-24 shrink-0">
                                <canvas id="scopeDistributionChart"></canvas>
                                <!-- Center Label inside Doughnut -->
                                <div class="absolute flex flex-col items-center justify-center pointer-events-none" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <span class="text-[7px] font-bold text-slate-400 tracking-wider leading-none uppercase">Users</span>
                                    <span class="text-sm font-extrabold text-gray-900 mt-0.5" x-text="scopeChartData.reduce((acc, curr) => acc + curr.count, 0)"></span>
                                </div>
                            </div>
                            <!-- Legend -->
                            <div class="flex-1 space-y-1.5 min-w-0">
                                <template x-for="(scope, index) in scopeChartData" :key="scope.id">
                                    <div class="flex items-center justify-between text-[11px] border-b border-slate-100 pb-1 last:border-0 last:pb-0">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <div class="w-2 h-2 rounded-full shrink-0" :style="'background-color: ' + getScopeColor(scope.id)"></div>
                                            <span class="font-medium text-slate-700 truncate" :title="scope.label" x-text="scope.label"></span>
                                        </div>
                                        <span class="font-bold text-slate-900 shrink-0" x-text="scope.count + ' User'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Top Departments -->
                    <div class="bg-white border border-slate-300 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col h-full overflow-hidden">
                        <div class="px-3 py-2 border-b border-slate-300 bg-slate-50 flex items-center gap-2 shrink-0">
                            <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Top Departments</span>
                        </div>
                        <div class="px-3 pt-1.5 pb-1 space-y-1.5 flex-1 overflow-y-auto custom-scrollbar flex flex-col justify-start">
                            <template x-for="dept in departmentBreakdown">
                                <div>
                                    <div class="flex justify-between items-center text-[11px] font-semibold text-gray-900 mb-0.5">
                                        <span class="truncate w-28 font-medium text-slate-700" x-text="dept.name"></span>
                                        <span class="font-bold text-slate-900" x-text="dept.user_count + ' User'"></span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-[#0c4da2] transition-all duration-500" :style="'width: ' + ((dept.user_count / stats.total_users) * 100) + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. Popular Roles -->
                    <div class="bg-white border border-slate-300 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col h-full overflow-hidden">
                        <div class="px-3 py-2 border-b border-slate-300 bg-slate-50 flex items-center gap-2 shrink-0">
                            <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Popular Roles</span>
                        </div>
                        <div class="px-3 pt-1.5 pb-1 space-y-1.5 flex-1 overflow-y-auto custom-scrollbar flex flex-col justify-start">
                            <template x-for="role in roleBreakdown">
                                <div>
                                    <div class="flex justify-between items-center text-[11px] font-semibold text-gray-900 mb-0.5">
                                        <span class="truncate w-28 font-medium text-slate-700" x-text="role.role_name"></span>
                                        <span class="font-bold text-slate-900" x-text="role.user_count + ' User'"></span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-[#0c4da2] transition-all duration-500" :style="'width: ' + ((role.user_count / stats.total_users) * 100) + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Real-time System & Performance Monitor -->
                <div class="bg-white border border-slate-300 flex-1 min-h-0 flex flex-col shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-slate-300 bg-slate-50 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">System & Web Performance Monitor</span>
                        </div>
                        <div class="text-[10px] font-semibold text-slate-500">
                            Uptime: <span class="font-mono font-extrabold text-[#0c4da2]" x-text="system.uptime"></span>
                        </div>
                    </div>
                    <div class="flex-1 p-3 grid grid-cols-2 lg:grid-cols-4 gap-3 overflow-y-auto custom-scrollbar"
                         x-init="initPerformanceMonitor()"
                         x-destroy="destroyPerformanceMonitor()">

                        <!-- CPU Load Card: Sparkline + Load % + Current Clock Speed -->
                        <div class="bg-slate-50/60 border border-slate-200 p-3 rounded-xs flex flex-col justify-between overflow-hidden min-h-[110px]">
                            <!-- Header -->
                            <div class="flex items-center justify-between shrink-0">
                                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">CPU Load</span>
                                <i class="fa-solid fa-microchip text-slate-400 text-xs"></i>
                            </div>
                            <!-- Sparkline (center) -->
                            <div class="w-full overflow-hidden flex-1 flex items-center my-1.5" style="height:28px">
                                <svg viewBox="0 0 200 28" class="w-full h-full" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="gradCpu" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#2563eb" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#2563eb" stop-opacity="0"/>
                                        </linearGradient>
                                        <clipPath id="clipCpu"><rect x="0" y="0" width="200" height="28"/></clipPath>
                                    </defs>
                                    <g clip-path="url(#clipCpu)">
                                        <polygon fill="url(#gradCpu)"
                                                 :points="'0,28 ' + sparklinePath(history.cpu, 200, 28, 2) + ' 200,28'"></polygon>
                                        <polyline fill="none" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                  :points="sparklinePath(history.cpu, 200, 28, 2)"></polyline>
                                    </g>
                                </svg>
                            </div>
                            <!-- Value + current speed at bottom -->
                            <div class="shrink-0">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-800 leading-none" x-text="perf.cpu + '%'"></span>
                                    <span class="text-[10px] font-semibold"
                                          :class="perf.cpu < 70 ? 'text-emerald-500' : 'text-rose-500'"
                                          x-text="perf.cpu < 70 ? 'Normal' : 'High'"></span>
                                    <!-- Current clock speed — changes with turbo/throttle -->
                                    <span class="ml-auto text-[9px] font-mono text-slate-500 font-semibold" x-show="perf.cpu_speed > 0" x-text="perf.cpu_speed + ' GHz'"></span>
                                </div>
                                <div class="w-full h-1 bg-slate-200/70 rounded-full mt-1.5 overflow-hidden">
                                    <div class="h-full bg-blue-600 transition-all duration-1000" :style="'width: ' + perf.cpu + '%'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Memory (RAM) Card: Sparkline + In Use + Free -->
                        <div class="bg-slate-50/60 border border-slate-200 p-3 rounded-xs flex flex-col justify-between overflow-hidden min-h-[110px]">
                            <div class="flex items-center justify-between shrink-0">
                                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">Memory (RAM)</span>
                                <i class="fa-solid fa-memory text-slate-400 text-xs"></i>
                            </div>
                            <div class="w-full overflow-hidden flex-1 flex items-center my-1.5" style="height:28px">
                                <svg viewBox="0 0 200 28" class="w-full h-full" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="gradRam" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                                        </linearGradient>
                                        <clipPath id="clipRam"><rect x="0" y="0" width="200" height="28"/></clipPath>
                                    </defs>
                                    <g clip-path="url(#clipRam)">
                                        <polygon fill="url(#gradRam)"
                                                 :points="'0,28 ' + sparklinePath(history.ram, 200, 28, 2) + ' 200,28'"></polygon>
                                        <polyline fill="none" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                  :points="sparklinePath(history.ram, 200, 28, 2)"></polyline>
                                    </g>
                                </svg>
                            </div>
                            <div class="shrink-0">
                                <!-- In Use (used) as primary value -->
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-800 leading-none" x-text="perf.ram + ' GB'"></span>
                                    <span class="text-[10px] text-slate-400 font-medium">in use</span>
                                    <span class="text-[10px] font-bold text-slate-500" x-text="'(' + Math.round((perf.ram / perf.total_ram) * 100) + '%)'"></span>
                                    <span class="ml-auto text-[10px] font-mono text-slate-500 font-semibold" x-text="perf.ram_free + ' GB free'"></span>
                                </div>
                                <div class="w-full h-1 bg-slate-200/70 rounded-full mt-1.5 overflow-hidden">
                                    <div class="h-full bg-emerald-500 transition-all duration-1000" :style="'width: ' + ((perf.ram / perf.total_ram) * 100) + '%'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- DB Latency Card: Sparkline -->
                        <div class="bg-slate-50/60 border border-slate-200 p-3 rounded-xs flex flex-col justify-between overflow-hidden min-h-[110px]">
                            <div class="flex items-center justify-between shrink-0">
                                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">DB Latency</span>
                                <i class="fa-solid fa-database text-slate-400 text-xs"></i>
                            </div>
                            <div class="w-full overflow-hidden flex-1 flex items-center my-1.5" style="height:28px">
                                <svg viewBox="0 0 200 28" class="w-full h-full" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="gradDb" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                        </linearGradient>
                                        <clipPath id="clipDb"><rect x="0" y="0" width="200" height="28"/></clipPath>
                                    </defs>
                                    <g clip-path="url(#clipDb)">
                                        <polygon fill="url(#gradDb)"
                                                 :points="'0,28 ' + sparklinePath(history.db, 200, 28, 2) + ' 200,28'"></polygon>
                                        <polyline fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                  :points="sparklinePath(history.db, 200, 28, 2)"></polyline>
                                    </g>
                                </svg>
                            </div>
                            <div class="shrink-0">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-800 leading-none" x-text="perf.db + ' ms'"></span>
                                    <span class="text-[10px] font-semibold"
                                          :class="perf.db < 50 ? 'text-emerald-500' : (perf.db < 100 ? 'text-amber-500' : 'text-rose-500')"
                                          x-text="perf.db < 50 ? 'Fast' : (perf.db < 100 ? 'Moderate' : 'Slow')"></span>
                                </div>
                                <div class="w-full h-1 bg-slate-200/70 rounded-full mt-1.5 overflow-hidden">
                                    <div class="h-full bg-amber-500 transition-all duration-1000" :style="'width: ' + Math.min(100, (perf.db / 150) * 100) + '%'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Disk (C:) Card: Radial Gauge (larger/thicker) + Free Space -->
                        <div class="bg-slate-50/60 border border-slate-200 p-3 rounded-xs flex flex-col justify-between overflow-hidden min-h-[110px]">
                            <div class="flex items-center justify-between shrink-0">
                                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">Disk (C:)</span>
                                <i class="fa-solid fa-hard-drive text-slate-400 text-xs"></i>
                            </div>
                            <!-- Radial gauge: bigger arc, thicker stroke -->
                            <div class="flex-1 flex items-center justify-center" style="min-height:52px">
                                <svg viewBox="0 0 100 58" class="w-full" style="max-height:58px;overflow:visible">
                                    <!-- Track arc (180°, r=40, center 50,54) -->
                                    <path d="M6,54 A44,44 0 0,1 94,54" fill="none" stroke="#e2e8f0" stroke-width="9" stroke-linecap="round"/>
                                    <!-- Fill arc: dynamic, circumference of 44r half-circle ≈ 138.2 -->
                                    <path d="M6,54 A44,44 0 0,1 94,54" fill="none"
                                          :stroke="perf.disk_pct < 80 ? '#10b981' : (perf.disk_pct < 90 ? '#f59e0b' : '#ef4444')"
                                          stroke-width="9" stroke-linecap="round"
                                          stroke-dasharray="138.2"
                                          :stroke-dashoffset="138.2 - (perf.disk_pct / 100) * 138.2"
                                          style="transition: stroke-dashoffset 1s ease, stroke 1s ease"/>
                                    <!-- Center % label -->
                                    <text x="50" y="50" text-anchor="middle"
                                          font-size="14" font-weight="800" fill="#1e293b"
                                          :textContent="perf.disk_pct + '%'"></text>
                                </svg>
                            </div>
                            <!-- Free space at bottom -->
                            <div class="shrink-0">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-800 leading-none" x-text="perf.disk_free + ' GB'"></span>
                                    <span class="text-[10px] text-slate-400 font-medium">free</span>
                                    <span class="text-[10px] font-bold text-slate-500" x-text="'(' + (100 - perf.disk_pct) + '% free)'"></span>
                                    <span class="ml-auto text-[10px] font-semibold"
                                          :class="perf.disk_pct < 80 ? 'text-emerald-500' : (perf.disk_pct < 90 ? 'text-amber-500' : 'text-rose-500')"
                                          x-text="perf.disk_pct < 80 ? 'Normal' : (perf.disk_pct < 90 ? 'Warning' : 'Critical')">
                                    </span>
                                </div>
                                <div class="text-[9px] text-slate-400 mt-0.5" x-text="perf.disk_used + ' / ' + perf.disk_total + ' GB used (' + perf.disk_pct + '%)'"></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Right Side: Recent Registered Users & Custom Overrides -->
            <div class="lg:col-span-4 flex flex-col gap-3 min-h-0 h-full">
                <!-- Latest Enrollments & Online Users Tabs -->
                <div class="bg-white border border-slate-300 lg:h-[200px] shrink-0 flex flex-col min-h-0 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 border-b border-slate-300 bg-slate-50 flex items-center shrink-0">
                        <div class="flex items-center gap-4">
                            <!-- Tab 1: Online Users -->
                            <button @click="rightTab = 'online'" 
                                    :class="rightTab === 'online' ? 'border-[#0c4da2] text-[#0c4da2]' : 'border-transparent text-slate-400 hover:text-gray-900'" 
                                    class="py-2.5 border-b-2 font-bold tracking-wider text-[9px] uppercase focus:outline-none flex items-center gap-1.5 transition-all">
                                <span>Online Users</span>
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span class="text-[10px] bg-emerald-50 text-emerald-600 px-1 rounded-xs font-bold" x-text="stats.online_users"></span>
                            </button>
                            <!-- Tab 2: Enrollments -->
                            <button @click="rightTab = 'enrollments'" 
                                    :class="rightTab === 'enrollments' ? 'border-[#0c4da2] text-[#0c4da2]' : 'border-transparent text-slate-400 hover:text-gray-900'" 
                                    class="py-2.5 border-b-2 font-bold tracking-wider text-[9px] uppercase focus:outline-none transition-all">
                                Latest Enrollments
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <!-- Enrollments Tab Content -->
                        <div x-show="rightTab === 'enrollments'">
                            <table class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-300 text-[9px] font-bold text-slate-400 tracking-wider sticky top-0 backdrop-blur-xs uppercase">
                                        <th class="py-1.5 px-4">User Details</th>
                                        <th class="py-1.5 px-3">NIK</th>
                                        <th class="py-1.5 px-4 text-right">Registered</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($recentUsers as $user)
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-1.5 px-4 font-medium text-gray-900">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-5 h-5 bg-[#0c4da2] text-white flex items-center justify-center font-bold text-[10px] rounded-xs">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="truncate w-32 font-bold text-gray-900 text-[11px] leading-tight" title="{{ $user->name }}">{{ $user->name }}</div>
                                                        <div class="text-[9px] text-slate-400 truncate w-32 mt-0.5 leading-none" title="{{ $user->dept_name ?? 'No Department' }}">
                                                            {{ $user->dept_code ? '[' . $user->dept_code . '] ' . $user->dept_name : 'No Department' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-slate-500 text-xs">{{ $user->nik }}</td>
                                            <td class="py-1.5 px-4 text-right text-slate-400 text-[10px]">{{ $user->created_at ? $user->created_at->format('m-d H:i') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Online Users Tab Content -->
                        <div x-show="rightTab === 'online'" x-cloak>
                            <table class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-300 text-[9px] font-bold text-slate-400 tracking-wider sticky top-0 backdrop-blur-xs uppercase">
                                        <th class="py-1.5 px-4">User Details</th>
                                        <th class="py-1.5 px-3">NIK</th>
                                        <th class="py-1.5 px-4 text-right">Last Active</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-if="onlineUsers.length === 0">
                                        <tr>
                                            <td colspan="3" class="py-6 text-center text-slate-400 text-[10px] font-light">
                                                No users currently online.
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="user in onlineUsers" :key="user.nik">
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-1.5 px-4 font-medium text-gray-900">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-5 h-5 bg-emerald-500 text-white flex items-center justify-center font-bold text-[10px] rounded-xs">
                                                        <span x-text="user.name.substring(0, 1).toUpperCase()"></span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="truncate w-32 font-bold text-gray-900 text-[11px] leading-tight" :title="user.name" x-text="user.name"></div>
                                                        <div class="text-[9px] text-slate-400 truncate w-32 mt-0.5 leading-none" :title="user.dept_name || 'No Dept'" x-text="user.dept_name || 'No Dept'"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-1.5 px-3 text-slate-500 text-[10px]" x-text="user.nik"></td>
                                            <td class="py-1.5 px-4 text-right">
                                                <span class="inline-block px-1 py-0.5 rounded-xs bg-emerald-50 text-emerald-600 font-bold text-[10px] border border-emerald-100" x-text="formatLastActive(user.last_activity)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Custom Override Users -->
                <div class="bg-white border border-slate-300 flex-1 flex flex-col min-h-0 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-slate-300 bg-slate-50 flex items-center gap-2 shrink-0">
                        <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Active Custom Overrides</span>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-300 text-[9px] font-bold text-slate-400 tracking-wider sticky top-0 backdrop-blur-xs uppercase">
                                    <th class="py-1.5 px-4">User Details</th>
                                    <th class="py-1.5 px-3">NIK</th>
                                    <th class="py-1.5 px-4 text-right">Overrides</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-if="overrideUsers.length === 0">
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-slate-400 text-[10px] font-light">
                                            No active custom overrides.
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="user in overrideUsers" :key="user.nik">
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-1.5 px-4 font-medium text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 bg-rose-500 text-white flex items-center justify-center font-bold text-[10px] rounded-xs">
                                                    <span x-text="user.name.substring(0, 1).toUpperCase()"></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="truncate w-32 font-bold text-gray-900 text-[11px] leading-tight" :title="user.name" x-text="user.name"></div>
                                                    <div class="text-[9px] text-slate-400 truncate w-32 mt-0.5 leading-none" :title="user.dept_code || 'No Dept'" x-text="user.dept_code || 'No Dept'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-1.5 px-3 text-slate-500 text-[10px]" x-text="user.nik"></td>
                                        <td class="py-1.5 px-4 text-right">
                                            <span class="inline-block px-1 py-0.5 rounded-xs bg-rose-50 text-rose-600 font-bold text-[10px] border border-rose-100" x-text="user.override_count + ' Rules'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        function dashboardConsole() {
            return {
                stats: @json($stats),
                scopeChartData: @json($scopeChartData),
                departmentBreakdown: @json($departmentBreakdown),
                roleBreakdown: @json($roleBreakdown),
                overrideUsers: @json($overrideUsers),
                onlineUsers: @json($onlineUsers),
                rightTab: 'online',
                system: {
                    uptime: '{{ $initialUptime }}'
                },
                perf: {
                    cpu: 0,
                    cpu_speed: 0,
                    ram: 0,
                    ram_free: 0,
                    total_ram: 16.0,
                    db: 0,
                    disk_used: 0,
                    disk_free: 0,
                    disk_total: 0,
                    disk_pct: 0
                },
                // Sparkline history – ring buffer, max 20 points each
                // Starts empty so data slides in from the right on first fetch
                history: {
                    cpu:  [],
                    ram:  [],
                    db:   [],
                    disk: []
                },

                async initPerformanceMonitor() {
                    // Fetch immediately on load — data enters from the right naturally
                    await this.fetchMetrics();
                    // Poll every 5 seconds – aligned with server cache TTL (CPU/RAM cache=5s, Disk cache=30s)
                    this._perfTimer = setInterval(async () => {
                        await this.fetchMetrics();
                    }, 5000);
                },

                destroyPerformanceMonitor() {
                    if (this._perfTimer) clearInterval(this._perfTimer);
                },

                async fetchMetrics() {
                    try {
                        const res = await fetch('{{ route('admin.dashboard.metrics') }}');
                        if (!res.ok) throw new Error('Response error');
                        const data = await res.json();

                        this.perf.db         = data.db;
                        this.perf.cpu        = data.cpu;
                        this.perf.cpu_speed  = data.cpu_speed ?? 0;
                        this.perf.ram        = data.ram;
                        this.perf.ram_free   = data.ram_free ?? (data.total_ram - data.ram);
                        this.perf.total_ram  = data.total_ram;
                        this.perf.disk_used  = data.disk_used;
                        this.perf.disk_free  = data.disk_free ?? 0;
                        this.perf.disk_total = data.disk_total;
                        this.perf.disk_pct   = data.disk_pct;

                        if (data.online_users !== undefined) {
                            this.onlineUsers = data.online_users;
                        }
                        if (data.online_users_count !== undefined) {
                            this.stats.online_users = data.online_users_count;
                        }
                        if (data.uptime !== undefined) {
                            this.system.uptime = data.uptime;
                        }

                        // Push new values into sparkline history (max 20 points)
                        // Each push slides existing data left and appends new point on the right
                        const push = (arr, val) => { arr.push(val); if (arr.length > 20) arr.shift(); };
                        push(this.history.cpu,  data.cpu);
                        push(this.history.ram,  data.ram);
                        push(this.history.db,   data.db);
                        push(this.history.disk, data.disk_pct);
                    } catch (e) {
                        console.warn("Failed to fetch dashboard metrics:", e);
                    }
                },

                // Build an SVG polyline points string from a history array.
                // Points are right-aligned: latest data always at x=w edge.
                // w = viewBox width, h = viewBox height, pad = vertical padding in px
                sparklinePath(data, w, h, pad) {
                    if (!data || data.length < 1) return '';
                    if (data.length === 1) {
                        // Single point: flat line at the right edge
                        const y = h / 2;
                        return `${w},${y.toFixed(1)}`;
                    }
                    const min = Math.min(...data);
                    const max = Math.max(...data);
                    const range = max - min || 1;
                    // Right-align: step backwards from the right edge so newest = rightmost
                    const maxPoints = 20;
                    const xStep = w / (maxPoints - 1);
                    const startX = w - (data.length - 1) * xStep;
                    return data.map((v, i) => {
                        const x = startX + i * xStep;
                        const y = pad + (1 - (v - min) / range) * (h - pad * 2);
                        return `${x.toFixed(1)},${y.toFixed(1)}`;
                    }).join(' ');
                },

                formatLastActive(timestamp) {
                    const now = Math.floor(Date.now() / 1000);
                    const diff = now - timestamp;
                    if (diff < 60) return 'Just now';
                    const mins = Math.floor(diff / 60);
                    if (mins < 60) return mins + 'm ago';
                    const hours = Math.floor(mins / 60);
                    if (hours < 24) return hours + 'h ago';
                    return Math.floor(hours / 24) + 'd ago';
                },

                getScopeColor(id) {
                    const scopeColorMap = {
                        'app_drawing': '#0c4da2',     // Brand Blue
                        'app_inventory': '#10b981',   // Emerald
                        'app_npc': '#f59e0b',         // Amber
                        'app_dashboard': '#6366f1'     // Indigo
                    };
                    return scopeColorMap[id] || '#64748b';
                },

                init() {
                    this.initChart();
                },

                initChart() {
                    const ctx = document.getElementById('scopeDistributionChart').getContext('2d');
                    
                    const labels = this.scopeChartData.map(d => d.label);
                    const counts = this.scopeChartData.map(d => d.count);
                    const backgroundColors = this.scopeChartData.map(d => this.getScopeColor(d.id));

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    data: counts,
                                    backgroundColor: backgroundColors,
                                    hoverBackgroundColor: backgroundColors,
                                    borderColor: '#ffffff',
                                    borderWidth: 2,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            };
        }
    </script>
</x-app-layout>

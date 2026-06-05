<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            
            <h2 class="text-sm font-semibold text-gray-900 tracking-wide ">User Management Dashboard</h2>
        </div>
    </x-slot>

    <!-- Load Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="px-6 py-4 flex flex-col gap-4 h-auto lg:h-[calc(100vh-120px)] lg:max-h-[calc(100vh-120px)] overflow-y-auto lg:overflow-hidden" x-data="dashboardConsole()">
        
        <!-- Summary Stats Grid (High Density - 6 Items) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 shrink-0">
            <!-- Total Users -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Total Users</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_users"></span>
                </div>
            </div>
            
            <!-- Active Users -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-user-check text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Active Users</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.active_users"></span>
                </div>
            </div>

            <!-- Custom Overrides -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Custom Overrides</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_overrides"></span>
                </div>
            </div>

            <!-- Active Scopes -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-cubes text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Active Scopes</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_scopes"></span>
                </div>
            </div>

            <!-- Access Roles -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Access Roles</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_roles"></span>
                </div>
            </div>

            <!-- Departments -->
            <div class="bg-white border border-gray-200 p-3.5 flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.02)] ">
                <div class="w-9 h-9 bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200/50 rounded-xs shadow-xs">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-wider block">Departments</span>
                    <span class="text-sm font-bold text-gray-900 mt-0.5 block" x-text="stats.total_departments"></span>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Workspace -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 min-h-0">
            
            <!-- Left Side: User Application & Department Mapping -->
            <div class="lg:col-span-8 flex flex-col gap-4 min-h-0 h-full">
                
                <!-- Chart Card: User Scope Distribution -->
                <div class="bg-white border border-gray-200 flex-1 min-h-0 flex flex-col shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-gray-200 bg-gray-50 flex items-center gap-2 shrink-0">
                        
                        <span class="text-xs font-bold tracking-wider text-slate-500">User Scope Access Allocations</span>
                    </div>
                    <div class="flex-1 min-h-0 py-2.5 px-4 flex flex-col sm:flex-row items-center gap-12 justify-center">
                        <!-- Doughnut Container -->
                        <div class="relative w-48 h-48 shrink-0">
                            <canvas id="scopeDistributionChart"></canvas>
                            <!-- Center Label inside Doughnut -->
                            <div class="absolute flex flex-col items-center justify-center pointer-events-none" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <span class="text-xs font-bold text-slate-400 tracking-wider leading-none">Total Access</span>
                                <span class="text-2xl font-extrabold text-gray-900 mt-1" x-text="scopeChartData.reduce((acc, curr) => acc + curr.count, 0)"></span>
                            </div>
                        </div>
                        
                        <!-- Custom Legend -->
                        <div class="flex-1 space-y-3.5 max-w-md w-full">
                            <template x-for="(scope, index) in scopeChartData" :key="scope.id">
                                <div class="flex items-center justify-between text-sm border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3.5 h-3.5 rounded-full shrink-0" :style="'background-color: ' + getScopeColor(scope.id)"></div>
                                        <span class="font-semibold text-gray-900 truncate max-w-64" x-text="scope.label"></span>
                                    </div>
                                    <span class="font-bold text-gray-900" x-text="scope.count + ' Users'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Departments & Roles Breakdown Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1 min-h-0">
                    <!-- Department Breakdown Progress Meters -->
                    <div class="bg-white border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col min-h-0">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gray-50 flex items-center gap-2 shrink-0">
                            <span class="text-xs font-bold tracking-wider text-slate-500">Top Departments</span>
                        </div>
                        <div class="p-4 space-y-3.5 flex-1 overflow-y-auto custom-scrollbar">
                            <template x-for="dept in departmentBreakdown">
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold text-gray-900 mb-1">
                                        <span class="truncate w-36 font-medium" x-text="dept.name + ' (' + dept.code + ')'"></span>
                                        <span class="font-bold text-gray-900" x-text="dept.user_count + ' Users'"></span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-[#0c4da2] transition-all duration-500" :style="'width: ' + ((dept.user_count / stats.total_users) * 100) + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
 
                    <!-- Role Breakdown Progress Meters -->
                    <div class="bg-white border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col min-h-0">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gray-50 flex items-center gap-2 shrink-0">
                            <span class="text-xs font-bold tracking-wider text-slate-500">Popular Roles</span>
                        </div>
                        <div class="p-4 space-y-3.5 flex-1 overflow-y-auto custom-scrollbar">
                            <template x-for="role in roleBreakdown">
                                <div>
                                    <div class="flex justify-between items-center text-xs font-semibold text-gray-900 mb-1">
                                        <span class="truncate w-36 font-medium" x-text="role.role_name"></span>
                                        <span class="font-bold text-gray-900" x-text="role.user_count + ' Users'"></span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-[#0c4da2] transition-all duration-500" :style="'width: ' + ((role.user_count / stats.total_users) * 100) + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side: Recent Registered Users & Custom Overrides -->
            <div class="lg:col-span-4 flex flex-col gap-4 min-h-0 h-full">
                <!-- Latest Enrollments & Online Users Tabs -->
                <div class="bg-white border border-gray-200 flex-1 flex flex-col min-h-0 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 border-b border-gray-200 bg-gray-50 flex items-center shrink-0">
                        <div class="flex items-center gap-4">
                            <!-- Tab 1: Enrollments -->
                            <button @click="rightTab = 'enrollments'" 
                                    :class="rightTab === 'enrollments' ? 'border-[#0c4da2] text-[#0c4da2]' : 'border-transparent text-slate-400 hover:text-gray-900'" 
                                    class="py-2.5 border-b-2 font-bold tracking-wider text-xs focus:outline-none transition-all">
                                Latest Enrollments
                            </button>
                            <!-- Tab 2: Online Users -->
                            <button @click="rightTab = 'online'" 
                                    :class="rightTab === 'online' ? 'border-[#0c4da2] text-[#0c4da2]' : 'border-transparent text-slate-400 hover:text-gray-900'" 
                                    class="py-2.5 border-b-2 font-bold tracking-wider text-xs focus:outline-none flex items-center gap-1.5 transition-all">
                                <span>Online Users</span>
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span class="text-xs bg-emerald-50 text-emerald-600 px-1 rounded-xs font-bold" x-text="stats.online_users"></span>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <!-- Enrollments Tab Content -->
                        <div x-show="rightTab === 'enrollments'">
                            <table class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-200 text-xs font-bold text-gray-400 tracking-wider sticky top-0 backdrop-blur-xs">
                                        <th class="py-2.5 px-4">User Details</th>
                                        <th class="py-2.5 px-3">NIK</th>
                                        <th class="py-2.5 px-4 text-right">Registered</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($recentUsers as $user)
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-3 px-4 font-medium text-gray-900">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 bg-[#0c4da2] text-white flex items-center justify-center font-bold text-xs rounded-xs">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="truncate w-32 font-bold text-gray-900 text-xs leading-tight" title="{{ $user->name }}">{{ $user->name }}</div>
                                                        <div class="text-xs text-slate-400 truncate w-32 mt-0.5 leading-none" title="{{ $user->dept_name ?? 'No Department' }}">
                                                            {{ $user->dept_code ? '[' . $user->dept_code . '] ' . $user->dept_name : 'No Department' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-slate-500 text-xs">{{ $user->nik }}</td>
                                            <td class="py-3 px-4 text-right text-slate-400 text-xs">{{ $user->created_at ? $user->created_at->format('m-d H:i') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Online Users Tab Content -->
                        <div x-show="rightTab === 'online'" x-cloak>
                            <table class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-200 text-xs font-bold text-gray-400 tracking-wider sticky top-0 backdrop-blur-xs">
                                        <th class="py-2.5 px-4">User Details</th>
                                        <th class="py-2.5 px-3">NIK</th>
                                        <th class="py-2.5 px-4 text-right">Last Active</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-if="onlineUsers.length === 0">
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-slate-400 text-xs font-light">
                                                No users currently online.
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="user in onlineUsers" :key="user.nik">
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-3 px-4 font-medium text-gray-900">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 bg-emerald-500 text-white flex items-center justify-center font-bold text-xs rounded-xs">
                                                        <span x-text="user.name.substring(0, 1).toUpperCase()"></span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="truncate w-32 font-bold text-gray-900 text-xs leading-tight" :title="user.name" x-text="user.name"></div>
                                                        <div class="text-xs text-slate-400 truncate w-32 mt-0.5 leading-none" :title="user.dept_name || 'No Dept'" x-text="user.dept_name || 'No Dept'"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-slate-500 text-xs" x-text="user.nik"></td>
                                            <td class="py-3 px-4 text-right">
                                                <span class="inline-block px-1.5 py-0.5 rounded-xs bg-emerald-50 text-emerald-600 font-bold text-xs border border-emerald-100" x-text="formatLastActive(user.last_activity)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Custom Override Users -->
                <div class="bg-white border border-gray-200 flex-1 flex flex-col min-h-0 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-gray-200 bg-gray-50 flex items-center gap-2 shrink-0">
                        
                        <span class="text-xs font-bold tracking-wider text-slate-500">Active Custom Overrides</span>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-200 text-xs font-bold text-gray-400 tracking-wider sticky top-0 backdrop-blur-xs">
                                    <th class="py-2.5 px-4">User Details</th>
                                    <th class="py-2.5 px-3">NIK</th>
                                    <th class="py-2.5 px-4 text-right">Overrides</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-if="overrideUsers.length === 0">
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-slate-400 text-xs font-light">
                                            No active custom overrides.
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="user in overrideUsers" :key="user.nik">
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 bg-rose-500 text-white flex items-center justify-center font-bold text-xs rounded-xs">
                                                    <span x-text="user.name.substring(0, 1).toUpperCase()"></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="truncate w-32 font-bold text-gray-900 text-xs leading-tight" :title="user.name" x-text="user.name"></div>
                                                    <div class="text-xs text-slate-400 truncate w-32 mt-0.5 leading-none" :title="user.dept_code || 'No Dept'" x-text="user.dept_code || 'No Dept'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 text-slate-500 text-xs" x-text="user.nik"></td>
                                        <td class="py-3 px-4 text-right">
                                            <span class="inline-block px-1.5 py-0.5 rounded-xs bg-rose-50 text-rose-600 font-bold text-xs border border-rose-100" x-text="user.override_count + ' Rules'"></span>
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
                rightTab: 'enrollments',
                system: {
                    uptime: '02d 14h'
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

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-5 bg-sky-500"></div>
            <h2 class="text-sm font-semibold text-gray-800 tracking-wide uppercase">User Management Dashboard</h2>
        </div>
    </x-slot>

    <!-- Load Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="px-6 py-4 flex flex-col gap-4 h-auto lg:h-[calc(100vh-120px)] lg:max-h-[calc(100vh-120px)] overflow-y-auto lg:overflow-hidden" x-data="dashboardConsole()">
        
        <!-- Summary Stats Grid (High Density) -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 shrink-0">
            <div class="bg-white border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 rounded-xs">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider block">Total Users</span>
                    <span class="text-xs font-semibold text-gray-900 mt-0.5 block" x-text="stats.total_users"></span>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 rounded-xs">
                    <i class="fa-solid fa-user-check text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider block">Active Users</span>
                    <span class="text-xs font-semibold text-gray-900 mt-0.5 block" x-text="stats.active_users"></span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 rounded-xs">
                    <i class="fa-solid fa-user-slash text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider block">Inactive Users</span>
                    <span class="text-xs font-semibold text-gray-900 mt-0.5 block" x-text="stats.inactive_users"></span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 rounded-xs">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider block">Access Roles</span>
                    <span class="text-xs font-semibold text-gray-900 mt-0.5 block" x-text="stats.total_roles"></span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 rounded-xs">
                    <i class="fa-solid fa-building text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider block">Departments</span>
                    <span class="text-xs font-semibold text-gray-900 mt-0.5 block" x-text="stats.total_departments"></span>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Workspace -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 min-h-0">
            
            <!-- Left Side: User Application & Department Mapping -->
            <div class="lg:col-span-8 flex flex-col gap-4 min-h-0">
                
                <!-- Chart Card: User Scope Distribution -->
                <div class="bg-white border border-gray-200 flex-1 min-h-0 flex flex-col">
                    <div class="px-4 py-2 border-b border-gray-200 bg-gray-50 flex items-center justify-between shrink-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">User Scope Access Allocations</span>
                    </div>
                    <div class="flex-1 min-h-0 p-4 relative flex items-center justify-center">
                        <canvas id="scopeDistributionChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>

                <!-- Department Breakdown Progress Meters -->
                <div class="bg-white border border-gray-200 shrink-0">
                    <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">User Distribution by Top Departments</span>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3">
                        <template x-for="dept in departmentBreakdown">
                            <div>
                                <div class="flex justify-between items-center text-[10px] font-semibold text-slate-600 mb-1">
                                    <span class="truncate w-36" x-text="dept.name + ' (' + dept.code + ')'"></span>
                                    <span class="font-mono font-bold text-slate-800" x-text="dept.user_count + ' Users'"></span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-sky-500 transition-all duration-500" :style="'width: ' + ((dept.user_count / stats.total_users) * 100) + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Right Side: Recent Registered Users -->
            <div class="lg:col-span-4 bg-white border border-gray-200 flex flex-col min-h-0">
                <div class="px-4 py-2 border-b border-gray-200 bg-gray-50 shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Latest Enrollments</span>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-[9px] font-bold text-gray-400 uppercase tracking-wider sticky top-0">
                                <th class="py-2 px-4">User Details</th>
                                <th class="py-2 px-3">NIK</th>
                                <th class="py-2 px-4 text-right">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentUsers as $user)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-2.5 px-4 font-medium text-slate-700">
                                        <div class="truncate w-36 font-semibold" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate w-36" title="{{ $user->dept_name ?? 'No Department' }}">
                                            {{ $user->dept_code ? '[' . $user->dept_code . '] ' . $user->dept_name : 'No Department' }}
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 font-mono text-slate-500">{{ $user->nik }}</td>
                                    <td class="py-2.5 px-4 text-right font-mono text-slate-400">{{ $user->created_at ? $user->created_at->format('m-d H:i') : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                system: {
                    uptime: '02d 14h'
                },

                init() {
                    this.initChart();
                },

                initChart() {
                    const ctx = document.getElementById('scopeDistributionChart').getContext('2d');
                    
                    const labels = this.scopeChartData.map(d => d.label);
                    const counts = this.scopeChartData.map(d => d.count);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Enrolled Users',
                                    data: counts,
                                    backgroundColor: 'rgba(14, 165, 233, 0.7)',
                                    hoverBackgroundColor: '#0ea5e9',
                                    borderColor: '#0284c7',
                                    borderWidth: 1.5,
                                    borderRadius: 3,
                                    barThickness: 32
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f8fafc' },
                                    ticks: {
                                        font: { size: 9, family: 'Inter' },
                                        color: '#94a3b8',
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { size: 10, family: 'Inter', weight: 'bold' },
                                        color: '#64748b'
                                    }
                                }
                            }
                        }
                    });
                }
            };
        }
    </script>
</x-app-layout>

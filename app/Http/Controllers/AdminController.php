<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedDepts = $request->input('depts', []);
        $selectedRoles = $request->input('roles', []);
        $selectedStatuses = $request->input('statuses', []);
        $selectedScopes = $request->input('scopes', []);

        if (is_string($selectedDepts)) $selectedDepts = array_filter(explode(',', $selectedDepts));
        if (is_string($selectedRoles)) $selectedRoles = array_filter(explode(',', $selectedRoles));
        if (is_string($selectedStatuses)) $selectedStatuses = array_filter(explode(',', $selectedStatuses));
        if (is_string($selectedScopes)) $selectedScopes = array_filter(explode(',', $selectedScopes));

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(!empty($selectedDepts), function ($query) use ($selectedDepts) {
                return $query->whereIn('id_dept', $selectedDepts);
            })
            ->when(!empty($selectedStatuses), function ($query) use ($selectedStatuses) {
                return $query->where(function ($q) use ($selectedStatuses) {
                    if (in_array('active', $selectedStatuses)) {
                        $q->orWhere('is_active', 1);
                    }
                    if (in_array('inactive', $selectedStatuses)) {
                        $q->orWhere('is_active', 0);
                    }
                });
            })
            ->when(!empty($selectedRoles), function ($query) use ($selectedRoles) {
                return $query->whereExists(function ($q) use ($selectedRoles) {
                    $q->select(\DB::raw(1))
                      ->from('user_scope_roles')
                      ->whereColumn('user_scope_roles.user_id', 'users.id')
                      ->whereIn('user_scope_roles.role_id', $selectedRoles);
                });
            })
            ->when(!empty($selectedScopes), function ($query) use ($selectedScopes) {
                return $query->whereExists(function ($sq) use ($selectedScopes) {
                    $sq->select(\DB::raw(1))
                      ->from('user_scope_roles')
                      ->whereColumn('user_scope_roles.user_id', 'users.id')
                      ->whereIn('user_scope_roles.scope_id', $selectedScopes);
                });
            })
            ->paginate(15)
            ->withQueryString();

        // Get user role assignments
        $userIds = $users->pluck('id')->toArray();
        $userScopeRoles = \DB::table('user_scope_roles')
            ->join('roles', 'roles.id', '=', 'user_scope_roles.role_id')
            ->whereIn('user_id', $userIds)
            ->select('user_scope_roles.user_id', 'user_scope_roles.scope_id', 'user_scope_roles.role_id', 'roles.role_name')
            ->get()
            ->groupBy('user_id');

        $totalOverall = User::count();

        if ($request->wantsJson()) {
            return response()->json([
                'users' => $users->items(),
                'next_page_url' => $users->nextPageUrl(),
                'user_scope_roles' => $userScopeRoles,
                'total' => $users->total(),
                'total_overall' => $totalOverall,
            ]);
        }

        $scopes = \DB::table('scopes')->where('is_active', 1)->get();
        $roles = \DB::table('roles')
            ->select('id', 'role_name', 'scope_id')
            ->get();
        $departments = \DB::table('departments')->get();

        return view('admin.index', compact('users', 'scopes', 'roles', 'userScopeRoles', 'departments', 'totalOverall'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user_id,
            'nik' => 'required|string|max:50|unique:users,nik,' . $request->user_id,
            'id_dept' => 'nullable|integer|exists:departments,id',
            'password' => 'nullable|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($data['user_id']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->nik = $data['nik'];
        $user->id_dept = $data['id_dept'];
        $user->is_active = $data['is_active'];

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'User profile updated successfully']);
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'nik' => 'required|string|max:50|unique:users,nik',
            'id_dept' => 'nullable|integer|exists:departments,id',
            'password' => 'required|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'success' => true, 
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    public function updateScopeRole(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'scope_id' => 'nullable|string|exists:scopes,id',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'required|boolean',
        ]);

        $userId = $data['user_id'];
        $scopeId = $data['scope_id'];
        $roleId = $data['role_id'];
        $status = $data['status'];

        $role = \DB::table('roles')->where('id', $roleId)->first();
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }
        $isGlobal = is_null($role->scope_id);

        if (!$isGlobal && !$scopeId) {
            return response()->json(['success' => false, 'message' => 'Scope is required for scope-specific roles'], 400);
        }

        \DB::transaction(function () use ($userId, $scopeId, $roleId, $status, $isGlobal) {
            $scopes = $isGlobal 
                ? \DB::table('scopes')->where('is_active', 1)->pluck('id')->toArray() 
                : [$scopeId];

            foreach ($scopes as $scId) {
                if ($status) {
                    $existsRole = \DB::table('user_scope_roles')
                        ->where('user_id', $userId)
                        ->where('scope_id', $scId)
                        ->where('role_id', $roleId)
                        ->exists();
                    if (!$existsRole) {
                        \DB::table('user_scope_roles')->insert([
                            'user_id' => $userId,
                            'scope_id' => $scId,
                            'role_id' => $roleId
                        ]);
                    }
                } else {
                    \DB::table('user_scope_roles')
                        ->where('user_id', $userId)
                        ->where('scope_id', $scId)
                        ->where('role_id', $roleId)
                        ->delete();
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'User scope and role updated successfully']);
    }

    public function bulkUpdateAccess(Request $request)
    {
        $data = $request->validate([
            'app' => 'required|string|exists:scopes,id',
            'status' => 'required|boolean',
        ]);

        $users = User::all();
        $scopeId = $data['app'];

        \DB::transaction(function () use ($users, $data, $scopeId) {
            foreach ($users as $user) {
                // 1. Legacy update removed for simplification

                // 2. Unified scope-role update
                if ($data['status']) {
                    $hasRole = \DB::table('user_scope_roles')
                        ->where('user_id', $user->id)
                        ->where('scope_id', $scopeId)
                        ->exists();
                        
                    if (!$hasRole) {
                        $defaultRoleId = 1;
                        if ($scopeId === 'app_inventory') {
                            $defaultRoleId = \DB::table('roles')
                                ->where('role_name', 'like', 'Inv%')
                                ->value('id') ?? 1;
                        } else {
                            $defaultRoleId = \DB::table('roles')
                                ->where('role_name', 'not like', 'Inv%')
                                ->value('id') ?? 1;
                        }
                        
                        \DB::table('user_scope_roles')->updateOrInsert([
                            'user_id' => $user->id,
                            'scope_id' => $scopeId,
                            'role_id' => $defaultRoleId,
                        ]);
                    }
                } else {
                    \DB::table('user_scope_roles')
                        ->where('user_id', $user->id)
                        ->where('scope_id', $scopeId)
                        ->delete();
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Bulk access updated successfully']);
    }

    public function getUserPermissions($userId)
    {
        $user = User::findOrFail($userId);

        // Fetch all active menus sorted by parent and order
        $menus = \App\Models\Menu::getSortedHierarchy(null, true);

        // Fetch all permissions
        $permissions = \DB::table('permissions')->get();

        // Fetch current user scope role assignments
        $assignments = \DB::table('user_scope_roles')
            ->join('roles', 'roles.id', '=', 'user_scope_roles.role_id')
            ->where('user_id', $userId)
            ->select('user_scope_roles.user_id', 'user_scope_roles.scope_id', 'user_scope_roles.role_id', 'roles.role_name')
            ->get();

        // Fetch role permissions for this user
        $rolePermissions = \DB::table('user_scope_roles')
            ->join('role_scope_permissions', function ($join) {
                $join->on('user_scope_roles.role_id', '=', 'role_scope_permissions.role_id')
                     ->on('user_scope_roles.scope_id', '=', 'role_scope_permissions.scope_id');
            })
            ->where('user_scope_roles.user_id', $userId)
            ->select('role_scope_permissions.scope_id', 'role_scope_permissions.menu_id', 'role_scope_permissions.permission_id')
            ->get();

        // Fetch user specific overrides
        $userOverrides = \DB::table('user_scope_permissions')
            ->where('user_id', $userId)
            ->select('scope_id', 'menu_id', 'permission_id', 'access_type')
            ->get();

        return response()->json([
            'menus' => $menus,
            'permissions' => $permissions,
            'role_permissions' => $rolePermissions,
            'overrides' => $userOverrides,
            'assignments' => $assignments,
        ]);
    }

    public function updateUserPermission(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'scope_id' => 'required|string|exists:scopes,id',
            'menu_id' => 'required|integer|exists:menus,id',
            'permission_id' => 'required|integer|exists:permissions,id',
            'access_type' => 'required|string|in:ALLOW,DENY,INHERIT',
        ]);

        $userId = $data['user_id'];
        $scopeId = $data['scope_id'];
        $menuId = $data['menu_id'];
        $permissionId = $data['permission_id'];
        $accessType = $data['access_type'];

        if ($accessType === 'INHERIT') {
            \DB::table('user_scope_permissions')
                ->where('user_id', $userId)
                ->where('scope_id', $scopeId)
                ->where('menu_id', $menuId)
                ->where('permission_id', $permissionId)
                ->delete();
        } else {
            \DB::table('user_scope_permissions')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'scope_id' => $scopeId,
                    'menu_id' => $menuId,
                    'permission_id' => $permissionId,
                ],
                [
                    'access_type' => $accessType,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Override permission updated successfully']);
    }

    public function mastersIndex()
    {
        return view('admin.masters');
    }

    public function departmentsAjax(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        $query = \DB::table('departments');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $filteredRecords = (clone $query)->count();

        $data = $query->orderBy('code')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function scopesAjax(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        $query = \DB::table('scopes');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('scope_name', 'like', "%{$search}%");
            });
        }

        $filteredRecords = (clone $query)->count();

        $data = $query->orderBy('id')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function permissionsAjax(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        $query = \DB::table('permissions');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('permission_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $filteredRecords = (clone $query)->count();

        $data = $query->orderBy('permission_name')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:departments,code',
            'name' => 'required|string|max:255',
        ]);

        \DB::table('departments')->insert([
            'code' => $data['code'],
            'name' => $data['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Department created successfully']);
    }

    public function updateDepartment(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:departments,code,' . $id,
            'name' => 'required|string|max:255',
        ]);

        \DB::table('departments')
            ->where('id', $id)
            ->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Department updated successfully']);
    }

    public function destroyDepartment($id)
    {
        \DB::table('departments')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', 1)->count(),
            'total_roles' => \DB::table('roles')->count(),
            'total_scopes' => \DB::table('scopes')->where('is_active', 1)->count(),
            'total_departments' => \DB::table('departments')->count(),
            'total_overrides' => \DB::table('user_scope_permissions')->distinct('user_id')->count('user_id'),
        ];

        // 1. User allocation per application scope
        $scopes = \DB::table('scopes')->where('is_active', 1)->get();
        $scopeChartData = [];
        foreach ($scopes as $scope) {
            $userCount = \DB::table('user_scope_roles')
                ->where('scope_id', $scope->id)
                ->distinct('user_id')
                ->count();
            $scopeChartData[] = [
                'id' => $scope->id,
                'label' => $scope->scope_name,
                'count' => $userCount
            ];
        }

        // 2. User allocation per department
        $departmentBreakdown = \DB::table('users')
            ->join('departments', 'departments.id', '=', 'users.id_dept')
            ->select('departments.name', 'departments.code', \DB::raw('count(users.id) as user_count'))
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderBy('user_count', 'desc')
            ->take(5)
            ->get();

        // 3. Role distribution breakdown
        $roleBreakdown = \DB::table('user_scope_roles')
            ->join('roles', 'roles.id', '=', 'user_scope_roles.role_id')
            ->select('roles.role_name', \DB::raw('count(distinct user_id) as user_count'))
            ->groupBy('roles.id', 'roles.role_name')
            ->orderBy('user_count', 'desc')
            ->take(6)
            ->get();

        // 4. Users with custom overrides
        $overrideUsers = \DB::table('user_scope_permissions')
            ->join('users', 'users.id', '=', 'user_scope_permissions.user_id')
            ->leftJoin('departments', 'departments.id', '=', 'users.id_dept')
            ->select('users.name', 'users.nik', 'departments.code as dept_code', \DB::raw('count(*) as override_count'))
            ->groupBy('users.id', 'users.name', 'users.nik', 'departments.code')
            ->orderBy('override_count', 'desc')
            ->take(5)
            ->get();

        // 5. Active online users (last 15 minutes)
        $activeTime = time() - (15 * 60);
        $onlineUsers = \DB::table('sessions')
            ->join('users', function ($join) {
                $join->on(\DB::raw('CAST(users.id AS VARCHAR(50))'), '=', 'sessions.user_id')
                     ->orOn('users.nik', '=', 'sessions.user_id');
            })
            ->leftJoin('departments', 'departments.id', '=', 'users.id_dept')
            ->where('sessions.last_activity', '>=', $activeTime)
            ->select('users.name', 'users.nik', 'departments.code as dept_code', 'departments.name as dept_name', \DB::raw('MAX(sessions.last_activity) as last_activity'))
            ->groupBy('users.name', 'users.nik', 'departments.code', 'departments.name')
            ->orderBy('last_activity', 'desc')
            ->get();

        $stats['online_users'] = $onlineUsers->count();

        $recentUsers = User::leftJoin('departments', 'departments.id', '=', 'users.id_dept')
            ->select('users.*', 'departments.name as dept_name', 'departments.code as dept_code')
            ->orderBy('users.created_at', 'desc')
            ->take(5)
            ->get();

        $systemMetrics = \Cache::get('dashboard.system_metrics');
        $initialUptime = $systemMetrics['uptime'] ?? '00d 00h';

        return view('admin.dashboard', compact('stats', 'scopeChartData', 'departmentBreakdown', 'roleBreakdown', 'overrideUsers', 'recentUsers', 'onlineUsers', 'initialUptime'));
    }

    public function getRealMetrics()
    {
        // ── DB Latency: always fresh (lightweight, just a SELECT 1) ────────────
        $dbStart   = microtime(true);
        \DB::select('SELECT 1');
        $dbLatency = round((microtime(true) - $dbStart) * 1000, 1);

        // ── CPU + RAM: cached 5 seconds ─────────────────────────────────────────
        // Spawning PowerShell is ~300ms overhead + 1s for Get-Counter sampling.
        // Caching means concurrent requests share one result instead of each
        // spawning their own process.
        $systemMetrics = \Cache::remember('dashboard.system_metrics', 5, function () {
            $defaults = ['cpu' => 15, 'totalMem' => 0, 'freeMem' => 0];
            try {
                $psScript = storage_path('app/get_system_stats_fast.ps1');
                $cmd      = 'powershell -NoProfile -NonInteractive -ExecutionPolicy Bypass -File "' . $psScript . '"';
                $output   = shell_exec($cmd);
                if ($output) {
                    $data = json_decode($output, true);
                    if (is_array($data)) {
                        return array_merge($defaults, $data);
                    }
                }
            } catch (\Exception $e) { /* quiet fallback */ }
            return $defaults;
        });

        // ── Disk: cached 30 seconds (disk usage changes slowly) ────────────────
        $diskMetrics = \Cache::remember('dashboard.disk_metrics', 30, function () {
            $defaults = ['diskUsedGB' => 0.0, 'diskTotalGB' => 0.0, 'diskPct' => 0];
            try {
                $psScript = storage_path('app/get_disk_stats.ps1');
                $cmd      = 'powershell -NoProfile -NonInteractive -ExecutionPolicy Bypass -File "' . $psScript . '"';
                $output   = shell_exec($cmd);
                if ($output) {
                    $data = json_decode($output, true);
                    if (is_array($data)) {
                        return array_merge($defaults, $data);
                    }
                }
            } catch (\Exception $e) { /* quiet fallback */ }
            return $defaults;
        });

        // ── Parse CPU / RAM ────────────────────────────────────────────────────
        $cpu      = (int)($systemMetrics['cpu'] ?? 15);
        $cpuSpeed = round((float)($systemMetrics['cpuSpeedGHz'] ?? 0.0), 2);
        $totalRAM = 16.0;
        $usedRAM  = 6.2;
        $freeRAM  = 9.8;
        if (!empty($systemMetrics['totalMem']) && !empty($systemMetrics['freeMem'])) {
            $totalRAM = round((float)$systemMetrics['totalMem'] / (1024 * 1024), 1);
            $freeRAM  = round((float)$systemMetrics['freeMem']  / (1024 * 1024), 1);
            $usedRAM  = round($totalRAM - $freeRAM, 1);
        }

        // ── Active online users (last 15 minutes) ──────────────────────────────
        $activeTime = time() - (15 * 60);
        $onlineUsers = \DB::table('sessions')
            ->join('users', function ($join) {
                $join->on(\DB::raw('CAST(users.id AS VARCHAR(50))'), '=', 'sessions.user_id')
                     ->orOn('users.nik', '=', 'sessions.user_id');
            })
            ->leftJoin('departments', 'departments.id', '=', 'users.id_dept')
            ->where('sessions.last_activity', '>=', $activeTime)
            ->select('users.name', 'users.nik', 'departments.code as dept_code', 'departments.name as dept_name', \DB::raw('MAX(sessions.last_activity) as last_activity'))
            ->groupBy('users.name', 'users.nik', 'departments.code', 'departments.name')
            ->orderBy('last_activity', 'desc')
            ->get();

        $uptime = $systemMetrics['uptime'] ?? '00d 00h';

        return response()->json([
            'db'                 => $dbLatency,
            'cpu'                => $cpu,
            'cpu_speed'          => $cpuSpeed,
            'ram'                => $usedRAM,
            'ram_free'           => $freeRAM,
            'total_ram'          => $totalRAM,
            'disk_used'          => $diskMetrics['diskUsedGB'],
            'disk_free'          => $diskMetrics['diskFreeGB'] ?? round($diskMetrics['diskTotalGB'] - $diskMetrics['diskUsedGB'], 1),
            'disk_total'         => $diskMetrics['diskTotalGB'],
            'disk_pct'           => $diskMetrics['diskPct'],
            'online_users'       => $onlineUsers,
            'online_users_count' => $onlineUsers->count(),
            'uptime'             => $uptime,
        ])->header('Cache-Control', 'no-store');
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Scope;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rolesQuery = Role::orderBy('role_name')
            ->when($search, function($query, $search) {
                return $query->where('role_name', 'like', "%{$search}%");
            });

        $roles = $rolesQuery->paginate(15)->withQueryString();

        $roles->getCollection()->transform(function($role) {
            $role->total_scopes = DB::table('role_scope_permissions')
                ->where('role_id', $role->id)
                ->distinct('scope_id')
                ->count('scope_id');
            return $role;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'roles' => $roles->items(),
                'next_page_url' => $roles->nextPageUrl()
            ]);
        }

        $scopes = Scope::where('is_active', 1)->get();
        $permissions = DB::table('permissions')->get();
        
        // Fetch menus for permission matrix, sorted hierarchically
        $menusByScope = Menu::getSortedHierarchy(null, true)->groupBy('scope_id');

        return view('admin.roles', compact('roles', 'scopes', 'permissions', 'menusByScope'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:150|unique:roles,role_name',
            'scope_id' => 'nullable|string|exists:scopes,id',
        ]);

        Role::create($data);

        return response()->json(['success' => true, 'message' => 'Role created successfully']);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:150|unique:roles,role_name,' . $role->id,
            'scope_id' => 'nullable|string|exists:scopes,id',
        ]);

        $role->update($data);

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    public function destroy(Role $role)
    {
        DB::transaction(function () use ($role) {
            // Clean up legacy tables to avoid foreign key constraint errors
            if (DB::getSchemaBuilder()->hasTable('user_roles')) {
                DB::table('user_roles')->where('role_id', $role->id)->delete();
            }
            if (DB::getSchemaBuilder()->hasTable('inv_user_roles')) {
                DB::table('inv_user_roles')->where('role_id', $role->id)->delete();
            }
            if (DB::getSchemaBuilder()->hasTable('npc_user_roles')) {
                DB::table('npc_user_roles')->where('role_id', $role->id)->delete();
            }

            $role->delete();
        });

        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
    }

    public function getRolePermissions($roleId, $scopeId)
    {
        $rolePermissions = DB::table('role_scope_permissions')
            ->where('role_id', $roleId)
            ->where('scope_id', $scopeId)
            ->select('menu_id', 'permission_id')
            ->get();

        return response()->json([
            'role_permissions' => $rolePermissions
        ]);
    }

    public function updatePermissions(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'scope_id' => 'required|string|exists:scopes,id',
            'permissions' => 'nullable|array', // expected format: [['menu_id' => x, 'permission_id' => y], ...]
        ]);

        $roleId = $data['role_id'];
        $scopeId = $data['scope_id'];
        $permissions = $data['permissions'] ?? [];

        DB::transaction(function () use ($roleId, $scopeId, $permissions) {
            // Delete existing mappings for this role and scope
            DB::table('role_scope_permissions')
                ->where('role_id', $roleId)
                ->where('scope_id', $scopeId)
                ->delete();

            // Insert new mappings
            $insertData = [];
            foreach ($permissions as $perm) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'scope_id' => $scopeId,
                    'menu_id' => $perm['menu_id'],
                    'permission_id' => $perm['permission_id'],
                ];
            }

            if (!empty($insertData)) {
                DB::table('role_scope_permissions')->insert($insertData);
            }
        });

        return response()->json(['success' => true, 'message' => 'Permissions saved successfully']);
    }

    public function storePermission(Request $request)
    {
        $data = $request->validate([
            'permission_name' => 'required|string|max:100|unique:permissions,permission_name',
            'description' => 'nullable|string',
        ]);

        DB::table('permissions')->insert(array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json(['success' => true, 'message' => 'Permission created successfully']);
    }

    public function destroyPermission($id)
    {
        DB::table('permissions')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Permission deleted successfully']);
    }

    public function updatePermission(Request $request, $id)
    {
        $data = $request->validate([
            'permission_name' => 'required|string|max:100|unique:permissions,permission_name,' . $id,
            'description' => 'nullable|string',
        ]);

        DB::table('permissions')
            ->where('id', $id)
            ->update(array_merge($data, [
                'updated_at' => now(),
            ]));

        return response()->json(['success' => true, 'message' => 'Permission updated successfully']);
    }
}

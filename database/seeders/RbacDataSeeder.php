<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RbacDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Update existing roles scope_id
        $existingRoles = DB::table('roles')->get();
        foreach ($existingRoles as $r) {
            $scope = 'app_drawing';
            if (str_starts_with($r->role_name, 'Inv ')) {
                $scope = 'app_inventory';
            } elseif (str_starts_with($r->role_name, 'Dashboard ')) {
                $scope = 'app_dashboard';
            } elseif ($r->role_name === 'NPC' || str_starts_with($r->role_name, 'NPC ')) {
                $scope = 'app_npc';
            }
            DB::table('roles')->where('id', $r->id)->update(['scope_id' => $scope]);
        }

        // 1. Seed Scopes
        $scopes = [
            [
                'id' => 'app_drawing',
                'scope_name' => 'Drawing Management',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 'app_inventory',
                'scope_name' => 'Inventory Management',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 'app_npc',
                'scope_name' => 'NPC Management',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 'app_dashboard',
                'scope_name' => 'Dashboard',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($scopes as $scope) {
            DB::table('scopes')->updateOrInsert(['id' => $scope['id']], $scope);
        }

        // 2. Seed Permissions
        $permissions = [
            ['permission_name' => 'view', 'description' => 'Can view pages/menus'],
            ['permission_name' => 'create', 'description' => 'Can create records'],
            ['permission_name' => 'edit', 'description' => 'Can edit records'],
            ['permission_name' => 'delete', 'description' => 'Can delete records'],
            ['permission_name' => 'upload', 'description' => 'Can upload files'],
            ['permission_name' => 'download', 'description' => 'Can download/export files'],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['permission_name' => $perm['permission_name']],
                array_merge($perm, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()])
            );
            $permissionIds[$perm['permission_name']] = DB::table('permissions')
                ->where('permission_name', $perm['permission_name'])
                ->value('id');
        }

        // 3. Copy menus from inv_m_menus to global menus table (without conflicts)
        if (DB::getSchemaBuilder()->hasTable('inv_m_menus')) {
            $invMenus = DB::table('inv_m_menus')->get();
            $isSqlSrv = DB::connection()->getDriverName() === 'sqlsrv';

            foreach ($invMenus as $invMenu) {
                $exists = DB::table('menus')->where('id', $invMenu->id)->exists();
                if (!$exists) {
                    $parentId = is_null($invMenu->parent_id) ? 'NULL' : intval($invMenu->parent_id);
                    $title = str_replace("'", "''", $invMenu->title);
                    $route = is_null($invMenu->route) ? 'NULL' : "'" . str_replace("'", "''", $invMenu->route) . "'";
                    $icon = is_null($invMenu->icon) ? 'NULL' : "'" . str_replace("'", "''", $invMenu->icon) . "'";
                    $sortOrder = intval($invMenu->order ?? 0);
                    $isActive = intval($invMenu->is_active ?? 1);

                    if ($isSqlSrv) {
                        DB::statement("
                            SET IDENTITY_INSERT menus ON;
                            INSERT INTO menus (id, parent_id, title, route, icon, sort_order, level, is_active, is_visible)
                            VALUES ({$invMenu->id}, {$parentId}, '{$title}', {$route}, {$icon}, {$sortOrder}, 1, {$isActive}, 1);
                            SET IDENTITY_INSERT menus OFF;
                        ");
                    } else {
                        DB::table('menus')->insert([
                            'id' => $invMenu->id,
                            'parent_id' => $invMenu->parent_id,
                            'title' => $invMenu->title,
                            'route' => $invMenu->route,
                            'icon' => $invMenu->icon,
                            'sort_order' => $invMenu->order ?? 0,
                            'level' => 1,
                            'is_active' => $invMenu->is_active ?? 1,
                            'is_visible' => 1,
                            'created_at' => $invMenu->created_at ?? Carbon::now(),
                            'updated_at' => $invMenu->updated_at ?? Carbon::now(),
                        ]);
                    }
                }
            }
        }

        // 4. Migrate Inventory Roles into global roles table and map IDs
        $roleIdMapping = []; // old_inv_role_id => new_global_role_id
        if (DB::getSchemaBuilder()->hasTable('inv_m_roles')) {
            $invRoles = DB::table('inv_m_roles')->get();
            foreach ($invRoles as $invRole) {
                $targetName = 'Inv ' . $invRole->name;
                $globalRole = DB::table('roles')->where('role_name', $targetName)->first();
                if (!$globalRole) {
                    $exactRole = DB::table('roles')->where('role_name', $invRole->name)->first();
                    if ($exactRole) {
                        $nameToUse = $targetName;
                    } else {
                        $nameToUse = $invRole->name;
                    }

                    $newId = DB::table('roles')->insertGetId([
                        'role_name' => $nameToUse,
                        'scope_id' => 'app_inventory',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $roleIdMapping[$invRole->id] = $newId;
                } else {
                    $roleIdMapping[$invRole->id] = $globalRole->id;
                    // Ensure scope_id is updated for global role if not set
                    DB::table('roles')->where('id', $globalRole->id)->update(['scope_id' => 'app_inventory']);
                }
            }
        }

        // 5. Migrate user roles to user_scope_roles
        if (DB::getSchemaBuilder()->hasTable('user_roles')) {
            $userRoles = DB::table('user_roles')->get();
            foreach ($userRoles as $ur) {
                // Ensure user and role exist in the database
                $userExists = DB::table('users')->where('id', $ur->user_id)->exists();
                $roleExists = DB::table('roles')->where('id', $ur->role_id)->exists();
                if (!$userExists || !$roleExists) {
                    continue;
                }

                $role = DB::table('roles')->where('id', $ur->role_id)->first();
                if ($role) {
                    $scopeId = $role->scope_id ?? 'app_drawing';
                    DB::table('user_scope_roles')->updateOrInsert([
                        'user_id' => $ur->user_id,
                        'scope_id' => $scopeId,
                        'role_id' => $ur->role_id,
                    ]);
                }
            }
        }

        if (DB::getSchemaBuilder()->hasTable('inv_user_roles') && !empty($roleIdMapping)) {
            $invUserRoles = DB::table('inv_user_roles')->get();
            foreach ($invUserRoles as $iur) {
                $userExists = DB::table('users')->where('id', $iur->user_id)->exists();
                if (!$userExists) {
                    continue;
                }

                if (isset($roleIdMapping[$iur->role_id])) {
                    $mappedRoleId = $roleIdMapping[$iur->role_id];
                    
                    // Double check role exists in global roles table
                    if (DB::table('roles')->where('id', $mappedRoleId)->exists()) {
                        DB::table('user_scope_roles')->updateOrInsert([
                            'user_id' => $iur->user_id,
                            'scope_id' => 'app_inventory',
                            'role_id' => $mappedRoleId,
                        ]);
                    }
                }
            }
        }

        // 6. Migrate role permissions to role_scope_permissions
        if (DB::getSchemaBuilder()->hasTable('role_menu')) {
            $roleMenus = DB::table('role_menu')->get();
            foreach ($roleMenus as $rm) {
                // Ensure role and menu exist
                $roleExists = DB::table('roles')->where('id', $rm->role_id)->exists();
                $menuExists = DB::table('menus')->where('id', $rm->menu_id)->exists();
                if (!$roleExists || !$menuExists) {
                    continue;
                }

                $scope = 'app_drawing';
                if (in_array($rm->menu_id, [1, 28])) {
                    $scope = 'app_dashboard';
                }

                $perms = [
                    'can_view' => 'view',
                    'can_upload' => 'upload',
                    'can_download' => 'download',
                    'can_delete' => 'delete',
                ];

                foreach ($perms as $col => $permName) {
                    if (isset($rm->$col) && $rm->$col) {
                        $permId = $permissionIds[$permName] ?? null;
                        if ($permId) {
                            if (is_null($rm->user_id)) {
                                DB::table('role_scope_permissions')->updateOrInsert([
                                    'role_id' => $rm->role_id,
                                    'scope_id' => $scope,
                                    'menu_id' => $rm->menu_id,
                                    'permission_id' => $permId,
                                ]);
                            } else {
                                // If user_id is set, verify user exists
                                $userExists = DB::table('users')->where('id', $rm->user_id)->exists();
                                if ($userExists) {
                                    DB::table('user_scope_permissions')->updateOrInsert([
                                        'user_id' => $rm->user_id,
                                        'scope_id' => $scope,
                                        'menu_id' => $rm->menu_id,
                                        'permission_id' => $permId,
                                        'access_type' => 'ALLOW',
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (DB::getSchemaBuilder()->hasTable('inv_role_menus') && !empty($roleIdMapping)) {
            $invRoleMenus = DB::table('inv_role_menus')->get();
            foreach ($invRoleMenus as $irm) {
                if (isset($roleIdMapping[$irm->role_id])) {
                    $mappedRoleId = $roleIdMapping[$irm->role_id];

                    // Verify role and menu exist
                    $roleExists = DB::table('roles')->where('id', $mappedRoleId)->exists();
                    $menuExists = DB::table('menus')->where('id', $irm->menu_id)->exists();
                    if (!$roleExists || !$menuExists) {
                        continue;
                    }

                    $perms = [
                        'can_view' => 'view',
                        'can_create' => 'create',
                        'can_edit' => 'edit',
                        'can_delete' => 'delete',
                    ];

                    $hasSpecificCols = isset($irm->can_view) || isset($irm->can_create) || isset($irm->can_edit) || isset($irm->can_delete);

                    if (!$hasSpecificCols) {
                        // Fallback: If no permission columns are present, treat mapping as 'view' access
                        $permId = $permissionIds['view'] ?? null;
                        if ($permId) {
                            DB::table('role_scope_permissions')->updateOrInsert([
                                'role_id' => $mappedRoleId,
                                'scope_id' => 'app_inventory',
                                'menu_id' => $irm->menu_id,
                                'permission_id' => $permId,
                            ]);
                        }
                    } else {
                        foreach ($perms as $col => $permName) {
                            if (isset($irm->$col) && $irm->$col) {
                                $permId = $permissionIds[$permName] ?? null;
                                if ($permId) {
                                    DB::table('role_scope_permissions')->updateOrInsert([
                                        'role_id' => $mappedRoleId,
                                        'scope_id' => 'app_inventory',
                                        'menu_id' => $irm->menu_id,
                                        'permission_id' => $permId,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (DB::getSchemaBuilder()->hasTable('inv_user_menus')) {
            $invUserMenus = DB::table('inv_user_menus')->get();
            foreach ($invUserMenus as $ium) {
                $userExists = DB::table('users')->where('id', $ium->user_id)->exists();
                $menuExists = DB::table('menus')->where('id', $ium->menu_id)->exists();
                if (!$userExists || !$menuExists) {
                    continue;
                }

                $permId = $permissionIds['view'] ?? null;
                if ($permId) {
                    DB::table('user_scope_permissions')->updateOrInsert([
                        'user_id' => $ium->user_id,
                        'scope_id' => 'app_inventory',
                        'menu_id' => $ium->menu_id,
                        'permission_id' => $permId,
                        'access_type' => 'ALLOW',
                    ]);
                }
            }
        }
    }
}

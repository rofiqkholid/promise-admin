<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find all roles where scope_id is NOT NULL
        $roles = DB::table('roles')->whereNotNull('scope_id')->get();

        foreach ($roles as $role) {
            // Delete user_scope_roles where scope_id does not match the role's scope_id
            DB::table('user_scope_roles')
                ->where('role_id', $role->id)
                ->where('scope_id', '!=', $role->scope_id)
                ->delete();

            // Delete role_scope_permissions where scope_id does not match the role's scope_id
            DB::table('role_scope_permissions')
                ->where('role_id', $role->id)
                ->where('scope_id', '!=', $role->scope_id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed/possible since deleted data was invalid.
    }
};

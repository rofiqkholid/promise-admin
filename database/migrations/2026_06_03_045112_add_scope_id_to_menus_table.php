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
        // Ensure scopes exist first to prevent foreign key conflicts during migration update
        DB::table('scopes')->updateOrInsert(['id' => 'app_drawing'], ['scope_name' => 'Drawing Management', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('scopes')->updateOrInsert(['id' => 'app_inventory'], ['scope_name' => 'Inventory Management', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('scopes')->updateOrInsert(['id' => 'app_npc'], ['scope_name' => 'NPC Management', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('scopes')->updateOrInsert(['id' => 'app_dashboard'], ['scope_name' => 'Dashboard', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);

        Schema::table('menus', function (Blueprint $table) {
            $table->string('scope_id', 50)->nullable()->after('parent_id');
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('set null');
        });

        // Auto-assign existing menus to scopes
        // 1. Inventory Management
        DB::table('menus')
            ->where(function($query) {
                $query->whereBetween('id', [1487, 1528])
                      ->orWhere('route', 'like', 'inventory.%')
                      ->orWhere('route', 'like', 'transactionHistory%')
                      ->orWhere('route', '=', 'inventory.userAccess.index');
            })
            ->update(['scope_id' => 'app_inventory']);

        // 2. Dashboard
        DB::table('menus')
            ->whereIn('id', [1, 28])
            ->orWhere('route', 'dashboard')
            ->update(['scope_id' => 'app_dashboard']);

        // 3. Drawing Management (default fallback for remaining items)
        DB::table('menus')
            ->whereNull('scope_id')
            ->update(['scope_id' => 'app_drawing']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['scope_id']);
            $table->dropColumn('scope_id');
        });
    }
};

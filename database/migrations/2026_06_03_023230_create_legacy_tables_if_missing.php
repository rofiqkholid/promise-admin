<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create roles table if it does not exist
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('role_name', 150)->unique();
                $table->timestamps();
            });
        }

        // Create menus table if it does not exist
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('parent_id')->nullable();
                $table->string('title', 150);
                $table->string('route', 150)->nullable();
                $table->string('icon', 100)->nullable();
                $table->integer('sort_order')->default(0);
                $table->integer('level')->default(1);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_visible')->default(true);
                $table->timestamps();
            });
        }

        // Create t1000_sso_user_access_app table if it does not exist
        if (!Schema::hasTable('t1000_sso_user_access_app')) {
            Schema::create('t1000_sso_user_access_app', function (Blueprint $table) {
                $table->integer('id_user')->primary();
                $table->boolean('app_drawing')->default(false);
                $table->boolean('app_inventory')->default(false);
                $table->boolean('app_npc')->default(false);
                $table->boolean('app_dashboard')->default(false);
            });
        }

        // Add missing fields to users table if they don't exist
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'nik')) {
                    $table->string('nik', 50)->nullable()->unique();
                }
                if (!Schema::hasColumn('users', 'id_dept')) {
                    $table->integer('id_dept')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop these tables in down() if they are shared/legacy,
        // but for sqlite tests we could do dropIfExists.
        if (app()->environment('testing')) {
            Schema::dropIfExists('t1000_sso_user_access_app');
            Schema::dropIfExists('menus');
            Schema::dropIfExists('roles');
        }
    }
};

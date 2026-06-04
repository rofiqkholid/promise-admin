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
        Schema::create('user_scope_roles', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('scope_id', 50);
            $table->integer('role_id');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            
            $table->primary(['user_id', 'scope_id', 'role_id']);
        });

        Schema::create('role_scope_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->string('scope_id', 50);
            $table->integer('menu_id');
            $table->integer('permission_id');
            
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            
            $table->primary(['role_id', 'scope_id', 'menu_id', 'permission_id']);
        });

        Schema::create('user_scope_permissions', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('scope_id', 50);
            $table->integer('menu_id');
            $table->integer('permission_id');
            $table->string('access_type', 10); // 'ALLOW' or 'DENY'
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            
            $table->primary(['user_id', 'scope_id', 'menu_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_scope_permissions');
        Schema::dropIfExists('role_scope_permissions');
        Schema::dropIfExists('user_scope_roles');
    }
};

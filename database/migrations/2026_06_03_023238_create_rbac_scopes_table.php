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
        Schema::create('scopes', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // e.g., 'app_inventory', 'app_drawing', 'app_npc', 'app_dashboard'
            $table->string('scope_name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scopes');
    }
};

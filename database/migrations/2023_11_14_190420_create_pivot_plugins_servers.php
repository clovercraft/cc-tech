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
        Schema::create('plugin_server', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained('servers');
            $table->foreignId('plugin_id')->constrained('plugins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugin_server');
    }
};

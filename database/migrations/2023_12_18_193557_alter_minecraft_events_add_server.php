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
        Schema::table('minecraft_events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('minecraft_account_id');
        });

        Schema::table('minecraft_events', function (Blueprint $table) {
            $table->foreignId('server_id')->nullable()->constrained('servers');
            $table->foreignId('minecraft_account_id')->nullable()->constrained('minecraft_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

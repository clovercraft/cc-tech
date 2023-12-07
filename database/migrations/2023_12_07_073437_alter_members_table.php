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
        Schema::table('minecraft_accounts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('discord_member_id');
        });

        Schema::dropIfExists('discord_members');
        Schema::table('members', function (Blueprint $table) {
            $table->date('birthday')->nullable()->change();
            $table->string('pronouns')->nullable()->change();
            $table->enum('status', ['active', 'whitelisted', 'inactive'])->default('active');
            $table->timestamp('lastseen_at')->nullable();
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

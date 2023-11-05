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
        Schema::create('discord_members', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->timestamp('last_seen');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('minecraft_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discord_member_id')->constrained('discord_members');
            $table->string('name');
            $table->string('uuid')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minecraft_accounts');
        Schema::dropIfExists('discord_members');
    }
};

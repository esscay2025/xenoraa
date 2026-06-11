<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'plan')) {
                $table->enum('plan', ['starter', 'professional', 'business'])->default('starter');
            }
            if (!Schema::hasColumn('users', 'custom_domain')) {
                $table->string('custom_domain')->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable();
            }
            if (!Schema::hasColumn('users', 'site_title')) {
                $table->string('site_title')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'plan_expires_at')) {
                $table->timestamp('plan_expires_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_template')) {
                $table->string('profile_template', 50)->nullable();
            }
            if (!Schema::hasColumn('users', 'onboarding_completed')) {
                $table->boolean('onboarding_completed')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'plan', 'custom_domain', 'profession', 'site_title', 'bio', 'avatar', 'trial_ends_at', 'plan_expires_at']);
        });
    }
};

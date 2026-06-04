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
                $table->string('username', 50)->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'plan')) {
                $table->enum('plan', ['starter', 'professional', 'business'])->default('starter')->after('status');
            }
            if (!Schema::hasColumn('users', 'custom_domain')) {
                $table->string('custom_domain')->nullable()->unique()->after('plan');
            }
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable()->after('custom_domain');
            }
            if (!Schema::hasColumn('users', 'site_title')) {
                $table->string('site_title')->nullable()->after('profession');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('site_title');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'plan_expires_at')) {
                $table->timestamp('plan_expires_at')->nullable()->after('trial_ends_at');
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

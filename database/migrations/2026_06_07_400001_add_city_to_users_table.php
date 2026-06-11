<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'website')) {
                $table->string('website', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city', 'state', 'country', 'website']);
        });
    }
};

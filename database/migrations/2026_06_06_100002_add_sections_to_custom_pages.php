<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('custom_pages', 'sections')) {
            Schema::table('custom_pages', function (Blueprint $table) {
                $table->json('sections')->nullable()->after('content')
                    ->comment('JSON array of section configs: [{key, enabled, data:{...}}]');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('custom_pages', 'sections')) {
            Schema::table('custom_pages', function (Blueprint $table) {
                $table->dropColumn('sections');
            });
        }
    }
};

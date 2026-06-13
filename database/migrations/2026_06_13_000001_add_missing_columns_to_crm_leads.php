<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_leads', 'company')) {
                $table->string('company')->nullable()->after('title');
            }
            if (!Schema::hasColumn('crm_leads', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumnIfExists('company');
            $table->dropColumnIfExists('internal_notes');
        });
    }
};

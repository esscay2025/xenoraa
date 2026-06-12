<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crm_projects', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_projects', 'owner_id')) {
                $table->bigInteger('owner_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('crm_projects', 'priority')) {
                $table->string('priority')->default('medium')->after('status');
            }
        });
    }
    public function down(): void
    {
        Schema::table('crm_projects', function (Blueprint $table) {
            $table->dropColumn(['owner_id', 'priority']);
        });
    }
};

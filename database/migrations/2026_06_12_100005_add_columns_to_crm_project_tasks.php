<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crm_project_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_project_tasks', 'milestone_id')) {
                $table->bigInteger('milestone_id')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('crm_project_tasks', 'estimated_hours')) {
                $table->decimal('estimated_hours', 5, 2)->default(0)->after('assigned_to');
            }
            if (!Schema::hasColumn('crm_project_tasks', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('estimated_hours');
            }
        });
    }
    public function down(): void
    {
        Schema::table('crm_project_tasks', function (Blueprint $table) {
            $table->dropColumn(['milestone_id', 'estimated_hours', 'sort_order']);
        });
    }
};

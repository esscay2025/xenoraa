<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_project_time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('crm_projects')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('crm_project_tasks')->nullOnDelete();
            $table->bigInteger('logged_by');
            $table->date('log_date');
            $table->decimal('hours', 5, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('project_id');
        });
    }
    public function down(): void { Schema::dropIfExists('crm_project_time_logs'); }
};

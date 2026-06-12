<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_project_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('crm_projects')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('crm_project_tasks')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('open');     // open, in_progress, resolved, closed
            $table->bigInteger('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->index('project_id');
        });
    }
    public function down(): void { Schema::dropIfExists('crm_project_issues'); }
};

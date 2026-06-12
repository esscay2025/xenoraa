<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('crm_projects')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('target_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamps();
            $table->index('project_id');
        });
    }
    public function down(): void { Schema::dropIfExists('crm_project_milestones'); }
};

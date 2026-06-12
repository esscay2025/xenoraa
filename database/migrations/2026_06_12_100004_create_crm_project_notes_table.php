<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_project_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('crm_projects')->cascadeOnDelete();
            $table->bigInteger('created_by');
            $table->text('body');
            $table->timestamps();
            $table->index('project_id');
        });
    }
    public function down(): void { Schema::dropIfExists('crm_project_notes'); }
};

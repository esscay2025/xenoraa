<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CRM Leads table
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('source')->default('chatbot'); // chatbot, manual, form
            $table->string('status')->default('new'); // new, contacted, qualified, proposal_sent, won, lost
            $table->string('priority')->default('medium'); // low, medium, high
            $table->text('summary')->nullable(); // AI-generated summary of requirements
            $table->text('notes')->nullable(); // Admin notes
            $table->string('assigned_to')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
        });

        // CRM Requirements (captured from chatbot conversations)
        Schema::create('crm_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->onDelete('cascade');
            $table->text('requirement'); // The captured requirement text
            $table->string('category')->nullable(); // automation, custom_app, digital_transformation, etc.
            $table->string('budget_range')->nullable();
            $table->string('timeline')->nullable();
            $table->text('pain_points')->nullable();
            $table->text('current_tools')->nullable();
            $table->boolean('scope_sent')->default(false);
            $table->timestamp('scope_sent_at')->nullable();
            $table->timestamps();
        });

        // Chatbot conversation history
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->onDelete('set null');
            $table->string('session_id'); // browser session identifier
            $table->string('role'); // user or assistant
            $table->text('message');
            $table->timestamps();
        });

        // Chatbot training data (admin-managed)
        Schema::create('chatbot_training', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // greeting, services, pricing, process, faq, objection
            $table->text('question'); // Example question/trigger
            $table->text('answer'); // Expected answer
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
        Schema::dropIfExists('crm_requirements');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('chatbot_training');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable(); // link to crm_leads if originated from lead
            $table->string('title');
            $table->decimal('value', 15, 2)->default(0);
            $table->string('currency')->default('INR');
            $table->string('stage')->default('prospecting');
            // stages: prospecting, qualification, proposal, negotiation, closed_won, closed_lost
            $table->integer('probability')->default(10); // 0-100%
            $table->date('expected_close')->nullable();
            $table->date('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('lost_reason')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });

        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('type'); // call, email, meeting, note, task, demo
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('related_type')->nullable(); // CrmLead, CrmDeal, CrmContact, CrmAccount
            $table->unsignedBigInteger('related_id')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->timestamps();
            $table->index('user_id');
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
        Schema::dropIfExists('crm_deals');
    }
};

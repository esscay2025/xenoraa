<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('source')->default('manual'); // manual, ai_chatbot, website, referral, linkedin, other
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, inactive, unsubscribed
            $table->timestamps();
            $table->index('user_id');
            $table->index('account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_contacts');
    }
};

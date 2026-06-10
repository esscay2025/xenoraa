<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_account_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('mail_template_id')->nullable();
            $table->string('status')->default('sent'); // sent | draft | scheduled
            $table->string('to_email');
            $table->string('cc_email')->nullable();
            $table->string('bcc_email')->nullable();
            $table->string('subject');
            $table->longText('body_html');
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('error_message')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['account_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_account_emails');
    }
};

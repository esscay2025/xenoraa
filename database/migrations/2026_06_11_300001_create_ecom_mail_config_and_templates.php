<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // E-commerce Mail Configurations (one per tenant)
        Schema::create('ecom_mail_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('mail_driver')->default('smtp');
            $table->string('mail_host')->nullable();
            $table->unsignedSmallInteger('mail_port')->default(587);
            $table->string('mail_username')->nullable();
            $table->text('mail_password')->nullable();
            $table->string('mail_encryption')->default('tls');
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        // E-commerce Mail Templates
        Schema::create('ecom_mail_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('type')->default('general');
            // Types: order_confirmation, order_shipped, order_delivered, order_cancelled,
            //        payment_received, payment_failed, refund_processed,
            //        welcome, password_reset, cart_abandoned, review_request, general
            $table->string('subject');
            $table->text('body_html');
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#6366f1');
            $table->string('secondary_color')->default('#f8fafc');
            $table->string('font_family')->default('Inter, Arial, sans-serif');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecom_mail_templates');
        Schema::dropIfExists('ecom_mail_configs');
    }
};

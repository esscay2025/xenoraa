<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── crm_mail_configs ─────────────────────────────────────────────────────
        // One row per tenant — stores SMTP credentials for CRM outbound mail
        Schema::create('crm_mail_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // tenant owner
            $table->string('mail_driver')->default('smtp');  // smtp | sendmail | mailgun | ses
            $table->string('mail_host')->nullable();
            $table->unsignedSmallInteger('mail_port')->default(587);
            $table->string('mail_username')->nullable();
            $table->text('mail_password')->nullable();       // encrypted
            $table->string('mail_encryption')->default('tls'); // tls | ssl | none
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // ── crm_mail_templates ────────────────────────────────────────────────────
        // Per-tenant mail templates used across CRM flows
        Schema::create('crm_mail_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');                          // e.g. "Invoice Template"
            $table->string('slug')->nullable();              // invoice | quote | sales_order | purchase_order | general | all_in_one
            $table->string('type')->default('general');      // invoice | quote | sales_order | purchase_order | general | all_in_one
            $table->string('subject')->nullable();           // default email subject
            $table->string('logo_path')->nullable();         // uploaded logo
            $table->string('primary_color')->default('#6366f1');
            $table->string('secondary_color')->default('#f8fafc');
            $table->string('font_family')->default('Inter, sans-serif');
            $table->text('header_text')->nullable();         // company name / tagline in header
            $table->longText('body_html')->nullable();       // full editable HTML body
            $table->text('footer_text')->nullable();         // footer note
            $table->boolean('show_logo')->default(true);
            $table->boolean('show_footer')->default(true);
            $table->boolean('is_default')->default(false);   // default for this type
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_mail_templates');
        Schema::dropIfExists('crm_mail_configs');
    }
};

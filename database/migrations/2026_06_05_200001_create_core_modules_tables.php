<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Portfolio / Projects ───────────────────────────────────────
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('project_url')->nullable();
            $table->string('technology_used')->nullable(); // comma-separated or JSON
            $table->string('category')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('images')->nullable(); // array of image paths
            $table->json('videos')->nullable(); // array of video URLs
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['completed', 'in_progress', 'planned'])->default('completed');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        // ─── Appointment Booking ────────────────────────────────────────
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('day_of_week'); // 0=Sun, 1=Mon...6=Sat
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('meeting_link')->nullable();
            $table->text('notes')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'appointment_date']);
        });

        // ─── Testimonials & Reviews ─────────────────────────────────────
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_company')->nullable();
            $table->string('client_designation')->nullable();
            $table->string('client_photo')->nullable();
            $table->text('review');
            $table->tinyInteger('rating')->default(5); // 1-5
            $table->string('video_url')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        // ─── Digital Business Card ──────────────────────────────────────
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('display_name');
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('logo')->nullable();
            $table->string('theme_color')->default('#6366f1');
            $table->json('social_links')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
        });

        // ─── Documents & Downloads ──────────────────────────────────────
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable(); // pdf, doc, etc.
            $table->unsignedBigInteger('file_size')->default(0); // bytes
            $table->enum('category', ['brochure', 'company_profile', 'resume', 'product_catalog', 'certificate', 'other'])->default('other');
            $table->boolean('is_public')->default(true);
            $table->integer('download_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'category']);
        });

        // ─── Media Gallery ──────────────────────────────────────────────
        Schema::create('media_gallery', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['image', 'video', 'youtube'])->default('image');
            $table->string('file_path')->nullable(); // for uploaded files
            $table->string('video_url')->nullable(); // for YouTube/external videos
            $table->string('thumbnail')->nullable();
            $table->string('album')->nullable(); // grouping
            $table->boolean('is_public')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });

        // ─── Notes & Reminders ──────────────────────────────────────────
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('color')->default('#ffffff');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('task');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'is_completed']);
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('remind_at');
            $table->enum('type', ['once', 'daily', 'weekly', 'monthly'])->default('once');
            $table->boolean('is_sent')->default(false);
            $table->string('related_type')->nullable(); // lead, appointment, etc.
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'remind_at']);
        });

        // ─── Profile Enhancements ───────────────────────────────────────
        Schema::create('profile_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->tinyInteger('proficiency')->default(80); // 0-100
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('profile_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->string('grade')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('profile_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('issuing_organization');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('profile_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('language');
            $table->enum('proficiency', ['basic', 'conversational', 'professional', 'fluent', 'native'])->default('professional');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_languages');
        Schema::dropIfExists('profile_certifications');
        Schema::dropIfExists('profile_education');
        Schema::dropIfExists('profile_skills');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('todos');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('media_gallery');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('business_cards');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('appointment_slots');
        Schema::dropIfExists('projects');
    }
};

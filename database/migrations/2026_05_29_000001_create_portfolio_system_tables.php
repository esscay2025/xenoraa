<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Roles & Permissions (Simple RBAC)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, staff, visitor
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add role_id to users table (will modify existing users table or add here if safe)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role_id')) {
                    $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
                }
                if (!Schema::hasColumn('users', 'status')) {
                    $table->string('status')->default('active'); // active, inactive
                }
            });
        }

        // 2. Blog Module
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // nullable for anonymous/unregistered visitors
            $table->string('visitor_name')->nullable();
            $table->string('visitor_email')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(true); // Auto-approve or require admin approval
            $table->timestamps();
        });

        // 3. Job Portal & Recruitment Platform Module
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recruiter/Admin who posted
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location')->default('Remote'); // Remote, Hybrid, On-site
            $table->string('type')->default('Full-time'); // Full-time, Part-time, Contract
            $table->string('salary_range')->nullable();
            $table->enum('status', ['active', 'filled', 'inactive'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable if applicant didn't register
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->string('resume_path'); // Path to uploaded resume
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['applied', 'reviewing', 'interviewing', 'offered', 'rejected'])->default('applied');
            $table->timestamps();
        });

        // 4. Expense Manager Module
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('business'); // business, personal
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Person who recorded the expense
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->enum('type', ['personal', 'business'])->default('personal');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved'); // For team expense approvals
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 5. Portfolio & Social Links
        Schema::create('portfolio_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('role');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // linkedin, instagram, facebook, threads, x, behance, fiverr, upwork
            $table->string('url');
            $table->string('icon_class')->nullable(); // FontAwesome/Bootstrap icons
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('portfolio_experiences');
        Schema::dropIfExists('social_links');

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'role_id')) {
                    $table->dropForeign(['role_id']);
                    $table->dropColumn('role_id');
                }
                if (Schema::hasColumn('users', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }

        Schema::dropIfExists('roles');
    }
};

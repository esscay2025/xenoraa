<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add user_id to site_settings so each tenant has their own settings
        if (!Schema::hasColumn('site_settings', 'user_id')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add tenant_owner_id to users so staff/visitors know which admin they belong to
        if (!Schema::hasColumn('users', 'tenant_owner_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_owner_id')->nullable()->after('role_id')->index();
            });
        }

        // Add user_id to portfolio_experiences for tenant isolation
        if (!Schema::hasColumn('portfolio_experiences', 'user_id')) {
            Schema::table('portfolio_experiences', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to social_links for tenant isolation
        if (!Schema::hasColumn('social_links', 'user_id')) {
            Schema::table('social_links', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to ecommerce_products for tenant isolation
        if (Schema::hasTable('ecommerce_products') && !Schema::hasColumn('ecommerce_products', 'user_id')) {
            Schema::table('ecommerce_products', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to ecommerce_categories for tenant isolation
        if (Schema::hasTable('ecommerce_categories') && !Schema::hasColumn('ecommerce_categories', 'user_id')) {
            Schema::table('ecommerce_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to newsletter_subscribers for tenant isolation
        if (Schema::hasTable('newsletter_subscribers') && !Schema::hasColumn('newsletter_subscribers', 'user_id')) {
            Schema::table('newsletter_subscribers', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to chatbot_training for tenant isolation
        if (Schema::hasTable('chatbot_training') && !Schema::hasColumn('chatbot_training', 'user_id')) {
            Schema::table('chatbot_training', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to chatbot_conversations for tenant isolation
        if (Schema::hasTable('chatbot_conversations') && !Schema::hasColumn('chatbot_conversations', 'user_id')) {
            Schema::table('chatbot_conversations', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }
    }

    public function down(): void
    {
        // Reverse migrations
        $tables = [
            'site_settings' => 'user_id',
            'users' => 'tenant_owner_id',
            'portfolio_experiences' => 'user_id',
            'social_links' => 'user_id',
        ];

        foreach ($tables as $table => $column) {
            if (Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $t) use ($column) {
                    $t->dropColumn($column);
                });
            }
        }
    }
};

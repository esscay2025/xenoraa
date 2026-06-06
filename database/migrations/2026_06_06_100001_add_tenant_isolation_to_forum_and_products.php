<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tenant_owner_id to forum_topics for tenant isolation
        if (!Schema::hasColumn('forum_topics', 'tenant_owner_id')) {
            Schema::table('forum_topics', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_owner_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to products for tenant isolation
        // (the previous migration targeted 'ecommerce_products' which doesn't exist)
        if (!Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }

        // Add user_id to product_categories for tenant isolation
        if (Schema::hasTable('product_categories') && !Schema::hasColumn('product_categories', 'user_id')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('forum_topics', 'tenant_owner_id')) {
            Schema::table('forum_topics', function (Blueprint $table) {
                $table->dropColumn('tenant_owner_id');
            });
        }

        if (Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('product_categories') && Schema::hasColumn('product_categories', 'user_id')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};

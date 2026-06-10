<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // crm_notes — polymorphic notes for any CRM entity
        if (!Schema::hasTable('crm_notes')) {
            Schema::create('crm_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('notable_type', 50)->default('account'); // account, contact, deal, lead, etc.
                $table->unsignedBigInteger('notable_id');
                $table->text('content');
                $table->timestamps();
                $table->index(['notable_type', 'notable_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // crm_account_products — pivot table linking accounts to products
        if (!Schema::hasTable('crm_account_products')) {
            Schema::create('crm_account_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('account_id');
                $table->unsignedBigInteger('product_id');
                $table->timestamps();
                $table->unique(['account_id', 'product_id']);
                $table->foreign('account_id')->references('id')->on('crm_accounts')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('crm_products')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_account_products');
        Schema::dropIfExists('crm_notes');
    }
};

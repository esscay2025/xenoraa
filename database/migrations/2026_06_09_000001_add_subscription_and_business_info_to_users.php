<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Subscription billing cycle (monthly / yearly)
            if (!Schema::hasColumn('users', 'plan_billing')) {
                $table->string('plan_billing', 20)->nullable()->after('plan');
            }
            // Razorpay payment ID for the most recent successful payment
            if (!Schema::hasColumn('users', 'payment_id')) {
                $table->string('payment_id', 100)->nullable()->after('plan_billing');
            }
            // Raw business information provided during onboarding
            if (!Schema::hasColumn('users', 'business_info')) {
                $table->text('business_info')->nullable()->after('payment_id');
            }
            // AI-generated content JSON stored after onboarding AI generation
            if (!Schema::hasColumn('users', 'business_info_ai')) {
                $table->mediumText('business_info_ai')->nullable()->after('business_info');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan_billing', 'payment_id', 'business_info', 'business_info_ai']);
        });
    }
};

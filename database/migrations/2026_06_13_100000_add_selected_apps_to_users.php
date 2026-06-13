<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // JSON column to store which apps the tenant has activated
            // e.g. ["website", "crm"] for a Duo Bundle customer
            if (!Schema::hasColumn('users', 'selected_apps')) {
                $table->json('selected_apps')->nullable()->after('plan');
            }

            // Rename old plan values to new ones if plan column exists
            // We keep the plan column as-is and handle migration in data
        });

        // Migrate existing plan values to new plan keys
        // starter      → solo_app
        // professional → duo_bundle
        // business / business_pro → all_access
        DB::statement("
            UPDATE users SET plan = CASE
                WHEN plan = 'starter'      THEN 'solo_app'
                WHEN plan = 'professional' THEN 'duo_bundle'
                WHEN plan IN ('business', 'business_pro') THEN 'all_access'
                ELSE plan
            END
            WHERE plan IN ('starter', 'professional', 'business', 'business_pro')
        ");

        // Auto-assign selected_apps for existing tenants based on their new plan
        // solo_app    → default to ['website'] (most common starter use case)
        // duo_bundle  → default to ['website', 'ecommerce']
        // all_access  → all 4 apps
        DB::statement("
            UPDATE users SET selected_apps = CASE
                WHEN plan = 'solo_app'    THEN '[\"website\"]'::jsonb
                WHEN plan = 'duo_bundle'  THEN '[\"website\", \"ecommerce\"]'::jsonb
                WHEN plan = 'all_access'  THEN '[\"website\", \"ecommerce\", \"pos\", \"crm\"]'::jsonb
                ELSE '[\"website\"]'::jsonb
            END
            WHERE selected_apps IS NULL AND plan IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'selected_apps')) {
                $table->dropColumn('selected_apps');
            }
        });

        // Revert plan names
        DB::statement("
            UPDATE users SET plan = CASE
                WHEN plan = 'solo_app'   THEN 'starter'
                WHEN plan = 'duo_bundle' THEN 'professional'
                WHEN plan = 'all_access' THEN 'business'
                ELSE plan
            END
            WHERE plan IN ('solo_app', 'duo_bundle', 'all_access')
        ");
    }
};

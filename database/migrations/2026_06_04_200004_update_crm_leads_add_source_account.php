<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_leads', 'source')) {
                $table->string('source')->default('manual')->after('status');
                // source: manual, ai_chatbot, website_form, referral, linkedin, cold_outreach, other
            }
            if (!Schema::hasColumn('crm_leads', 'account_id')) {
                $table->unsignedBigInteger('account_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('crm_leads', 'contact_id')) {
                $table->unsignedBigInteger('contact_id')->nullable()->after('account_id');
            }
            if (!Schema::hasColumn('crm_leads', 'deal_value')) {
                $table->decimal('deal_value', 15, 2)->nullable()->after('source');
            }
            if (!Schema::hasColumn('crm_leads', 'priority')) {
                $table->string('priority')->default('medium')->after('deal_value');
                // priority: low, medium, high, urgent
            }
            if (!Schema::hasColumn('crm_leads', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable()->after('priority');
            }
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn(['source', 'account_id', 'contact_id', 'deal_value', 'priority', 'assigned_to']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── CONTACTS ──────────────────────────────────────────────
        // Existing: id, user_id, account_id, first_name, last_name, email, phone, job_title, department, city, country, source, notes, status
        Schema::table('crm_contacts', function (Blueprint $table) {
            $table->string('contact_image')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('reporting_to')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('title')->nullable();
            $table->string('mobile')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('fax')->nullable();
            $table->boolean('email_opt_out')->default(false);
            $table->string('lead_source')->nullable();
            $table->string('assistant')->nullable();
            $table->string('assistant_phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('skype_id')->nullable();
            $table->string('twitter')->nullable();
            // Mailing Address
            $table->string('mailing_country')->nullable();
            $table->string('mailing_building')->nullable();
            $table->string('mailing_street')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip')->nullable();
            $table->decimal('mailing_lat', 10, 7)->nullable();
            $table->decimal('mailing_lng', 10, 7)->nullable();
            // Other Address
            $table->string('other_country')->nullable();
            $table->string('other_building')->nullable();
            $table->string('other_street')->nullable();
            $table->string('other_city')->nullable();
            $table->string('other_state')->nullable();
            $table->string('other_zip')->nullable();
            $table->decimal('other_lat', 10, 7)->nullable();
            $table->decimal('other_lng', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->string('attachments')->nullable();
        });

        // ── ACCOUNTS ──────────────────────────────────────────────
        // Existing: id, user_id, name, type, industry, website, phone, email, address, city, country, annual_revenue, employees, notes, status
        Schema::table('crm_accounts', function (Blueprint $table) {
            $table->string('account_image')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_type')->nullable();
            $table->string('rating')->nullable();
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->string('account_site')->nullable();
            $table->string('ownership')->nullable();
            $table->string('sic_code')->nullable();
            $table->string('ticker_symbol')->nullable();
            $table->string('fax')->nullable();
            // Billing Address
            $table->string('billing_country')->nullable();
            $table->string('billing_building')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->decimal('billing_lat', 10, 7)->nullable();
            $table->decimal('billing_lng', 10, 7)->nullable();
            // Shipping Address
            $table->string('shipping_country')->nullable();
            $table->string('shipping_building')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->decimal('shipping_lat', 10, 7)->nullable();
            $table->decimal('shipping_lng', 10, 7)->nullable();
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
        });

        // ── DEALS ──────────────────────────────────────────────────
        // Existing: id, user_id, account_id, contact_id, lead_id, title, value, currency, stage, probability, expected_close, closed_at, notes, lost_reason
        Schema::table('crm_deals', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('name')->nullable(); // Deal Name
            $table->string('type')->nullable();
            $table->string('next_step')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('qualification')->nullable();
            $table->decimal('expected_revenue', 15, 2)->nullable();
            $table->string('campaign_source')->nullable();
            $table->decimal('amount', 15, 2)->nullable(); // alias for value
            $table->date('closing_date')->nullable(); // alias for expected_close
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('crm_contacts', function (Blueprint $table) {
            $table->dropColumn([
                'contact_image','owner_id','reporting_to','vendor_id','title',
                'mobile','secondary_email','other_phone','home_phone','fax','email_opt_out',
                'lead_source','assistant','assistant_phone','date_of_birth','skype_id','twitter',
                'mailing_country','mailing_building','mailing_street','mailing_city','mailing_state','mailing_zip','mailing_lat','mailing_lng',
                'other_country','other_building','other_street','other_city','other_state','other_zip','other_lat','other_lng',
                'description','attachments'
            ]);
        });
        Schema::table('crm_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'account_image','owner_id','account_number','account_type','rating',
                'parent_account_id','account_site','ownership','sic_code','ticker_symbol','fax',
                'billing_country','billing_building','billing_street','billing_city','billing_state','billing_zip','billing_lat','billing_lng',
                'shipping_country','shipping_building','shipping_street','shipping_city','shipping_state','shipping_zip','shipping_lat','shipping_lng',
                'outstanding_amount','description'
            ]);
        });
        Schema::table('crm_deals', function (Blueprint $table) {
            $table->dropColumn([
                'owner_id','name','type','next_step','lead_source','qualification',
                'expected_revenue','campaign_source','amount','closing_date','description'
            ]);
        });
    }
};

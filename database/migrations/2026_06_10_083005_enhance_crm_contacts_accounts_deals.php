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
            $table->string('contact_image')->nullable()->after('id');
            $table->unsignedBigInteger('owner_id')->nullable()->after('contact_image');
            $table->unsignedBigInteger('reporting_to')->nullable()->after('last_name');
            $table->unsignedBigInteger('vendor_id')->nullable()->after('account_id');
            $table->string('title')->nullable()->after('vendor_id');
            $table->string('mobile')->nullable()->after('phone');
            $table->string('secondary_email')->nullable()->after('email');
            $table->string('other_phone')->nullable()->after('mobile');
            $table->string('home_phone')->nullable()->after('other_phone');
            $table->string('fax')->nullable()->after('home_phone');
            $table->boolean('email_opt_out')->default(false)->after('fax');
            $table->string('lead_source')->nullable()->after('email_opt_out');
            $table->string('assistant')->nullable()->after('lead_source');
            $table->string('assistant_phone')->nullable()->after('assistant');
            $table->date('date_of_birth')->nullable()->after('assistant_phone');
            $table->string('skype_id')->nullable()->after('date_of_birth');
            $table->string('twitter')->nullable()->after('skype_id');
            // Mailing Address
            $table->string('mailing_country')->nullable()->after('twitter');
            $table->string('mailing_building')->nullable()->after('mailing_country');
            $table->string('mailing_street')->nullable()->after('mailing_building');
            $table->string('mailing_city')->nullable()->after('mailing_street');
            $table->string('mailing_state')->nullable()->after('mailing_city');
            $table->string('mailing_zip')->nullable()->after('mailing_state');
            $table->decimal('mailing_lat', 10, 7)->nullable()->after('mailing_zip');
            $table->decimal('mailing_lng', 10, 7)->nullable()->after('mailing_lat');
            // Other Address
            $table->string('other_country')->nullable()->after('mailing_lng');
            $table->string('other_building')->nullable()->after('other_country');
            $table->string('other_street')->nullable()->after('other_building');
            $table->string('other_city')->nullable()->after('other_street');
            $table->string('other_state')->nullable()->after('other_city');
            $table->string('other_zip')->nullable()->after('other_state');
            $table->decimal('other_lat', 10, 7)->nullable()->after('other_zip');
            $table->decimal('other_lng', 10, 7)->nullable()->after('other_lat');
            $table->text('description')->nullable()->after('other_lng');
            $table->string('attachments')->nullable()->after('description');
        });

        // ── ACCOUNTS ──────────────────────────────────────────────
        // Existing: id, user_id, name, type, industry, website, phone, email, address, city, country, annual_revenue, employees, notes, status
        Schema::table('crm_accounts', function (Blueprint $table) {
            $table->string('account_image')->nullable()->after('id');
            $table->unsignedBigInteger('owner_id')->nullable()->after('account_image');
            $table->string('account_number')->nullable()->after('name');
            $table->string('account_type')->nullable()->after('account_number');
            $table->string('rating')->nullable()->after('account_type');
            $table->unsignedBigInteger('parent_account_id')->nullable()->after('rating');
            $table->string('account_site')->nullable()->after('parent_account_id');
            $table->string('ownership')->nullable()->after('industry');
            $table->string('sic_code')->nullable()->after('ownership');
            $table->string('ticker_symbol')->nullable()->after('sic_code');
            $table->string('fax')->nullable()->after('phone');
            // Billing Address
            $table->string('billing_country')->nullable()->after('ticker_symbol');
            $table->string('billing_building')->nullable()->after('billing_country');
            $table->string('billing_street')->nullable()->after('billing_building');
            $table->string('billing_city')->nullable()->after('billing_street');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_zip')->nullable()->after('billing_state');
            $table->decimal('billing_lat', 10, 7)->nullable()->after('billing_zip');
            $table->decimal('billing_lng', 10, 7)->nullable()->after('billing_lat');
            // Shipping Address
            $table->string('shipping_country')->nullable()->after('billing_lng');
            $table->string('shipping_building')->nullable()->after('shipping_country');
            $table->string('shipping_street')->nullable()->after('shipping_building');
            $table->string('shipping_city')->nullable()->after('shipping_street');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_zip')->nullable()->after('shipping_state');
            $table->decimal('shipping_lat', 10, 7)->nullable()->after('shipping_zip');
            $table->decimal('shipping_lng', 10, 7)->nullable()->after('shipping_lat');
            $table->decimal('outstanding_amount', 15, 2)->default(0)->after('shipping_lng');
            $table->text('description')->nullable()->after('outstanding_amount');
        });

        // ── DEALS ──────────────────────────────────────────────────
        // Existing: id, user_id, account_id, contact_id, lead_id, title, value, currency, stage, probability, expected_close, closed_at, notes, lost_reason
        Schema::table('crm_deals', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('id');
            $table->string('name')->nullable()->after('owner_id'); // Deal Name
            $table->string('type')->nullable()->after('name');
            $table->string('next_step')->nullable()->after('type');
            $table->string('lead_source')->nullable()->after('next_step');
            $table->string('qualification')->nullable()->after('stage');
            $table->decimal('expected_revenue', 15, 2)->nullable()->after('probability');
            $table->string('campaign_source')->nullable()->after('expected_revenue');
            $table->decimal('amount', 15, 2)->nullable()->after('campaign_source'); // alias for value
            $table->date('closing_date')->nullable()->after('amount'); // alias for expected_close
            $table->text('description')->nullable()->after('closing_date');
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

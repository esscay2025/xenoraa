<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('crm_leads', function (Blueprint $table) {
            // 1. Lead Profile
            $table->string('lead_image')->nullable()->after('id');
            $table->unsignedBigInteger('owner_id')->nullable()->after('lead_image'); // staff assignment
            $table->string('lead_status')->nullable()->default('Not Contacted')->after('owner_id');
            $table->string('rating')->nullable()->after('lead_status');

            // 2. Personal Information
            $table->string('salutation')->nullable()->after('rating');
            $table->string('first_name')->nullable()->after('salutation');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('title')->nullable()->after('last_name');
            $table->string('industry')->nullable()->after('title');

            // 3. Contact Information
            $table->string('secondary_email')->nullable()->after('email');
            $table->string('phone')->nullable()->after('secondary_email');
            $table->string('fax')->nullable()->after('phone');
            $table->string('website')->nullable()->after('fax');
            $table->string('twitter')->nullable()->after('website');
            $table->string('linkedin')->nullable()->after('twitter');
            $table->string('facebook')->nullable()->after('linkedin');
            $table->string('instagram')->nullable()->after('facebook');
            $table->boolean('email_opt_out')->default(false)->after('instagram');

            // 4. Address Information
            $table->string('country')->nullable()->after('email_opt_out');
            $table->string('flat_no')->nullable()->after('country');
            $table->string('street')->nullable()->after('flat_no');
            $table->string('city')->nullable()->after('street');
            $table->string('state')->nullable()->after('city');
            $table->string('zip')->nullable()->after('state');

            // 5. Business Information
            $table->decimal('annual_revenue', 15, 2)->nullable()->after('zip');
            $table->integer('no_of_employees')->nullable()->after('annual_revenue');

            // 6. Lead Qualification
            $table->decimal('budget', 15, 2)->nullable()->after('no_of_employees');
            $table->text('requirement')->nullable()->after('budget');
            $table->date('expected_purchase_date')->nullable()->after('requirement');
            $table->string('decision_maker')->nullable()->after('expected_purchase_date');
            $table->string('competitor')->nullable()->after('decision_maker');
            $table->string('interest_level')->nullable()->after('competitor');
            $table->date('follow_up_date')->nullable()->after('interest_level');

            // 7. Lead Tracking
            $table->string('campaign_source')->nullable()->after('follow_up_date');
            $table->string('campaign_name')->nullable()->after('campaign_source');
            $table->string('referral_source')->nullable()->after('campaign_name');
            $table->timestamp('last_activity_date')->nullable()->after('referral_source');
            $table->timestamp('converted_date')->nullable()->after('last_activity_date');
            $table->boolean('is_converted')->default(false)->after('converted_date');

            // 8. Description & Notes
            $table->text('description')->nullable()->after('is_converted');
            $table->text('internal_notes')->nullable()->after('description');
        });
    }

    public function down(): void {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn([
                'lead_image','owner_id','lead_status','rating',
                'salutation','first_name','last_name','title','industry',
                'secondary_email','phone','fax','website','twitter','linkedin','facebook','instagram','email_opt_out',
                'country','flat_no','street','city','state','zip',
                'annual_revenue','no_of_employees',
                'budget','requirement','expected_purchase_date','decision_maker','competitor','interest_level','follow_up_date',
                'campaign_source','campaign_name','referral_source','last_activity_date','converted_date','is_converted',
                'description','internal_notes',
            ]);
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('crm_leads', function (Blueprint $table) {
            // 1. Lead Profile
            $table->string('lead_image')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable(); // staff assignment
            $table->string('lead_status')->nullable()->default('Not Contacted');
            $table->string('rating')->nullable();

            // 2. Personal Information
            $table->string('salutation')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('title')->nullable();
            $table->string('industry')->nullable();

            // 3. Contact Information
            $table->string('secondary_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->boolean('email_opt_out')->default(false);

            // 4. Address Information
            $table->string('country')->nullable();
            $table->string('flat_no')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // 5. Business Information
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->integer('no_of_employees')->nullable();

            // 6. Lead Qualification
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('requirement')->nullable();
            $table->date('expected_purchase_date')->nullable();
            $table->string('decision_maker')->nullable();
            $table->string('competitor')->nullable();
            $table->string('interest_level')->nullable();
            $table->date('follow_up_date')->nullable();

            // 7. Lead Tracking
            $table->string('campaign_source')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('referral_source')->nullable();
            $table->timestamp('last_activity_date')->nullable();
            $table->timestamp('converted_date')->nullable();
            $table->boolean('is_converted')->default(false);

            // 8. Description & Notes
            $table->text('description')->nullable();
            $table->text('internal_notes')->nullable();
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

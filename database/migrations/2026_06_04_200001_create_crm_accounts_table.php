<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // tenant owner
            $table->string('name');
            $table->string('type')->default('prospect'); // prospect, customer, partner, vendor
            $table->string('industry')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->integer('employees')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_accounts');
    }
};

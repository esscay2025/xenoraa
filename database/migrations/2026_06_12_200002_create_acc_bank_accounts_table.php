<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->string('name');
            $table->enum('account_type', ['bank', 'cash', 'credit_card', 'savings', 'wallet'])->default('bank');
            $table->string('bank_name')->nullable();
            $table->string('account_number', 30)->nullable(); // stored masked
            $table->string('ifsc_code', 20)->nullable();
            $table->string('currency', 10)->default('INR');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->date('opening_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }
    public function down(): void { Schema::dropIfExists('acc_bank_accounts'); }
};

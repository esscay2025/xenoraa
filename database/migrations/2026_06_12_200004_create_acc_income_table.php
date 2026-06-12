<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_income', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->string('income_number', 30)->nullable(); // INC-000001
            $table->string('title');
            $table->string('category', 80)->nullable(); // Service Revenue, Product Sales, Consulting, etc.
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->date('income_date');
            $table->string('customer_name', 120)->nullable();
            $table->string('reference', 80)->nullable(); // invoice number or external ref
            $table->unsignedBigInteger('invoice_id')->nullable(); // linked to crm_invoices
            $table->enum('status', ['received', 'pending', 'cancelled'])->default('received');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency', 20)->nullable(); // monthly, quarterly, yearly
            $table->date('next_recurring_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'income_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('acc_income'); }
};

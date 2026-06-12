<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->string('expense_number', 30)->nullable(); // EXP-000001
            $table->string('title');
            $table->string('category', 80)->nullable(); // Rent, Salaries, Software, Travel, etc.
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->date('expense_date');
            $table->string('vendor_name', 120)->nullable();
            $table->string('reference', 80)->nullable();
            $table->unsignedBigInteger('purchase_order_id')->nullable(); // linked to crm_purchase_orders
            $table->boolean('is_billable')->default(false);
            $table->unsignedBigInteger('billable_project_id')->nullable();
            $table->string('receipt_path', 255)->nullable(); // uploaded receipt file
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('paid');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency', 20)->nullable();
            $table->date('next_recurring_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expense_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('acc_expenses'); }
};

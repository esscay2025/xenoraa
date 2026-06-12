<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('chart_account_id')->nullable();
            $table->string('reference_number', 50)->nullable(); // TXN-000001
            $table->enum('type', ['credit', 'debit']); // money in / money out
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->string('category', 80)->nullable();
            $table->string('payee', 120)->nullable(); // vendor or customer name
            $table->enum('source', ['manual', 'invoice', 'purchase_order', 'pos', 'journal', 'import'])->default('manual');
            $table->unsignedBigInteger('source_id')->nullable(); // FK to invoice_id / po_id / pos_order_id
            $table->boolean('is_reconciled')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'transaction_date']);
            $table->index(['bank_account_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('acc_transactions'); }
};

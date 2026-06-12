<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->string('code', 20)->nullable();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
            $table->string('sub_type', 60)->nullable(); // e.g. bank, receivable, payable, revenue, cogs
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->boolean('is_system')->default(false); // system/default accounts cannot be deleted
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });
    }
    public function down(): void { Schema::dropIfExists('acc_chart_of_accounts'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->string('journal_number', 30)->nullable(); // JE-000001
            $table->date('entry_date');
            $table->string('narration')->nullable();
            $table->string('reference', 80)->nullable();
            $table->enum('status', ['draft', 'posted'])->default('draft');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('acc_journal_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id');
            $table->unsignedBigInteger('chart_account_id');
            $table->string('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
            $table->foreign('journal_entry_id')->references('id')->on('acc_journal_entries')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('acc_journal_lines');
        Schema::dropIfExists('acc_journal_entries');
    }
};

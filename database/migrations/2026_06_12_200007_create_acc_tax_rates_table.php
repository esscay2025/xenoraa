<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acc_tax_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_owner_id')->nullable();
            $table->string('name', 80); // e.g. GST 18%, GST 5%, No Tax
            $table->decimal('rate', 5, 2)->default(0); // percentage
            $table->string('tax_type', 30)->nullable(); // GST, VAT, TDS, etc.
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
        });
    }
    public function down(): void { Schema::dropIfExists('acc_tax_rates'); }
};

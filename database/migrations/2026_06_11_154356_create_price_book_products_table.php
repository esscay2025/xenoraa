<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_book_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_book_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('list_price', 12, 2)->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable()->default(0);
            $table->timestamps();

            $table->unique(['price_book_id', 'product_id']);
            $table->foreign('price_book_id')->references('id')->on('crm_price_books')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('crm_products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_book_products');
    }
};

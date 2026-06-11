<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_price_book_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_book_id');
            $table->unsignedBigInteger('user_id');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();

            $table->foreign('price_book_id')->references('id')->on('crm_price_books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_price_book_attachments');
    }
};

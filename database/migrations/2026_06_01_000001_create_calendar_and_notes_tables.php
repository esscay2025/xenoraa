<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Calendar Events table (for logged-in users)
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 100)->nullable(); // for guest users
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('color', 20)->default('blue'); // blue, green, red, yellow, purple
            $table->boolean('is_reminder')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'event_date']);
            $table->index(['session_id', 'event_date']);
        });

        // Notes table (for logged-in users)
        Schema::create('user_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 100)->nullable(); // for guest users
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('color', 20)->default('default'); // default, yellow, blue, green, pink
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['session_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notes');
        Schema::dropIfExists('calendar_events');
    }
};

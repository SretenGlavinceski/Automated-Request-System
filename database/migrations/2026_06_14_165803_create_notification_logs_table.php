<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('recipient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('channel');
            $table->string('message');
            $table->string('status')->default('queued');

            $table->unsignedInteger('attempts')->default(0);
            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};

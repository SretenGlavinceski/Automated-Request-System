<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_item_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('event');

            $table->string('recipient_type');

            $table->boolean('send_database')->default(true);
            $table->boolean('send_email')->default(false);

            $table->string('subject')->nullable();
            $table->text('message');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};

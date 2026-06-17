<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->uuid('tracking_id')
                ->nullable()
                ->after('id');

            $table->unique([
                'tracking_id',
                'channel',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->dropUnique([
                'tracking_id',
                'channel',
            ]);

            $table->dropColumn('tracking_id');
        });
    }
};

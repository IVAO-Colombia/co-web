<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->foreignId('parent_event_id')->nullable()->after('id')->constrained('events');
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->unsignedSmallInteger('recurrence_interval')->nullable()->after('is_recurring');
            $table->jsonb('recurrence_weekdays')->nullable()->after('recurrence_interval');
            $table->date('recurrence_ends_at')->nullable()->after('recurrence_weekdays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('parent_event_id');
            $table->dropColumn([
                'is_recurring',
                'recurrence_interval',
                'recurrence_weekdays',
                'recurrence_ends_at',
            ]);
        });
    }
};

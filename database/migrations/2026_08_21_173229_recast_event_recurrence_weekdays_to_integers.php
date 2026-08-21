<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Events created through a multipart submission stored their weekdays as
     * strings (`["4"]`), which the strict comparison in GenerateEventOccurrences
     * never matches. Recast the stored values to integers.
     */
    public function up(): void
    {
        DB::table('events')
            ->whereNotNull('recurrence_weekdays')
            ->select('id', 'recurrence_weekdays')
            ->orderBy('id')
            ->each(function (object $event): void {
                /** @var array<int, mixed> $weekdays */
                $weekdays = json_decode((string) $event->recurrence_weekdays, true) ?: [];
                $recast = array_values(array_map(intval(...), $weekdays));

                if ($recast === $weekdays) {
                    return;
                }

                DB::table('events')
                    ->where('id', $event->id)
                    ->update(['recurrence_weekdays' => json_encode($recast)]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restoring the malformed string values would serve no purpose.
    }
};

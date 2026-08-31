<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The REGULAR event type is being removed in favor of the new, more
     * specific types. Reassign the existing REGULAR events to Airbridge.
     */
    public function up(): void
    {
        DB::table('events')
            ->where('type', 'REGULAR')
            ->update(['type' => 'airbridge']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible: genuinely new Airbridge events created after this
        // migration ran would be indistinguishable from reassigned ones.
    }
};

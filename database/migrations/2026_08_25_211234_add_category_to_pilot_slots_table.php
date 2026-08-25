<?php

declare(strict_types=1);

use App\Enums\PilotSlotCategory;
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
        Schema::table('pilot_slots', function (Blueprint $table): void {
            $table->string('category', 20)
                ->default(PilotSlotCategory::DEPARTURE->value)
                ->after('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilot_slots', function (Blueprint $table): void {
            $table->dropColumn('category');
        });
    }
};

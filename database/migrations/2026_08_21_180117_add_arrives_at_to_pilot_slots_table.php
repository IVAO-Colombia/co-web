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
        Schema::table('pilot_slots', function (Blueprint $table): void {
            $table->dateTime('arrives_at')->nullable()->after('departs_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilot_slots', function (Blueprint $table): void {
            $table->dropColumn('arrives_at');
        });
    }
};

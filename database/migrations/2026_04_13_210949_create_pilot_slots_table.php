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
        Schema::disableForeignKeyConstraints();

        Schema::create('pilot_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('pilot_id')->nullable()->constrained('users');
            $table->string('airline_icao', 10)->index();
            $table->string('flight_number', 20)->index();
            $table->string('aircraft', 25);
            $table->string('origin', 10);
            $table->string('destination', 10);
            $table->dateTime('departs_at');
            $table->string('gate', 10)->nullable();
            $table->string('status', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilot_slots');
    }
};

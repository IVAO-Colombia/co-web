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
        Schema::create('atc_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ivao_id')->nullable();
            $table->string('airport_id', 10)->index();
            $table->string('atc_callsign', 50);
            $table->string('compose_position', 50);
            $table->string('middle_identifier', 10)->nullable();
            $table->string('position', 10);
            $table->string('frequency', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atc_positions');
    }
};

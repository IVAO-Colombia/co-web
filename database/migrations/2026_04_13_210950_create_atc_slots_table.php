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

        Schema::create('atc_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('atc_id')->nullable()->constrained('users');
            $table->string('callsign', 25)->index();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 50);
            $table->jsonb('ivao_booking')->nullable();
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
        Schema::dropIfExists('atc_slots');
    }
};

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

        Schema::create('user_award_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('award_id')->constrained('user_awards');
            $table->foreignId('event_id')->constrained();
            $table->string('callsign', 25);
            $table->string('status', 50);
            $table->integer('points');
            $table->text('observations');
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
        Schema::dropIfExists('user_award_reports');
    }
};

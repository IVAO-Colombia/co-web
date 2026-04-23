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
        Schema::create('atc_position_fras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atc_position_id')->constrained();
            $table->string('atc_compose_position')->index();
            $table->unsignedBigInteger('ivao_id');
            $table->unsignedBigInteger('ivao_user_id')->nullable();
            $table->unsignedBigInteger('ivao_atc_position_id')->nullable();
            $table->unsignedBigInteger('ivao_subcenter_id')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('monday');
            $table->boolean('tuesday');
            $table->boolean('wednesday');
            $table->boolean('thursday');
            $table->boolean('friday');
            $table->boolean('saturday');
            $table->boolean('sunday');
            $table->date('date')->nullable();
            $table->integer('min_atc')->nullable();
            $table->boolean('active');
            $table->boolean('is_blacklist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atc_position_fras');
    }
};

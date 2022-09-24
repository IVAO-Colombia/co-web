<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("airports", function (Blueprint $table) {
            $table->id();
            $table
                ->char("icao", 10)
                ->unique()
                ->nullable();
            $table->char("iata", 4)->nullable();
            $table->char("name", 255)->nullable();
            $table->float("latitude", 30, 26)->nullable();
            $table->float("longitude", 30, 26)->nullable();
            $table->char("country", 2)->nullable();
            $table->char("municipality", 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("airports");
    }
}

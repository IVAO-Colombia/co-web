<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackervasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("trackervas", function (Blueprint $table) {
            $table->id();
            $table->bigInteger("virtualairines_id")->nullable();
            $table->string("callsign")->nullable();
            $table->string("userId")->nullable();
            $table->string("departureId")->nullable();
            $table->string("arrivalId")->nullable();
            $table->string("aircraftId")->nullable();
            $table->string("sessionId")->nullable();
            $table->string("stateAircraft")->nullable();
            $table->string("onGround")->nullable();
            $table->string("groundSpeed")->nullable();
            $table->text("route")->nullable();
            $table->text("remarks")->nullable();
            $table->dateTime("departureTime")->nullable();
            $table->dateTime("arrivalTime")->nullable();
            $table->string("onlineTime")->nullable();
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
        Schema::dropIfExists("trackervas");
    }
}

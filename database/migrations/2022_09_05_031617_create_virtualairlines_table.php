<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualairlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("virtualairlines", function (Blueprint $table) {
            $table->id();
            $table->char("icao", 10)->unique();
            $table->char("iata", 4);
            $table->char("name", 255);
            $table->char("code", 255)->nullable();
            $table->string("description")->nullable();
            $table->string("website")->nullable();
            $table->string("imagen")->nullable();
            $table->boolean("status")->default(true);
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
        Schema::dropIfExists("virtualairlines");
    }
}

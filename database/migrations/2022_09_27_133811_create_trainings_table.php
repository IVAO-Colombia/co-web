<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("trainings", function (Blueprint $table) {
            $table->id("id");
            $table->bigInteger("user_id");
            $table->datetime("date_training")->nullable();
            $table->bigInteger("trainer_id")->nullable();
            $table->string("rating")->nullable();
            $table->string("typetraining");
            $table->integer("status");
            $table->text("note")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("trainings");
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("departments", function (Blueprint $table) {
            $table->id();
            $table->string("department_id");
            $table->string("title");
            $table->text("description");
            $table->string("banner");
            $table->unsignedBigInteger("team_id");
            $table->boolean("active")->default(1);
            $table->boolean("events")->default(0);
            $table->timestamps();

            $table
                ->foreign("team_id")
                ->references("id")
                ->on("teams");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("departments");
    }
}

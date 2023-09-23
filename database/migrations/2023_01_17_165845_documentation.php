<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Documentation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("documentations", function(Blueprint $table){

            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable(true);
            $table->string('type');
            $table->string('file')->nullable(true);
            $table->string("url")->nullable(true);
            $table->timestamps($precision = 0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

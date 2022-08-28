<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug',255)->unique();
            $table->string('image')->nullable();
            $table->string('date_time',255)->nullable();
            $table->date('start_publish_date')->nullable()->default(new DateTime());
            $table->date('end_publish_date')->nullable()->default(new DateTime());
            $table->text('description')->nullable();
            $table->boolean('has_booking')->default(false);
            $table->boolean('confirm_booking')->default(false);
            $table->boolean('featured')->default(false);
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
        Schema::dropIfExists('events');
    }
}

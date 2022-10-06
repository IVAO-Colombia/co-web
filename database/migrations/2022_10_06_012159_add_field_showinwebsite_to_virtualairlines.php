<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldShowinwebsiteToVirtualairlines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("virtualairlines", function (Blueprint $table) {
            $table->boolean("show_in_web")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("virtualairlines", function (Blueprint $table) {
            $table->dropColumn("show_in_web");
        });
    }
}

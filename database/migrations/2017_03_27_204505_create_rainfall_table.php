<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRainfallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rainfall', function ($table) {
            $table->integer('station_number')->unsigned();
            $table->string('month');
            $table->integer('rainfall_amount');

            $table->foreign('station_number')->references('station_id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rainfall');
    }
}

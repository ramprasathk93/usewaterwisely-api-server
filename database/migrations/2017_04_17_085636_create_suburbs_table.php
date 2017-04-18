<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuburbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('suburbs', function ($table) {
            $table->integer('id')->unsigned();
            $table->char('suburb_code', 4);
            $table->string('suburb_name');
            $table->integer('station_number')->unsigned();

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
        Schema::drop('suburbs');
    }
}

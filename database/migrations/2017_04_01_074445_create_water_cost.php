<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaterCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_cost', function ($table) {
            $table->increments('id');
            $table->string('water_provider');
            $table->float('service_charge', 8, 4);
            // 440 L/day
            $table->float('block_1_charge', 8, 4);
            // 441-880 L/day
            $table->float('block_2_charge', 8, 4);
            // 881 + L/day
            $table->float('block_3_charge', 8, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('water_cost');
    }
}

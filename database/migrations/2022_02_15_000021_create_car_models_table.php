<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('option');
            $table->string('engine_cylinders');
            $table->string('engine_displacement');
            $table->string('engine_power');
            $table->string('engine_torque');
            $table->string('engine_fuel_system');
            $table->string('engine_fuel');
            $table->string('engine_c2o');
            $table->string('performance_top_speed');
            $table->string('performance_acceleration');
            $table->string('fuel_economy_city');
            $table->string('fuel_economy_highway');
            $table->string('fuel_economy_combined');
            $table->string('transmission_drive_type');
            $table->string('transmission_gearbox');
            $table->string('brakes_front');
            $table->string('brakes_rear');
            $table->string('tires_size');
            $table->string('dimensions_length');
            $table->string('dimensions_width');
            $table->string('dimensions_height');
            $table->string('dimensions_front_rear_track');
            $table->string('dimensions_wheelbase');
            $table->string('dimensions_ground_clearance');
            $table->string('dimensions_cargo_volume');
            $table->string('dimensions_cd');
            $table->string('weight_unladen');
            $table->string('weight_limit');
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
        Schema::dropIfExists('car_models');
    }
}

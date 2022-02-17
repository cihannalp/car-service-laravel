<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_account_id')->unsigned();
            $table->bigInteger('car_model_id')->unsigned();
            $table->double('total');
            $table->boolean('is_canceled')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_account_id')->references('id')->on('user_accounts')->cascadeOnDelete();
            $table->foreign('car_model_id')->references('id')->on('car_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->tinyInteger('wifi')->default(0);
            $table->tinyInteger('tv')->default(0);
            $table->tinyInteger('dryer')->default(0);
            $table->tinyInteger('c_oxide_alarm')->default(0);
            $table->tinyInteger('air_conditioning')->default(0);
            $table->tinyInteger('washer')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            //
        });
    }
};

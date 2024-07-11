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
        Schema::create('estate_amenity_values', function (Blueprint $table) {
            $table->id('eav_id');
            $table->bigInteger('eav_estate_id');
            $table->bigInteger('eav_amenity_id');
            $table->string('eav_value')->comment('0=off,1=available')->nullable();
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
        Schema::dropIfExists('estate_amenity_values');
    }
};

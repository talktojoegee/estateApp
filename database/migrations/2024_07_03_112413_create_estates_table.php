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
        Schema::create('estates', function (Blueprint $table) {
            $table->id('e_id');
            $table->string('e_name')->nullable();
            $table->integer('e_state_id')->nullable();
            $table->string('e_city')->nullable();
            $table->string('e_mobile_no')->nullable();
            $table->string('e_address')->nullable();
            $table->bigInteger('e_contact_person')->nullable()->comment('resource person');
            $table->tinyInteger('e_sold')->default(0)->comment('0=Not sold,1=sold');
            $table->date('e_date_sold')->nullable();
            $table->bigInteger('e_sold_to')->nullable()->comment('the person/entity sold to');
            $table->string('e_slug');
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
        Schema::dropIfExists('estates');
    }
};

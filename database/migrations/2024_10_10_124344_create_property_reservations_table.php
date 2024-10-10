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
        Schema::create('property_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserved_for')->comment('customer ID');
            $table->unsignedBigInteger('reserved_by');
            $table->unsignedBigInteger('property_id');
            $table->tinyInteger('type')->default(1)->comment('1=part payment,2=special reservation');
            //$table->dateTime('date_reserved');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=approved,2=declined');
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->dateTime('date_actioned')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('property_reservations');
    }
};

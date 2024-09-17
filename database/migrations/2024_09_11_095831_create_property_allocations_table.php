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
        Schema::create('property_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estate_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('allocated_by');
            $table->unsignedBigInteger('customer_id');
            $table->integer('level')->nullable()->comment('second,third,fourth...');
            $table->dateTime('allocated_at');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=approved,2=declined');
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->dateTime('date_actioned')->nullable();
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
        Schema::dropIfExists('property_allocations');
    }
};

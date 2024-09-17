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
        Schema::create('payment_definitions', function (Blueprint $table) {
            $table->id();
            $table->integer('pay_code');
            $table->string('payment_name');
            $table->tinyInteger('taxable')->default(0)->comment('0=No,1=yes');
            $table->tinyInteger('payment_type')->comment('1=Income,2=Deduction');
            $table->tinyInteger('payment_variance')->default(1)->comment('1=Standard,2=Variation');
            $table->unsignedBigInteger('added_by')->nullable();
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
        Schema::dropIfExists('payment_definitions');
    }
};

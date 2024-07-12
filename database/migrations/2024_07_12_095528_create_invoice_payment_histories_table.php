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
        Schema::create('invoice_payment_histories', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('invoice_id');
            $table->double('amount')->default(0);
            $table->double('charge')->default(0);
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
        Schema::dropIfExists('invoice_payment_histories');
    }
};

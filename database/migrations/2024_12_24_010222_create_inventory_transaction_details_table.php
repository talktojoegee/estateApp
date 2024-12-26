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
        Schema::create('inventory_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("master_id")->nullable();
            $table->unsignedBigInteger("item_id")->nullable();
            $table->integer("quantity")->default(0);
            $table->double("amount")->default(0);
            $table->tinyInteger("trans_type")->default(1)->comment("1=Purchase,2=Sales");
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
        Schema::dropIfExists('inventory_transaction_details');
    }
};

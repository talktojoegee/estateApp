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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vendor_id");
            $table->tinyInteger("status")->default(0)->comment("0=Pending,1=Approved,2=Declined");
            $table->date("trans_date")->nullable();
            $table->tinyInteger("trans_type")->default(1)->comment("1=Purchase,2=Sales");
            $table->unsignedBigInteger("purchased_by")->nullable();
            $table->unsignedBigInteger("sold_by")->nullable();
            $table->unsignedBigInteger("actioned_by")->nullable();
            $table->date("date_actioned")->nullable();
            $table->string("slug")->nullable();
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
        Schema::dropIfExists('inventory_transactions');
    }
};

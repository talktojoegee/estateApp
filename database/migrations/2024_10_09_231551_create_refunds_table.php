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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('requested_by');
            $table->dateTime('date_requested');
            $table->double('amount_paid')->default(0);
            $table->double('actual_amount')->default(0);
            $table->double('amount_refunded')->default(0);
            $table->double('refund_rate')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=approved,2=declined');
            $table->dateTime('date_actioned')->nullable();
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->tinyInteger('posted')->default(0)->comment('0=no,1=yes,2=discarded');
            $table->unsignedBigInteger('posted_discarded_by')->nullable();
            $table->dateTime('posted_discarded_date')->nullable();
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
        Schema::dropIfExists('refunds');
    }
};

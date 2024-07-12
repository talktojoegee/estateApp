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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            //$table->unsignedBigInteger('tenant_id')->nullable();
            //$table->double('tenant_app_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('receipt_no')->nullable();
            $table->integer('payment_method')->default(1)->comment('1=cash,2=Cheque,3=bank transfer,4=internet');
            $table->integer('payment_plan')->default(1)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('trans_ref')->nullable();
            $table->double('vat')->default(0);
            $table->double('vat_rate')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('total')->default(0);
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->tinyInteger('posted')->default(0)->comment('0=not,1=yes');
            $table->dateTime('date_posted')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};

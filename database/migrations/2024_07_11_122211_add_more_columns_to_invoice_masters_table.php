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
        Schema::table('invoice_masters', function (Blueprint $table) {
            $table->tinyInteger('invoice_type')->default(1)->comment('1=new lease,2=Lease renewal, 3=Sale of property	');
            $table->tinyInteger('payment_method')->default(1)->comment('1=cash,2=Cheque,3=bank transfer,4=internet');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->double('vat')->default(0);
            $table->double('vat_rate')->default(0);
            $table->double('charge')->default(0);
            $table->double('sub_total')->default(0);
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_masters', function (Blueprint $table) {
            //
        });
    }
};

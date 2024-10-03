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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paid_by');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('payment_definition_id');
            $table->integer('payroll_month');
            $table->integer('payroll_year');
            $table->double('amount')->default(0);
            $table->tinyInteger('status')->default(0)->comment('1=Confirmed,0=otherwise');
            $table->date('date_actioned')->nullable();
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->string('batch_code')->nullable();
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
        Schema::dropIfExists('salaries');
    }
};

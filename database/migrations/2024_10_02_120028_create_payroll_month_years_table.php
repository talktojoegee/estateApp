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
        Schema::create('payroll_month_years', function (Blueprint $table) {
            $table->id();
            $table->integer('payroll_month')->nullable();
            $table->integer('payroll_year')->nullable();
            $table->integer('payroll_status')->default(0)->comment('only one will be active per time');
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
        Schema::dropIfExists('payroll_month_years');
    }
};

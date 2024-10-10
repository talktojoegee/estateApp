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
            $table->integer('discount_type')->nullable()->comment('1=flat,2=rate');
            $table->double('discount_rate')->default(0);
            $table->double('discount_amount')->default(0);
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

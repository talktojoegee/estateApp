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
            $table->tinyInteger('posted')->default(0)->comment('1=Yes,0=No,2=discard');
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->dateTime('date_posted')->nullable();
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

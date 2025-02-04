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
        Schema::table('property_bulk_import_details', function (Blueprint $table) {
            $table->string('customer')->nullable();
            //$table->string('customer_id')->nullable();
            //$table->string('customer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_bulk_import_details', function (Blueprint $table) {
            //
        });
    }
};

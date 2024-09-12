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
            $table->tinyInteger('payment_status')->default(0)->comment('0=Not paid,1=invoice issued, 2=receipt')->after('status');
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

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
            $table->tinyInteger('action_status')->default(0)->comment('0=pending,1=approved,2=declined');
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->dateTime('date_actioned')->nullable();
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

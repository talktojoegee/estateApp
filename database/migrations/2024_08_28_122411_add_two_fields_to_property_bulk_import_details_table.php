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
            $table->text('property_name')->after('occupied_by')->nullable();
            $table->text('amount_paid')->after('price')->nullable();
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

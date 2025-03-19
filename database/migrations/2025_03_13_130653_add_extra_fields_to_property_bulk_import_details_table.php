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
            $table->string('block')->nullable();
            $table->string('location')->nullable();
            $table->string('availability')->nullable();
            $table->string('bank_details')->nullable();
            $table->string('account_number')->nullable();
            $table->string('mode_of_payment')->nullable();
            //$table->double('amount_paid')->default(0);
            $table->double('balance')->default(0);
            $table->string('purchase_status')->nullable();
            $table->string('allocation_letter')->nullable();

            $table->string('second_allotee')->nullable();
            $table->string('third_allotee')->nullable();
            $table->string('fourth_allotee')->nullable();
            $table->string('fifth_allotee')->nullable();

            $table->double('rent_amount')->default(0);

            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_gender')->nullable();
            $table->string('occupation')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_email')->nullable();
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

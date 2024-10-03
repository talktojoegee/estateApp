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
        Schema::table('estates', function (Blueprint $table) {
            $table->string('property_account')->nullable();
            $table->string('customer_account')->nullable();
            $table->string('vendor_account')->nullable();
            $table->string('tax_account')->nullable();
            $table->string('refund_account')->nullable();
            $table->string('charges_account')->nullable();
            $table->string('salary_account')->nullable();
            $table->string('employee_account')->nullable();
            $table->string('workflow_account')->nullable();
            $table->string('general_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estates', function (Blueprint $table) {
            //
        });
    }
};

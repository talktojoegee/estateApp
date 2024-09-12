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
        Schema::create('app_default_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('property_account')->nullable()->comment('default property GL code');
            $table->integer('customer_account')->nullable()->comment('default customer GL code');
            $table->integer('vendor_account')->nullable()->comment('default vendor GL code');
            $table->integer('tax_account')->default(1)->comment('default tax GL code');
            $table->integer('refund_account')->default(1)->comment('default refund GL code');
            $table->integer('charges_account')->default(1)->comment('default charges GL code');
            $table->integer('salary_account')->default(1)->comment('default salary GL code');
            $table->integer('employee_account')->default(1)->comment('default employee GL code');
            $table->integer('workflow_account')->default(1)->comment('default workflow GL code');
            $table->integer('general_account')->default(1)->comment('default general GL code');
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
        Schema::dropIfExists('app_default_settings');
    }
};

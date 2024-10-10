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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_account')->nullable();
            $table->bigInteger('workflow_account')->nullable();
            $table->bigInteger('general_account')->nullable();
            $table->bigInteger('salary_account')->nullable();
            $table->bigInteger('charge_account')->nullable();
            $table->bigInteger('vendor_account')->nullable();
            $table->bigInteger('customer_account')->nullable();
            $table->tinyInteger('default_account_settings')->default(1)->comment('1=Estate,0=General');
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
        Schema::dropIfExists('general_settings');
    }
};

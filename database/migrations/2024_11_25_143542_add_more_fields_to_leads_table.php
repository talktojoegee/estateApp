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
        Schema::table('leads', function (Blueprint $table) {
            $table->tinyInteger('customer_type')->default(1)->comment('1=Individual,2=Partnership,3=Organization');
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_mobile_no')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_person_full_name')->nullable();
            $table->string('company_person_mobile_no')->nullable();
            $table->string('company_person_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};

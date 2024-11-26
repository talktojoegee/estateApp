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
        Schema::create('lead_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->comment('first_partner_ID');
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('address')->nullable();

            $table->string('kin_full_name')->nullable();
            $table->string('kin_mobile_no')->nullable();
            $table->string('kin_email')->nullable();
            $table->string('kin_address')->nullable();
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
        Schema::dropIfExists('lead_partners');
    }
};

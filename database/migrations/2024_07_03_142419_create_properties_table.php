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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estate_id');
            $table->unsignedBigInteger('added_by');
            //$table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('occupied_by')->nullable()->comment('current occupant');
            //$table->tinyInteger('occupy_level')->default(1)->comment('1=Tenant,2=Owner');
            $table->string('property_title')->nullable();
            $table->string('house_no')->nullable();
            $table->string('shop_no')->nullable();
            $table->string('plot_no')->nullable();
            $table->string('no_of_office_rooms')->nullable();
            $table->tinyInteger('office_ensuite_toilet_bathroom')->default(0)->comment('0=None,1=Yes,2=No');
            $table->string('no_of_shops')->nullable();
            $table->integer('building_type')->nullable();
            $table->integer('total_no_bedrooms')->nullable();
            $table->integer('with_bq')->nullable()->default(0);
            $table->integer('no_of_floors')->nullable();
            $table->integer('no_of_toilets')->nullable();
            $table->integer('no_of_car_parking')->nullable();
            $table->integer('no_of_units')->nullable();
            $table->integer('frequency')->nullable();
            $table->integer('currency_id')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->integer('property_condition')->default(1)->comment('1=Good,2=under repair,3=bad,4=fair');
            $table->integer('construction_stage')->nullable();
            $table->string('land_size')->nullable();
            $table->bigInteger('gl_id')->nullable()->comment('general ledger ID');
            $table->unsignedBigInteger('sold_to')->nullable();
            $table->date('date_sold')->nullable();
            $table->integer('location_id')->nullable();
            $table->string('address')->nullable();

            $table->string('built_on')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Available,1=Rented,2=Sold');

            //$table->tinyInteger('status')->default(0)->comment('0=Vacant,1=Taken,2=Maintenance,3=Renovation,4=Sold,5=listed');
            $table->integer('bedrooms')->default(0)->comment('no of bedrooms')->nullable();
            $table->integer('living_rooms')->default(0)->nullable()->comment('no of living rooms');
            $table->integer('kitchen')->default(0)->comment('0=No,1=Yes');
            $table->integer('borehole')->default(0)->comment('0=No,1=Yes');
            $table->integer('pool')->default(0)->comment('0=No,1=Yes');
            $table->integer('security')->default(0)->comment('0=No,1=Yes');
            $table->integer('car_park')->default(0)->comment('0=No,1=Yes');
            $table->integer('garage')->default(0)->comment('0=No,1=Yes');
            $table->integer('laundry')->default(0)->comment('0=No,1=Yes');
            $table->integer('store_room')->default(0)->comment('0=No,1=Yes');
            $table->integer('balcony')->default(0)->comment('0=No,1=Yes');
            $table->integer('elevator')->default(0)->comment('0=No,1=Yes');
            $table->integer('play_ground')->default(0)->comment('0=No,1=Yes');
            $table->integer('lounge')->default(0)->comment('0=No,1=Yes');
            $table->integer('guest_toilet')->default(0)->comment('0=No,1=Yes');
            $table->integer('anteroom')->default(0)->comment('0=No,1=Yes');
            $table->integer('fitted_wardrobe')->default(0)->comment('0=No,1=Yes');
            $table->integer('bq')->default(0)->comment('0=No,1=Yes');
            $table->integer('penthouse')->default(0)->comment('0=No,1=Yes');
            $table->integer('gate_house')->default(0)->comment('0=No,1=Yes');
            $table->integer('gen_house')->default(0)->comment('0=No,1=Yes');
            $table->string('slug');
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
        Schema::dropIfExists('properties');
    }

    /*
     * BQ Options
     * ===========
     *
     * None
Inbuilt: self-contain (a room with toilet/bathroom and kitchen).
Externally built: 1 unit of self-contain (a room with toilet/bathroom and kitchen).
Externally built: 2 units of self-contain (a room with toilet/bathroom and kitchen).
Inbuilt: 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).
Externally built: 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).
Externally built: 2 units of 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).
with space for 2 rooms BQ.
     */

    /*
     * PentHouse Options
     * =================
     *
     * None
Self Contain (A Bedroom ensuite with toilet/bathroom).
Sitting Room, 1-Bedroom ensuite with toilet/bathroom) and mini-kitchen.
Sitting Room, 2-Bedroom ensuite with toilet/bathroom) and mini-kitchen.
Others
     */

/*
 * Payment Plans
 * =============
 *
 * None
Outright Purchase
Conditional Purchase: Pay 70% and take possession, and pay the balance of 30% within 90 days (3 months period).
NHF: pay 30% down payment and process the balance through FGMB within the above mentioned Scheme. Balance can be paid within 10 to 30 years depending on your unexpired service years.
Smart Homes: make 50% down payment and take possession, take advantage of an in-house mortgage provider who will pay us the balance at 10% interest to you; repayable in 6(six) years.
Rent to Own: pay your annual rent for 13 years and own the house. Take possession from point of payment of year one.
 */

};

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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('org_id');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0=open,1=closed')->default(0);
            $table->date('date_closed')->nullable();
            $table->bigInteger('closed_by')->nullable();
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
        Schema::dropIfExists('support_tickets');
    }
};

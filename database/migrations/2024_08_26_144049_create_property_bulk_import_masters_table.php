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
        Schema::create('property_bulk_import_masters', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code')->nullable();
            $table->unsignedBigInteger('imported_by')->nullable();
            $table->integer('status')->default(0)->comment('0=pending,1=approved');
            $table->unsignedBigInteger('actioned_by')->nullable();
            $table->date('action_date')->nullable();
            $table->text('narration')->nullable();
            $table->text('attachment')->nullable();
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
        Schema::dropIfExists('property_bulk_import_masters');
    }
};

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
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->default('wallpaper.jpg');
            $table->string('wallpaper_name')->default('Default');
            $table->string('text_color')->default('#FFFFFF');
            $table->string('caption_color')->default('#FFFFFF');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->tinyInteger('custom')->default(0)->comment('0=no,1=yes');
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
        Schema::dropIfExists('wallpapers');
    }
};

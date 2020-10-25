<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStarFortunesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('star_fortunes', function (Blueprint $table) {
            $table->id();
            $table->integer('cron_star_sign_id')->index();
            $table->tinyInteger('type')->comment('運勢');
            $table->tinyInteger('score')->comment('分數');
            $table->string('description')->comment('說明');
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
        Schema::dropIfExists('star_fortunes');
    }
}

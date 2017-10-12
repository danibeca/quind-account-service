<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComponentQualitySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_quality_system', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('component_id')->unsigned();
            $table->integer('quality_system_id')->unsigned();
            $table->string('url', 150);
            $table->integer('type');
            $table->boolean('verified')->default(0);

            $table->foreign('component_id')->references('id')->on('components')->onDelete('cascade');
            $table->foreign('quality_system_id')->references('id')->on('quality_systems')->onDelete('cascade');

            $table->unique(['component_id', 'quality_system_id', 'url'], 'component_quality_system_unique');

          //  $table->primary(['id','component_id', 'quality_system_id'], 'component_quality_system_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_quality_system');
    }
}

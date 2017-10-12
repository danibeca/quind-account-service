<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateHierarchicalTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hierarchical_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('type_id')->unsigned();
            NestedSet::columns($table);
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('tag_types');
        });

        DB::table('hierarchical_tags')->insert(
            array(
                array(
                    'id'   => 1,
                    'name' => 'Cuenta',
                    'type_id'=> 1,
                    '_lft' => 1,
                    '_rgt' => 6,
                    'parent_id' => NULL
                ),
                array(
                    'id'   => 2,
                    'name' => 'Sistemas',
                    'type_id'=> 1,
                    '_lft' => 2,
                    '_rgt' => 5,
                    'parent_id' => 1
                ),
                array(
                    'id'   => 3,
                    'name' => 'Aplicaciones',
                    'type_id'=> 2,
                    '_lft' => 3,
                    '_rgt' => 4,
                    'parent_id' => 2
                )
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hierarchical_tags');
    }
}

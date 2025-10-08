<?php

use Illuminate\Database\Migrations\Migration;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('modelno')->nullable();
            $table->integer('manufacturer_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
            //making a note here. Interestingly we state the model and modelno cloumns as strings. Tableplus stores as a var_string.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('models');
    }
}

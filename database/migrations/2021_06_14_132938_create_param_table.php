<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('params', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('display_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Create table for description params
        Schema::create('param_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('param_id');
            $table->longText('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('param_id')->references('id')->on('params');
        });

        // Create table for values params
        Schema::create('param_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('param_id')->nullable();
            $table->string('code');
            $table->text('name');
            $table->string('symbol')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_visible')->nullable();
            $table->boolean('is_default')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('param_id')->references('id')->on('params');
        });

        // Create table for values params descriptions
        Schema::create('param_values_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('param_value_id');
            $table->longText('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('param_value_id')->references('id')->on('param_values');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('param_values_descriptions');
        Schema::dropIfExists('param_values');
        Schema::dropIfExists('param_descriptions');
        Schema::dropIfExists('params');
    }
}

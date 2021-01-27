<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->boolean('active')->nullable()->default(1);
            $table->string('password');
            $table->string('module')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('provider')->nullable(); //social midia
            $table->string('provider_id')->nullable(); //social midia
            $table->json('provider_response')->nullable(); //social midia
            $table->timestamp('last_login')->nullable();

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
        Schema::dropIfExists('users');
    }
}

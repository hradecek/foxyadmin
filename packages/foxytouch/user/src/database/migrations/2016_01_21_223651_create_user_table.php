<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password', 255);
            $table->string('username', 255)
                  ->unique();
            $table->string('email', 255)
                  ->unique();
            $table->boolean('online')
                  ->default(false);
            $table->integer('attempt')
                  ->default(0);
            $table->binary('ip_address')
                  ->nullable();
            $table->integer('sign_in_count')
                  ->default(0);
            $table->timestamp('last_sign_in')
                  ->nullable();
            $table->string('profile_picture_uri')
                  ->nullable();

            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user');
    }
}

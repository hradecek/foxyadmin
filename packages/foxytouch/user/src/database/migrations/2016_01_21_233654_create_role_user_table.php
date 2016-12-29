<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')
                  ->unsigned()
                  ->index();
            $table->integer('user_id')
                  ->unsigned()
                  ->index();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
            $table->foreign('role_id')
                  ->references('id')
                  ->on('role')
                  ->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }
}

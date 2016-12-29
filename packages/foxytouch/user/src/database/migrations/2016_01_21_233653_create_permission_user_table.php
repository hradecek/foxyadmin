<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->integer('permission_id')
                  ->unsigned()
                  ->index();
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
            $table->integer('allow')
                  ->default(0b1111);
            $table->integer('deny')
                  ->default(0b0000);

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permission')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->primary(['permission_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_user');
    }
}

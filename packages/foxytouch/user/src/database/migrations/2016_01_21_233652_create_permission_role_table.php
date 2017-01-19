<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('role_id')
                  ->unsigned()
                  ->index();
            $table->integer('permission_id')
                  ->unsigned()
                  ->index();
            $table->integer('allow')
                  ->default(0b1111);
            $table->integer('deny')
                  ->default(0b0000);

            $table->foreign('role_id')
                  ->references('id')
                  ->on('role')
                  ->onDelete('cascade');
            $table->foreign('permission_id')
                  ->references('id')
                  ->on('permission')
                  ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
    }
}

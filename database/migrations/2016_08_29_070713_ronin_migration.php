<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoninMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_scope', function (Blueprint $table) {
            $table->integer('scope_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->boolean('granted')->default(true);

            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['scope_id', 'role_id']);
        });

        Schema::create('scope_user', function (Blueprint $table) {
            $table->integer('scope_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('granted')->default(true);

            $table->foreign('scope_id')->references('id')->on('scopes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['scope_id', 'user_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_scope');
        Schema::drop('scope_user');
        Schema::drop('role_user');
        Schema::drop('roles');
        Schema::drop('scopes');
    }
}

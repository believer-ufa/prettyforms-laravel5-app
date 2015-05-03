<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->text('description');
			$table->timestamps();
		});
        
		Schema::create('users_roles', function(Blueprint $table)
		{
			$table->integer('user_id');  $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('role_id'); $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_roles');
		Schema::drop('roles');
	}

}

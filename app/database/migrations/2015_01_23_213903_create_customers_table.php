<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 80);
			$table->string('first_name', 20);
			$table->string('last_name', 40);
			$table->string('address1', 80);
			$table->string('address2', 80)->nullable();
			$table->string('city', 60);
			$table->char('state', 2);
			$table->mediumInteger('zip')->unsigned();
			$table->bigInteger('phone')->unsigned();
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
		Schema::drop('customers');
	}

}

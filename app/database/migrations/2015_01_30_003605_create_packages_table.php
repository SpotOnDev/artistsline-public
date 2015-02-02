<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->string('trk_id', 20);
			$table->string('tracking', 30);
			$table->string('status', 20);
			$table->mediumInteger('product_id')->unsigned();
			$table->tinyInteger('quantity')->unsigned();
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
		Schema::drop('packages');
	}

}

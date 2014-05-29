<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUsersTable extends Migration {
	public function up()
	{
		Schema::create('users', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('email', 255)->unique();
            $t->string('first_name');
            $t->string('last_name');
            $t->string('password', 64);
            $t->string('remember_token', 100)->nullable();
            $t->string('activate_token', 100)->nullable();
            $t->integer('status');
            $t->softDeletes();
            $t->timestamps();
        });
	}
	public function down()
	{
		Schema::drop('users');
	}
}

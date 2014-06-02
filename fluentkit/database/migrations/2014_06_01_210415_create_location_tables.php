<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLocationTables extends Migration {
	public function up()
	{
		Schema::create('continents', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('code', 255)->unique();
            $t->string('name');
        });
        
        Schema::create('countries', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('code', 255)->unique();
            $t->string('name');
            $t->integer('continent_id')->unsigned();
            $t->foreign('continent_id')->references('id')->on('continents');
        });
        
        Schema::create('cities', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('name');
            $t->integer('country_id')->unsigned();
            $t->foreign('country_id')->references('id')->on('countries');
        });
	}
	public function down()
	{
        Schema::table('cities', function($table){
            $table->dropForeign('cities_country_id_foreign');
        });
        Schema::drop('cities');
        
        Schema::table('countries', function($table){
            $table->dropForeign('countries_continent_id_foreign');
        });
        Schema::drop('countries');
        
		Schema::drop('continents');
	}
}

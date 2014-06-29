<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateLocationTables extends Migration {
	public function up()
	{
        Schema::create('time_zones', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('string', 255)->unique();
        });

		Schema::create('continents', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('code', 255)->unique();
            $t->string('name');
            $t->integer('time_zone_id')->unsigned()->nullable();
            $t->foreign('time_zone_id')->references('id')->on('time_zones');
        });
        
        Schema::create('countries', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('code', 255)->unique();
            $t->string('name');
            $t->integer('continent_id')->unsigned()->nullable();
            $t->foreign('continent_id')->references('id')->on('continents');
            $t->integer('time_zone_id')->unsigned()->nullable();
            $t->foreign('time_zone_id')->references('id')->on('time_zones');
        });

        Schema::create('regions', function($t) {
            $t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('code', 255);
            $t->string('name');
            $t->integer('country_id')->unsigned()->nullable();
            $t->foreign('country_id')->references('id')->on('countries');
            $t->integer('time_zone_id')->unsigned()->nullable();
            $t->foreign('time_zone_id')->references('id')->on('time_zones');
        });
        
        Schema::create('cities', function($t) {
			$t->engine = 'InnoDB';
            $t->increments('id');
            $t->string('name');
            $t->integer('region_id')->unsigned()->nullable();
            $t->foreign('region_id')->references('id')->on('regions');
            $t->integer('country_id')->unsigned()->nullable();
            $t->foreign('country_id')->references('id')->on('countries');
            $t->integer('time_zone_id')->unsigned()->nullable();
            $t->foreign('time_zone_id')->references('id')->on('time_zones');
        });
	}
	public function down()
	{
        Schema::table('cities', function($table){
            $table->dropForeign('cities_country_id_foreign');
            $table->dropForeign('cities_region_id_foreign');
            $table->dropForeign('cities_time_zone_id_foreign');
        });
        Schema::drop('cities');

        Schema::table('regions', function($table){
            $table->dropForeign('regions_country_id_foreign');
            $table->dropForeign('regions_time_zone_id_foreign');
        });
        Schema::drop('regions');
        
        Schema::table('countries', function($table){
            $table->dropForeign('countries_code_unique');
            $table->dropForeign('countries_continent_id_foreign');
            $table->dropForeign('countries_time_zone_id_foreign');
        });
        Schema::drop('countries');
        
        Schema::table('continents', function($table){
            $table->dropForeign('continents_code_unique');
            $table->dropForeign('continents_time_zone_id_foreign');
        });
		Schema::drop('continents');

        Schema::table('time_zones', function($table){
            $table->dropForeign('time_zones_string_unique');
        });
        Schema::drop('time_zones');        
	}
}

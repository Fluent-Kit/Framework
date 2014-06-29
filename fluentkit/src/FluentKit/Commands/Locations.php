<?php
namespace FluentKit\Commands;

use ZipArchive;
use DB;
use Exception;
use GuzzleHttp\Client;
use League\Csv\Reader;
use Illuminate\Filesystem\FileSystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Locations extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fluentkit:locations';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update global locations via the MaxMind GeoIP2 Database';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		try{
	        $this->info('Fetching MaxMind Locations File...');

	        $client = new Client;
	        $response = $client->get('http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip');
	        
	        $this->info('Extracting CSV File...');
	        
	        $file = new FileSystem;
	        $file->put(storage_path() . '/temp/geolite.zip', $response->getBody());
	        $zip = new ZipArchive;
	        $res = $zip->open(storage_path() . '/temp/geolite.zip');
	        if ($res === true) {
	          //$zip->extractTo(storage_path() . '/temp/');
	            for($i = 0; $i < $zip->numFiles; $i++) {
	                $filename = $zip->getNameIndex($i);
	                $fileinfo = pathinfo($filename);
	                if($fileinfo['basename'] == 'GeoLite2-City-Locations.csv'){
	                    $file->put(storage_path() . '/temp/geolite.csv', $zip->getFromIndex($i));   
	                }
	            }
	          $zip->close();
	        }
	        $file->delete(storage_path() . '/temp/geolite.zip');
	        
	        $this->info('Reading CSV File...');
	        
	        $reader = new Reader(storage_path() . '/temp/geolite.csv');
	        $reader->setDelimiter(',');

	        $data = $reader->fetchAssoc([
	        	'geoname_id',
	        	'continent_code',
	        	'continent_name',
	        	'country_iso_code',
	        	'country_name',
	        	'subdivision_iso_code',
	        	'subdivision_name',
	        	'city_name',
	        	'metro_code',
	        	'time_zone'
	        ]);

		    unset($data[0]);
		    $timezones = [];
		    $continents = [];
		    $countries = [];
		    $regions = [];
		    $cities = [];
		    foreach($data as $line){

		    	if($line['time_zone'] != '' && !isset($timezones[$line['time_zone']])){
		    		$timezones[$line['time_zone']] = true;
		    	}

		    	if($line['continent_code'] != '' && !isset($continents[$line['continent_code']])){
		    		$continents[$line['continent_code']] = $line;
		    	}

		    	if($line['country_iso_code'] != '' && !isset($countries[$line['country_iso_code']])){
		    		$countries[$line['country_iso_code']] = $line;
		    	}

		    	if($line['subdivision_name'] != '' && !isset($regions[$line['subdivision_name']])){
		    		$regions[$line['subdivision_name']] = $line;
		    	}

		    	if($line['city_name'] != '' && !isset($cities[$line['city_name']])){
		    		$cities[$line['city_name']] = $line;
		    	}
		    }

		    //delete all first as constraints exist
		    DB::table('cities')->delete();
		    DB::table('regions')->delete();
		    DB::table('countries')->delete();
		    DB::table('continents')->delete();
		    DB::table('time_zones')->delete();

		    //insert timezones
		    foreach($timezones as $string => $value){
		    	$timezones[$string] = DB::table('time_zones')->insertGetId(
		    		['string' => $string]
		    	);
		    }
		    $this->info(count($timezones) . ' Timezones Imported Into Database');

		    //continents
		    foreach($continents as $continent){
		    	$insert = [
	    			'code' => $continent['continent_code'],
	    			'name' => $continent['continent_name']
	    		];
	    		if((isset($timezones[$continent['time_zone']]))){
	    			$insert['time_zone_id'] = $timezones[$continent['time_zone']];
	    		}
		    	$continents[$continent['continent_code']] = DB::table('continents')->insertGetId($insert);
		    }
		    $this->info(count($continents) . ' Continents Imported Into Database');

		    //countries
		    foreach($countries as $country){
		    	$insert = [
	    			'code' => $country['country_iso_code'],
	    			'name' => $country['country_name']
	    		];
	    		if((isset($timezones[$country['time_zone']]))){
	    			$insert['time_zone_id'] = $timezones[$country['time_zone']];
	    		}
	    		if((isset($continents[$country['continent_code']]))){
	    			$insert['continent_id'] = $continents[$country['continent_code']];
	    		}
		    	$countries[$country['country_iso_code']] = DB::table('countries')->insertGetId($insert);
		    }
	        $this->info(count($countries) . ' Countries Imported Into Database');

	        //regions
	        foreach($regions as $region){
	        	$insert = [
	        		'code' => $region['subdivision_iso_code'],
	        		'name' => $region['subdivision_name']
	        	];
	        	if((isset($timezones[$region['time_zone']]))){
	    			$insert['time_zone_id'] = $timezones[$region['time_zone']];
	    		}
	    		if((isset($countries[$region['country_iso_code']]))){
	    			$insert['country_id'] = $countries[$region['country_iso_code']];
	    		}
	    		$regions[$region['subdivision_name']] = DB::table('regions')->insertGetId($insert);
	        }
	        $this->info( count($regions) . ' Regions Imported Into Database');

	        foreach($cities as $city){
	        	$insert = [
	        		'name' => $city['city_name']
	        	];
	        	if((isset($timezones[$city['time_zone']]))){
	    			$insert['time_zone_id'] = $timezones[$city['time_zone']];
	    		}
	    		if((isset($countries[$city['country_iso_code']]))){
	    			$insert['country_id'] = $countries[$city['country_iso_code']];
	    		}
	    		if((isset($regions[$city['subdivision_name']]))){
	    			$insert['region_id'] = $regions[$city['subdivision_name']];
	    		}
	    		$cities[$city['city_name']] = DB::table('cities')->insertGetId($insert);
	        }
	        $this->info( count($cities) . ' Cities Imported Into Database');

	        $this->info('Deleting Temporary CSV File...');
	        $file->delete(storage_path() . '/temp/geolite.csv');

	        $this->info('Success');
        }catch(Exception $e){
        	$this->error($e->getMessage());
        }
        
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			
		);
	}

}

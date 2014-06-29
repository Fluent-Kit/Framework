<?php
namespace FluentKit\Commands;

use ZipArchive;
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
        $this->info('Fetching MaxMind Locations File...');
        //http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip
        $client = new Client;

        $response = $client->get('http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip');
        
        //return;
        
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
        $data = $reader->fetchOne(1);
        $this->info(print_r($data, true));
        
        $file->delete(storage_path() . '/temp/geolite.csv');
        
        
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

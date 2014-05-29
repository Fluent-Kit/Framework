<?php
namespace FluentKit\Commands;

use File;
use Artisan;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ApplicationBuild extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fluentkit:build';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the application download file. This will remove space hogger files, clear storage dir and tar package for use.';

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

		Artisan::call('optimize', array('--force' => ''));
		//
		$this->info('Building Application Version: ' . $this->option('build-version'));
		if(File::copyDirectory(base_path().'/..', base_path().'/../../fluentkit-'.$this->option('build-version'))){


			$this->info('Application Copied');

			$vendorDir = base_path().'/../../fluentkit-'.$this->option('build-version') . '/fluentkit/app/vendor';

			$rules = array(
		    	'doctrine/annotations'                                       => 'bin tests',
				'doctrine/cache'                                             => 'bin tests',
				'doctrine/collections'                                       => 'tests',
				'doctrine/common'                                            => 'README* UPGRADE* phpunit.xml* build* tests bin lib/vendor',
				'doctrine/dbal'                                              => 'bin build* docs docs2 tests lib/vendor',
				'doctrine/inflector'                                         => 'phpunit.xml* README* tests',
				'filp/whoops'                                                => 'README.md phpunit.xml* tests examples',
				'ircmaxell/password-compat'                                  => 'README.md test',
				'laravel/framework'                                          => 'build tests',
				'monolog/monolog'                                            => 'README.markdown phpunit.xml* tests doc',
				'nikic/php-parser'                                           => 'README.md CHANGELOG* phpunit.xml* doc test test_old',
				'patchwork/utf8'                                             => 'README.md tests',
				'predis/predis'                                              => 'README.md CHANGELOG* phpunit.xml* examples tests bin FAQ CONTRIBUTING*',
				'swiftmailer/swiftmailer'                                    => 'CHANGES README* build* doc docs notes test-suite tests create_pear_package.php package*',
				'symfony/browser-kit/Symfony/Component/BrowserKit'           => 'CHANGELOG* README* Tests',
				'symfony/console/Symfony/Component/Console'                  => 'CHANGELOG* README* Tests',
				'symfony/css-selector/Symfony/Component/CssSelector'         => 'CHANGELOG* README* Tests',
				'symfony/debug/Symfony/Component/Debug'                      => 'CHANGELOG* README* Tests',
				'symfony/dom-crawler/Symfony/Component/DomCrawler'           => 'CHANGELOG* README* Tests',
				'symfony/event-dispatcher/Symfony/Component/EventDispatcher' => 'CHANGELOG* README* Tests',
				'symfony/filesystem/Symfony/Component/Filesystem'            => 'CHANGELOG* README* Tests',
				'symfony/finder/Symfony/Component/Finder'                    => 'CHANGELOG* README* Tests',
				'symfony/http-foundation/Symfony/Component/HttpFoundation'   => 'CHANGELOG* README* Tests',
				'symfony/http-kernel/Symfony/Component/HttpKernel'           => 'CHANGELOG* README* Tests',
				'symfony/process/Symfony/Component/Process'                  => 'CHANGELOG* README* Tests',
				'symfony/routing/Symfony/Component/Routing'                  => 'CHANGELOG* README* Tests',
				'symfony/translation/Symfony/Component/Translation'          => 'CHANGELOG* README* Tests',

				//Packages:
				'anahkiasen/former'                                          => 'README* CHANGELOG* CONTRIBUTING* phpunit.xml* tests',
				'anahkiasen/html-object'                                     => 'README* CHANGELOG* phpunit.xml* examples tests',
				'anahkiasen/underscore-php'                                  => 'README* CHANGELOG* phpunit.xml* tests',
				'intervention/image'                                         => 'README* phpunit.xml* public tests',
				'jasonlewis/basset'                                          => 'README* phpunit.xml* tests/Basset',
				'leafo/lessphp'                                              => 'README* docs tests Makefile package.sh',
				'kriswallsmith/assetic'                                      => 'CHANGELOG* phpunit.xml* tests docs',
				'mrclay/minify'                                              => 'HISTORY* MIN.txt UPGRADING* README* min_extras min_unit_tests min/builder min/config* min/quick-test* min/utils.php min/groupsConfig.php min/index.php',
				'phpoffice/phpexcel'                                         => 'Examples unitTests changelog.txt',
				'phenx/php-font-lib'                                         => 'www',
				'mustache/mustache'                                          => 'bin test',
				'mockery/mockery'                                            => 'examples tests',
				'dompdf/dompdf'                                              => 'www',

				//Additional packages
				'phpdocumentor/reflection-docblock'                          => 'README* CHANGELOG* phpunit.xml* tests',
				'rcrowe/twigbridge'                                          => 'README* CHANGELOG* phpunit.xml* tests',
				'twig/twig'                                                  => 'README* CHANGELOG* phpunit.xml* test doc',
				'cartalyst/sentry'                                           => 'README* CHANGELOG* phpunit.xml* tests docs',
				'maximebf/debugbar'                                          => 'README* CHANGELOG* phpunit.xml* tests demo docs',
				'dflydev/markdown'                                           => 'README* CHANGELOG* phpunit.xml* tests',
				'jeremeamia/SuperClosure'                                    => 'README* CHANGELOG* phpunit.xml* tests demo',
				'nesbot/carbon'                                              => 'README* CHANGELOG* phpunit.xml* tests',

				//my additions
		        'phpseclib/phpseclib' => 'tests',
		        'd11wtq/boris' => 'bin',
		        'jeremeamia/SuperClosure' => 'tests demo',
		        'nesbot/carbon' => 'tests',
		        'psr/log' => 'Test',
		        'stack/builder' => 'tests',
				'maximebf/debugbar' => 'README* CHANGELOG* tests demo docs',
		        'barryvdh/laravel-debugbar' => 'readme* tests',
		        'barryvdh/laravel-httpcache' => 'readme* tests phpunit.xml',
		        'humweb/filters' => 'tests *.yml LICENSE README.md phpunit.xml',
		        'justinrainbow/json-schema' => 'test docs phpunit.xml* LICENSE README.md',
		        'kriswallsmith/assetic' => 'Gemfile LICENSE README.md CHANGELOG*',
		        'jasonlewis/resource-watcher' => 'tests watcher phpunit.xml LICENSE README.md',
		        'seld/jsonlint' => 'tests phpunit.xml* LICENSE README.mdown CHANGELOG*',
		        'herrera-io/json' => 'phpunit.xml* LICENSE README.md',
		        'cartalyst/sentry' => 'changelog.md docs license.txt phpunit.xml* readme.md schema tests src/Cartalyst/Sentry/Facades/CI src/Cartalyst/Sentry/Facades/FuelPHP src/Cartalyst/Sentry/Facades/Kohana src/Cartalyst/Sentry/Cookies/CICookie.php src/Cartalyst/Sentry/Cookies/FuelPHPCookie.php src/Cartalyst/Sentry/Cookies/KohanaCookie.php src/Cartalyst/Sentry/Groups/Kohana src/Cartalyst/Sentry/Sessions/CISession.php src/Cartalyst/Sentry/Sessions/FuelPHPSession.php src/Cartalyst/Sentry/Sessions/KohanaSession.php src/Cartalyst/Sentry/Throttling/Kohana src/Cartalyst/Sentry/Users/Kohana',
			);

		    $filesystem = new Filesystem();

		    $this->info('Performing Package Deletes');

		    foreach($rules as $packageDir => $rule){
		        if(!file_exists($vendorDir . '/' . $packageDir)){
		            continue;
		        }
		        $patterns = explode(' ', $rule);
		        foreach($patterns as $pattern){
		            try{
		                $finder = new Finder();
		                foreach($finder->name($pattern)->in( $vendorDir . '/' . $packageDir) as $file){
		                    if($file->isDir()){
		                        $filesystem->deleteDirectory($file->getRealPath());
		                    }elseif($file->isFile()){
		                        $filesystem->delete($file->getRealPath());
		                    }
		                }
		            }catch(\Exception $e){
		                //TODO; check why error are thrown on deleting directories
		                //$this->error("Could not parse $packageDir ($pattern): ".$e->getMessage());
		            }
		        }
		    }


			$files = File::allFiles(base_path().'/../../fluentkit-'.$this->option('build-version'));
			$this->info('Performing Deletes, Optimisations and Comment Removal');
			foreach($files as $file){

				//remove artisan command
				if(strpos($file->getRealPath(), '/app/start/artisan.php') !== false){
					$content = str_replace('Artisan::add(new \FluentKit\Commands\ApplicationBuild);', '', File::get($file->getRealPath()));
					File::put($file->getRealPath(), $content);
				}

				//delete files we dont need
				if( 
					in_array( File::extension($file->getRealPath()), array('txt','md', 'markdown', 'sh', 'exe') ) || 
					in_array( $file->getFilename(), array('composer.phar', 'composer.json', 'composer.lock', 'error_log', 'phpunit.xml', 'phpunit.php', 'phpunit.xml.dist') ) || 
					strpos($file->getRealPath(), '.git') !== false || 
					strpos($file->getRealPath(), '.gitignore') !== false || 
					strpos($file->getRealPath(), '.gitattributes') !== false || 
					strpos($file->getRealPath(), '.travis') !== false || 
					strpos($file->getRealPath(), 'cgi-bin') !== false || 
					strpos($file->getRealPath(), '/app/storage/') !== false || 
					strpos($file->getRealPath(), '/storage/') !== false || 
					strpos($file->getRealPath(), '/FluentKit/Commands/ApplicationBuild.php') !== false || 
					strpos($file->getRealPath(), '/fluentkit/server.php') !== false 
					){
					File::delete($file->getRealPath());
					continue;
				}

				//optimize files
				if(File::extension($file->getRealPath()) == 'php' || $file->getFilename() == 'artisan'){
					$content = File::get($file->getRealPath());
					$content = $this->removeComments($content);
					$content = preg_replace('/\n\s*\n/', "\n", $content);
					File::put($file->getRealPath(), $content);
				}		
			}
			
			$this->info('Application Build Ready');
			$this->info('Application Build Stats:');
			$line = system('du -sh '.base_path().'/../../fluentkit-'.$this->option('build-version'), $return);
			$this->info('Compressing Application');
			$line = system('tar -zcf '.base_path().'/../../fluentkit-'.$this->option('build-version').'.tar.gz '.base_path().'/../../fluentkit-'.$this->option('build-version'));
			$this->info('Compressed Application Build Stats:');
			$line = system('du -sh '.base_path().'/../../fluentkit-'.$this->option('build-version').'.tar.gz', $return);
			
			if( File::deleteDirectory( base_path().'/../../fluentkit-'.$this->option('build-version') ) ){
				$this->info('Temporary Application Build Folder Removed');
			}
			
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
			//array('build-version', InputArgument::REQUIRED, 'Build Version'),
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
			array('build-version', null, InputOption::VALUE_REQUIRED, 'Build Version', null),
		);
	}


	private function removeComments($str = ''){
		$newstr = '';
		$commentTokens = array(T_COMMENT);
		if (defined('T_DOC_COMMENT'))
		    $commentTokens[] = T_DOC_COMMENT; // PHP 5
		if (defined('T_ML_COMMENT'))
		    $commentTokens[] = T_ML_COMMENT;  // PHP 4

		$tokens = token_get_all($str);

		foreach ($tokens as $token) {    
		    if (is_array($token)) {
		        if (in_array($token[0], $commentTokens))
		            continue;

		        $token = $token[1];
		    }

		    $newstr .= $token;
		}
		return $newstr;
	}

}

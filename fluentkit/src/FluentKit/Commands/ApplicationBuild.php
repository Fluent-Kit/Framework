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
        $app = $this->getLaravel();
        $version = $app['config']->get('app.version');

		$this->call('optimize', array('--force' => ''));
		//
		$this->info('Building Application Version: ' . $version);
		if($app['files']->copyDirectory(base_path().'/..', base_path().'/../../fluentkit-'.$version)){


			$this->info('Application Copied');

			$vendorDir = base_path().'/../../fluentkit-'.$version . '/fluentkit/app/vendor';
            /*
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
            */
            /*
			$files = $app['files']->allFiles(base_path().'/../../fluentkit-'.$version);
			$this->info('Performing Deletes, Optimisations and Comment Removal');
            
            $dirs_to_remove = array(
                'tests',
                '.git',
                'node_modules',
                'bower_components',
                'cgi-bin',
                'vendor/phpunit',
                'vendor/sebastien',
                'vendor/mockery'
            );
            
            
			foreach($files as $file){
                
                if($app['files']->isDirectory($file->getRealPath())){
                    foreach($dirs_to_remove as $dir){
                        if(str_contains($file->getRealPath(), $dir)){
                            $app['files']->deleteDirectory($file->getRealPath());   
                        }
                    }
                }

				//remove artisan command
				if(str_contains($file->getRealPath(), '/app/start/artisan.php')){
					$content = str_replace('Artisan::add(new \FluentKit\Commands\ApplicationBuild);', '', $app['files']->get($file->getRealPath()));
					$app['files']->put($file->getRealPath(), $content);
				}

				//delete files we dont need
				if( 
					in_array( $app['files']->extension($file->getRealPath()), array('txt','md', 'markdown', 'sh', 'exe') ) || 
					in_array( $file->getFilename(), array('composer.phar', 'composer.json', 'composer.lock', 'error_log', 'phpunit.xml', 'phpunit.php', 'phpunit.xml.dist', 'bower.json', 'package.json', 'gulpfile.js') ) || 
					str_contains($file->getRealPath(), '.git') || 
					str_contains($file->getRealPath(), '.gitignore') || 
					str_contains($file->getRealPath(), '.gitattributes') || 
					str_contains($file->getRealPath(), '.travis') || 
					str_contains($file->getRealPath(), 'cgi-bin') || 
					str_contains($file->getRealPath(), '/storage/cache/') ||
                    str_contains($file->getRealPath(), '/storage/debugbar/') ||
                    str_contains($file->getRealPath(), '/storage/fluentkit') ||
                    str_contains($file->getRealPath(), '/storage/logs/') ||
                    str_contains($file->getRealPath(), '/storage/meta/') ||
                    str_contains($file->getRealPath(), '/storage/sessions/') ||
                    str_contains($file->getRealPath(), '/storage/views/') ||
					str_contains($file->getRealPath(), '/FluentKit/Commands/ApplicationBuild.php') || 
                    str_contains($file->getRealPath(), 'node_modules') || 
                    str_contains($file->getRealPath(), 'bower_components') || 
					str_contains($file->getRealPath(), '/fluentkit/server.php')
					){
					$app['files']->delete($file->getRealPath());
					continue;
				}
                

				//optimize files
				if($app['files']->extension($file->getRealPath()) == 'php' || $file->getFilename() == 'artisan'){
					$content = $app['files']->get($file->getRealPath());
					$content = $this->removeComments($content);
					$content = preg_replace('/\n\s*\n/', "\n", $content);
					$app['files']->put($file->getRealPath(), $content);
				}		
			}
            */
            
			system('cd '.base_path().'/../../fluentkit-'.$version.'/fluentkit && composer update --prefer-dist --no-dev');
            $line = system('du -sh '.base_path().'/../../fluentkit-'.$version, $return);
            $app['files']->deleteDirectory(base_path().'/../../fluentkit-'.$version.'/fluentkit/bower_components');
            $app['files']->deleteDirectory(base_path().'/../../fluentkit-'.$version.'/fluentkit/node_modules');
            $files = $app['files']->allFiles(base_path().'/../../fluentkit-'.$version);
			$this->info('Performing Deletes, Optimisations and Comment Removal');
			foreach($files as $file){
                //optimize files
				if($app['files']->extension($file->getRealPath()) == 'php' || $file->getFilename() == 'artisan'){
					$content = $app['files']->get($file->getRealPath());
					$content = $this->removeComments($content);
					$content = preg_replace('/\n\s*\n/', "\n", $content);
					$app['files']->put($file->getRealPath(), $content);
				}
            }
			$this->info('Application Build Ready');
			$this->info('Application Build Stats:');
			$line = system('du -sh '.base_path().'/../../fluentkit-'.$version, $return);
			$this->info('Compressing Application');
			$line = system('tar -zcf '.base_path().'/../../fluentkit-'.$version.'.tar.gz '.base_path().'/../../fluentkit-'.$version);
			$this->info('Compressed Application Build Stats:');
			$line = system('du -sh '.base_path().'/../../fluentkit-'.$version.'.tar.gz', $return);
			
			//if( $app['files']->deleteDirectory( base_path().'/../../fluentkit-'.$version ) ){
			//	$this->info('Temporary Application Build Folder Removed');
	//		}
            $this->info('Application Build Completed!');
			
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

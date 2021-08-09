<?php
declare( strict_types = 1 );

namespace Zebrands\Catalogue\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Foundation\AliasLoader;
use Symfony\Component\Console\Output\ConsoleOutput;

use Zebrands\Catalogue\Providers\Register   as CatalogueRegister;
use Zebrands\Catalogue\Domain\ContextDomain;

class Laravel extends ServiceProvider
{
    const BASE        = 'Zebrands';
    protected $config =  [
        'seeds'      => '/Database/Seeds',
        'routes'     => '/Routes/api.php',
        'migrations' => '/Database/Migrations',
        'providers'  => [
            CatalogueRegister::class,
        ]
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() :void
    {
        \Schema::defaultStringLength(191);
        $config = (object) $this->config;

        $loader = AliasLoader::getInstance();
        foreach ($config->providers as $class) {

            $provider = new $class;

            $path = explode('\\Providers', $class)[0];
            $path = str_replace(self::BASE, '/src', str_replace('\\', '/', $path));
            $path = explode(self::BASE, __DIR__)[0].self::BASE.$path;

            #aplica solo si es por consola
            if ($this->app->runningInConsole()) {
                #ejecucicion de migrations
                if(is_dir($path.$config->migrations)){
                    $this->loadMigrationsFrom($path.$config->migrations);
                }
                #ejecucion de seeds
                if(is_dir($path.$config->seeds) && $this->isConsoleCommandContains(
                        [ 'db:seed', '--seed' ], 
                        [ '--class', 'help', '-h' ]
                    )){
                    $this->addSeedsAfterConsoleCommandFinished($path.$config->seeds);
                }
            }
            else if(file_exists($path.$config->routes)){
                $this->loadRoutesFrom($path.$config->routes); 
            }

            $alias = array_merge($provider->contracts??[], $provider->alias??[]);

            foreach ($provider->contracts??[] as $contract => $notuse) {
                $this->setAlias($loader, $provider::ALIAS, $contract);
            }

            foreach ($provider->alias??[] as $contract) {
                $contract = $contract['laravel']??$contract;
                $this->setAlias($loader, $provider::ALIAS,  $contract);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() :void
    {   
        foreach ($this->config['providers'] as $class) {

            $provider = new $class;

            foreach ($provider->contracts??[] as $contract => $repository) {

                $repository = $repository['laravel']??$repository;
                #register contract
               $this->app->bind($contract, $repository);
            }
            $this->commands($provider->commands??[]);
        }

        #creo un Context para tener la data auth
        $this->app->singleton('Context', function(){
            $request = app(\Illuminate\Http\Request::class);
            return new ContextDomain($request);
        });
    }


    protected function setAlias(AliasLoader &$loader,  string $alias, string $contract) :void
    {
        $service = explode('\\', $contract);
        $service = end($service);
        #dump("$alias\\$service => $contract");
        $loader->alias(
            "$alias\\$service", #alias
            $contract           #class
        );
    }

    #no usar esto agrega mas tiempo a los request, mejor en composer
    #$helpers       = app_path()."/../*/*/*/$alias/Infrastructure/Laravel/Helpers/*";
    #foreach (glob($helpers) as $helper){
    #    require_once($helper);
    #}    }

    /**
     * Get a value that indicates whether the current command in console
     * contains a string in the specified $fields.
     *
     * @param string|array $contain_options
     * @param string|array $exclude_options
     *
     * @return bool
     */
    protected function isConsoleCommandContains($contain_options, $exclude_options = null) : bool
    {
        $args = Request::server('argv', null);
        if (is_array($args)) {
            $command = implode(' ', $args);
            if (
                Str::contains($command, $contain_options) &&
                ($exclude_options == null || !Str::contains($command, $exclude_options))
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add seeds from the $seed_path after the current command in console finished.
     */
    protected function addSeedsAfterConsoleCommandFinished($seeds_path)
    {
        Event::listen(CommandFinished::class, function(CommandFinished $event) 
            use ($seeds_path) {
            // Accept command in console only,
            // exclude all commands from Artisan::call() method.
            if ($event->output instanceof ConsoleOutput) {
                $this->addSeedsFrom($seeds_path);
            }
        });
    }

    /**
     * Register seeds.
     *
     * @param string  $seeds_path
     * @return void
     */
    protected function addSeedsFrom($seeds_path)
    {
        $file_names = glob( $seeds_path . '/*.php');

        foreach ($file_names as $filename)
        {
            $classes = $this->getClassesFromFile($filename);
            foreach ($classes as $class) {
                echo "\033[1;33mSeeding:\033[0m {$class}\n";
                $startTime = microtime(true);
                Artisan::call('db:seed', [ '--class' => $class, '--force' => '' ]);
                $runTime = round(microtime(true) - $startTime, 2);
                echo "\033[0;32mSeeded: \033[0m {$class} ({$runTime} seconds)\n";
            }
        }
    }

    /**
     * Get full class names declared in the specified file.
     *
     * @param string $filename
     * @return array an array of class names.
     */
    private function getClassesFromFile(string $filename) : array
    {
        // Get namespace of class (if vary)
        $namespace = "";
        $lines = file($filename);
        $namespaceLines = preg_grep('/^namespace /', $lines);
        if (is_array($namespaceLines)) {
            $namespaceLine = array_shift($namespaceLines);
            $match = array();
            preg_match('/^namespace (.*);$/', $namespaceLine, $match);
            $namespace = array_pop($match);
        }

        // Get name of all class has in the file.
        $classes = array();
        $php_code = file_get_contents($filename);
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if ($namespace !== "") {
                    $classes[] = $namespace . "\\$class_name";
                } else {
                    $classes[] = $class_name;
                }
            }
        }

        return $classes;
    }
}
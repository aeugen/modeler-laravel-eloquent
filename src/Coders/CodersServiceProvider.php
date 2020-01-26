<?php

namespace Aeugen\Modeler\Coders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Aeugen\Modeler\Coders\Console\CodeModelsCommand;
use Aeugen\Modeler\Coders\Model\Config;
use Aeugen\Modeler\Coders\Model\Factory as ModelFactory;
use Aeugen\Modeler\Support\Classify;

class CodersServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/modeler.php' => config_path('modeler.php'),
            ], 'aeugen-modeler');

            $this->commands([
                CodeModelsCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelFactory();
    }

    /**
     * Register Model Factory.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton(ModelFactory::class, function ($app) {
            return new ModelFactory(
                $app->make('db'),
                $app->make(Filesystem::class),
                new Classify(),
                new Config($app->make('config')->get('modeler'))
            );
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [ModelFactory::class];
    }
}

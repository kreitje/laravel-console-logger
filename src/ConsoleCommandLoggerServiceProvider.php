<?php

namespace Kreitje\LaravelConsoleLogger;

use Illuminate\Support\ServiceProvider;
use Kreitje\LaravelConsoleLogger\Commands\CleanupLogCommand;

class ConsoleCommandLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/commandlogger.php' => base_path('config/commandlogger.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../migrations/' => database_path('migrations')
            ], 'migrations');

            $this->loadMigrationsFrom(__DIR__.'/../migrations');

            $this->bindCommands();
            $this->registerCommands();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/commandlogger.php', 'commandlogger');
    }

    public function bindCommands()
    {
        $this->app->bind(
            'command.consolelogger:cleanup',
            CleanupLogCommand::class
        );
    }

    public function registerCommands()
    {
        $this->commands([
            'command.consolelogger:cleanup'
        ]);
    }
}

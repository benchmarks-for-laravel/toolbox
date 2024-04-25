<?php

namespace BenchmarksForLaravel\Toolbox;

use BenchmarksForLaravel\Toolbox\Console\Commands\RunBenchmarkCommand;
use Illuminate\Support\ServiceProvider;

class ToolboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'benchmarks-for-laravel',
        );

        $this->app->singleton('benchmarks', fn() => new ToolboxManager());
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunBenchmarkCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/config.php' => config_path('benchmarks-for-laravel.php'),
        ]);
    }
}

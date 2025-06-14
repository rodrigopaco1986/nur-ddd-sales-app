<?php

namespace Src\Sales\Service\Application\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->commands([
            \Src\Sales\Service\Infraestructure\Console\Commands\KafkaConsumeServicesCommand::class,
        ]);
    }
}

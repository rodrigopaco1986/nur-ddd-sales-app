<?php

namespace Src\Sales\Patient\Application\Providers;

use Illuminate\Support\ServiceProvider;

class PatientProvider extends ServiceProvider
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
            \Src\Sales\Patient\Infraestructure\Console\Commands\KafkaConsumePatientsCommand::class,
        ]);
    }
}

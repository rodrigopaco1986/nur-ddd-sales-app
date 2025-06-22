<?php

namespace App\Providers;

use App\Notifications\NotificationProducerInterface;
use Illuminate\Support\ServiceProvider;
use Src\Sales\Order\Infraestructure\Events\Kafka\KafkaNotificationProducer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationProducerInterface::class,
            KafkaNotificationProducer::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

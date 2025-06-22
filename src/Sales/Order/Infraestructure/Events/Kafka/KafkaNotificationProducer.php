<?php

namespace Src\Sales\Order\Infraestructure\Events\Kafka;

use App\Notifications\NotificationProducerInterface;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Src\Sales\Order\Domain\Exceptions\KafkaNotificationException;
use Throwable;

class KafkaNotificationProducer implements NotificationProducerInterface
{
    /**
     * Publishes a message to a Kafka topic.
     *
     * @param  array  $payload
     * @return bool
     */
    public function publish(string $topic, string $key, string $payload): void
    {
        try {

            Kafka::publish(config('kafka.brokers'))
                ->onTopic($topic)
                ->withBodyKey($key, $payload)
                ->send();

        } catch (Throwable $e) {
            Log::error("Failed to publish message to Kafka topic [{$topic}].", [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            throw new KafkaNotificationException($topic);
        }
    }
}

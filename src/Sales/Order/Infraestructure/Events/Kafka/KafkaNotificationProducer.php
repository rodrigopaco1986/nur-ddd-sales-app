<?php

namespace Src\Sales\Order\Infraestructure\Events\Kafka;

use App\Notifications\NotificationProducerInterface;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Src\Sales\Order\Infraestructure\Exceptions\KafkaNotificationException;
use Throwable;

class KafkaNotificationProducer implements NotificationProducerInterface
{
    /**
     * Publishes a message to a Kafka topic.
     *
     * @param  array  $payload
     * @return bool
     */
    public function publish(string $topic, ?string $key, $payload): void
    {
        try {

            if ($key) {
                Kafka::publish(config('kafka.brokers'))
                    ->onTopic($topic)
                    ->withBodyKey($key, $payload)
                    ->send();
            } else {
                Kafka::publish(config('kafka.brokers'))
                    ->onTopic($topic)
                    ->withBody($payload)
                    ->send();
            }

        } catch (Throwable $e) {
            Log::error("Failed to publish message to Kafka topic [{$topic}].", [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            throw new KafkaNotificationException($topic);
        }
    }
}

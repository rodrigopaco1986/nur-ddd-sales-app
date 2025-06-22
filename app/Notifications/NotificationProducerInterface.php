<?php

namespace App\Notifications;

interface NotificationProducerInterface
{
    /**
     * Publish a notification message to a message broker.
     *
     * @param  string  $topic  The topic or queue name to publish to.
     * @param  string  $key  The message key, used for partitioning.
     * @param  string  $payload  The message body.
     */
    public function publish(string $topic, string $key, string $payload): void;
}

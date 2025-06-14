<?php

namespace Src\Sales\Service\Infraestructure\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Src\Sales\Service\Infraestructure\Kafka\ServiceBrokerHandler;

class KafkaConsumeServicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     *
     * @var string
     */
    protected $signature = 'kafka:consume-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a single Kafka consumer to listen for services topics.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $topics = array_keys(config('kafka_topics_consumers.services'));
        $consumerGroupId = config('kafka.consumer_group_id');
        $brokers = config('kafka.brokers');

        if (empty($topics)) {
            $this->error('No topics are configured in config/kafka_consumers.php or provided via --topics option. Exiting.');

            return;
        }

        $this->info('Starting Kafka service consumer...');
        $this->info("Consumer Group ID: [{$consumerGroupId}]");
        $this->info('Listening on topics: [' . implode(', ', $topics) . ']');

        // Create a single consumer instance, subscribing to all topics related to patients,
        $consumer = Kafka::consumer($topics, $consumerGroupId)
            ->withBrokers($brokers)
            ->withConsumerGroupId($consumerGroupId)
            ->withHandler(new ServiceBrokerHandler)
            ->withAutoCommit(false)
            ->build();

        $consumer->consume();
    }
}

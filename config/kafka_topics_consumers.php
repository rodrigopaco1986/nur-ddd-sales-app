<?php

// topics to be subscribed and its consumer
// TOPIC => CONSUMER
return [
    'patients' => [
        'patient.created' => \Src\Sales\Patient\Infraestructure\Kafka\Handlers\PatientUpsertHandler::class,
        'patient.updated' => \Src\Sales\Patient\Infraestructure\Kafka\Handlers\PatientUpsertHandler::class,
        'patient.deleted' => null,
    ],
    // services
    'services' => [
        'service.created' => \Src\Sales\Service\Infraestructure\Kafka\Handlers\ServiceUpsertHandler::class,
        'service.updated' => \Src\Sales\Service\Infraestructure\Kafka\Handlers\ServiceUpsertHandler::class,
        'service.deleted' => null,
    ],
];

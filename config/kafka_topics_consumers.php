<?php

// topics to be subscribed and its consumer
// TOPIC => CONSUMER
return [
    'patients' => [
        'patient.created' => \Src\Sales\Patient\Infraestructure\Kafka\Handlers\PatientUpsertHandler::class,
        'patient.updated' => \Src\Sales\Patient\Infraestructure\Kafka\Handlers\PatientUpsertHandler::class,
        'patient.deleted' => \Src\Sales\Patient\Infraestructure\Kafka\Handlers\PatientUpsertHandler::class,
    ],
    // services
    'services' => [
        'service.created' => [],
        'service.updated' => [],
        'service.deleted' => [],
    ],
];

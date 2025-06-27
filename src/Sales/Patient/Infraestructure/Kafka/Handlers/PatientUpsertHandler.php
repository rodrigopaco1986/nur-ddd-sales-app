<?php

namespace Src\Sales\Patient\Infraestructure\Kafka\Handlers;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ConsumerMessage;
use Src\Sales\Patient\Domain\Entities\Patient;
use Src\Sales\Patient\Infraestructure\Repositories\PatientRepository;

class PatientUpsertHandler
{
    public function __invoke(ConsumerMessage $message)
    {
        $body = $message->getBody();

        echo '<pre>';
        print_r('Saving new patient from kafka...');
        echo '</pre>';
        Log::info('Saving new patient from kafka...', $body);

        $patient = new Patient(
            $body['user_id'],
            $body['full_name'],
            $body['dni'],
            '',
            $body['phone'],
            $body['email'],
        );

        $patientRepository = new PatientRepository;
        $patientRepository->upsert($patient);
    }
}

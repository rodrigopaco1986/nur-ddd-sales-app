<?php

namespace Src\Sales\Patient\Infraestructure\Mappers;

use Src\Sales\Patient\Domain\Entities\Patient as PatientEntity;
use Src\Sales\Patient\Infraestructure\Models\Patient as EloquentPatient;

class PatientMapper
{
    public static function toEntity(EloquentPatient $patient): PatientEntity
    {
        $patientEntity = new PatientEntity(
            $patient->code,
            $patient->name,
            $patient->nit,
            $patient->address,
            $patient->phone,
            $patient->email,
        );
        $patientEntity->setId($patient->id);

        return $patientEntity;
    }

    public static function toModel(PatientEntity $patient): EloquentPatient
    {
        $eloquentPatientModel = (new EloquentPatient)
            ->fill([
                'code' => $patient->getCode(),
                'nit' => $patient->getNit(),
                'name' => $patient->getName(),
                'address' => $patient->getAddress(),
                'phone' => $patient->getPhone(),
                'email' => $patient->getEmail(),
            ]);

        return $eloquentPatientModel;
    }
}

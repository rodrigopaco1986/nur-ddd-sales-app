<?php

namespace Src\Sales\Patient\Infraestructure\Repositories;

use Src\Sales\Patient\Domain\Entities\Patient;
use Src\Sales\Patient\Domain\Repositories\PatientRepositoryInterface;
use Src\Sales\Patient\Infraestructure\Mappers\PatientMapper;
use Src\Sales\Patient\Infraestructure\Models\Patient as EloquentPatient;

class PatientRepository implements PatientRepositoryInterface
{
    public function findById(string $id): ?Patient
    {
        $eloquentPatient = EloquentPatient::find($id);

        if ($eloquentPatient) {
            return PatientMapper::toEntity($eloquentPatient);
        }

        return null;
    }

    public function findByCode(string $code): ?Patient
    {
        $eloquentPatient = EloquentPatient::where('code', $code)->first();

        if ($eloquentPatient) {
            return PatientMapper::toEntity($eloquentPatient);
        }

        return null;
    }

    public function save(Patient $patient): Patient
    {
        $eloquentPatientModel = PatientMapper::toModel($patient);
        $eloquentPatientModel->save();

        return PatientMapper::toEntity($eloquentPatientModel);
    }

    public function upsert(Patient $patient): Patient
    {
        $eloquentPatient = EloquentPatient::where('code', $patient->getCode())->first();
        if ($eloquentPatient) {
            $eloquentPatient->fill([
                'code' => $patient->getCode(),
                'nit' => $patient->getNit(),
                'name' => $patient->getName(),
                'address' => $patient->getAddress(),
                'phone' => $patient->getPhone(),
                'email' => $patient->getEmail(),
            ])->save();

            return PatientMapper::toEntity($eloquentPatient);

        } else {
            return $this->save($patient);
        }
    }
}

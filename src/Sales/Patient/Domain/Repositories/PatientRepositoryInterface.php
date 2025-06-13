<?php

namespace Src\Sales\Patient\Domain\Repositories;

use Src\Sales\Patient\Domain\Entities\Patient;

interface PatientRepositoryInterface
{
    public function findById(string $id): ?Patient;

    public function findByCode(string $code): ?Patient;

    public function save(Patient $patient): ?Patient;

    public function upsert(Patient $patient): ?Patient;
}

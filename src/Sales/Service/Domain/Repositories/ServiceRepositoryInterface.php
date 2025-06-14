<?php

namespace Src\Sales\Service\Domain\Repositories;

use Src\Sales\Service\Domain\Entities\Service;

interface ServiceRepositoryInterface
{
    public function findById(string $id): ?Service;

    public function findByIds(array $ids): array;

    public function findByCode(string $code): ?Service;

    public function findByCodes(array $codes): array;

    public function save(Service $service): ?Service;

    public function upsert(Service $service): ?Service;
}

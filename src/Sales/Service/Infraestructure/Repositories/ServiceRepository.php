<?php

namespace Src\Sales\Service\Infraestructure\Repositories;

use Src\Sales\Service\Domain\Entities\Service;
use Src\Sales\Service\Domain\Repositories\ServiceRepositoryInterface;
use Src\Sales\Service\Infraestructure\Mappers\ServiceMapper;
use Src\Sales\Service\Infraestructure\Models\Service as EloquentService;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function findById(string $id): ?Service
    {
        $eloquentService = EloquentService::find($id);

        if ($eloquentService) {
            return ServiceMapper::toEntity($eloquentService);
        }

        return null;
    }

    public function findByIds(array $ids): array
    {
        $response = [];

        foreach ($ids as $id) {
            $response[] = $this->findById($id);
        }

        return $response;
    }

    public function findByCode(string $code): ?Service
    {
        $eloquentService = EloquentService::where('code', $code)->first();

        if ($eloquentService) {
            return ServiceMapper::toEntity($eloquentService);
        }

        return null;
    }

    public function findByCodes(array $codes): array
    {
        $response = [];

        foreach ($codes as $code) {
            $response[] = $this->findByCode($code);
        }

        return $response;
    }

    public function save(Service $service): Service
    {
        $eloquentServiceModel = ServiceMapper::toModel($service);
        $eloquentServiceModel->save();

        return ServiceMapper::toEntity($eloquentServiceModel);
    }

    public function upsert(Service $service): Service
    {
        $eloquentService = EloquentService::where('code', $service->getCode())->first();
        if ($eloquentService) {
            $eloquentService->fill([
                'code' => $service->getCode(),
                'name' => $service->getName(),
                'unit' => $service->getUnit(),
                'description' => $service->getDescription(),
            ])->save();
        }

        return $this->save($service);
    }
}

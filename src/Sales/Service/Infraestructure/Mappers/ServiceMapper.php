<?php

namespace Src\Sales\Service\Infraestructure\Mappers;

use Src\Sales\Service\Domain\Entities\Service as ServiceEntity;
use Src\Sales\Service\Infraestructure\Models\Service as EloquentService;

class ServiceMapper
{
    public static function toEntity(object $service): ServiceEntity
    {
        $serviceEntity = new ServiceEntity(
            $service->code,
            $service->name,
            $service->unit,
            $service->description,
        );
        $serviceEntity->setId($service->id);

        return $serviceEntity;
    }

    public static function toModel(ServiceEntity $patient): EloquentService
    {
        $eloquentServiceModel = (new EloquentService)
            ->fill([
                'code' => $patient->getCode(),
                'name' => $patient->getName(),
                'unit' => $patient->getUnit(),
                'description' => $patient->getDescription(),
            ]);

        return $eloquentServiceModel;
    }
}

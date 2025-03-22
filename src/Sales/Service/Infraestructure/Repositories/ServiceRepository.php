<?php

namespace Src\Sales\Service\Infraestructure\Repositories;

use Exception;
use Src\Sales\Service\Domain\Entities\Service;
use Src\Sales\Service\Domain\Repositories\ServiceRepositoryInterface;
use Src\Sales\Service\Infraestructure\Mappers\ServiceMapper;
use Src\Sales\Shared\Application\Services\HttpClient;

class ServiceRepository implements ServiceRepositoryInterface
{
    //TODO: move to a settings the endoint
    const URL = 'http://localhost:8000/fake/service/%s';

    public function findById(string $id): Service
    {
        $url = sprintf(
            self::URL,
            $id
        );

        try {

            /*$response = HttpClient::client()
                ->get($url)
                ->getBody()
                ->getContents();
            */
            $faker = \Faker\Factory::create();
            $response = json_encode([
                'id' => $id,
                'code' => $faker->randomNumber(5),
                'name' => $faker->sentence(4),
                'unit' => $faker->word(),
                'description' => $faker->sentence(10),
            ]);

            return ServiceMapper::toEntity(json_decode($response));

        } catch (Exception $e) {
            throw new Exception('Error getting service info for: '.$id);
        }
    }

    public function findByIds(array $ids): array
    {
        $response = [];

        foreach ($ids as $id) {
            $response[] = $this->findById($id);
        }

        return $response;
    }
}

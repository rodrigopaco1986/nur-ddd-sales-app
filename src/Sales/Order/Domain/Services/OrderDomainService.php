<?php

namespace Src\Sales\Order\Domain\Services;

use DateTimeImmutable;
use Src\Sales\Order\Domain\Entities\Order;
use Src\Sales\Order\Domain\Entities\OrderItem;
use Src\Sales\Order\Domain\Exceptions\ServiceNotFoundException;
use Src\Sales\Order\Domain\Repositories\OrderRepositoryInterface;
use Src\Sales\Order\Domain\ValueObject\OrderStatus;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class OrderDomainService
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function create(string $patientId, Currency $currency, array $items, array $servicesInfo): ?Order
    {
        $order = new Order($patientId, new DateTimeImmutable, OrderStatus::CREATED(), $currency);

        foreach ($items as $item) {

            $serviceItem = collect($servicesInfo)->filter(function ($value) use ($item) {
                if ($value) {
                    return $item['service_id'] == $value->getId();
                }

                return false;
            })->first();

            if (! $serviceItem) {
                throw new ServiceNotFoundException($item['service_id']);
            }

            $orderItem = new OrderItem(
                $serviceItem->getId(),
                $serviceItem->getCode(),
                $serviceItem->getName(),
                $serviceItem->getUnit(),
                $item['quantity'],
                new Money($item['price'], $currency),
                new Money($item['discount'], $currency),
            );

            $order->addItem($orderItem);
        }

        $orderEntitySaved = $this->orderRepository->save($order);

        return $orderEntitySaved;

    }
}

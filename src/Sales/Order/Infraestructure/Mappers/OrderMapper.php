<?php

namespace Src\Sales\Order\Infraestructure\Mappers;

use Src\Sales\Order\Domain\Entities\Order as OrderEntity;
use Src\Sales\Order\Domain\ValueObject\OrderStatus;
use Src\Sales\Order\Infraestructure\Models\Order as EloquentOrder;
use Src\Sales\Shared\Domain\ValueObject\Currency;

class OrderMapper
{
    public static function toEntity(EloquentOrder $order): OrderEntity
    {
        $orderEntity = new OrderEntity(
            $order->patient_id,
            $order->order_date,
            new OrderStatus($order->status),
            new Currency($order->currency),
        );
        $orderEntity->setId($order->id);

        $items = $order->items()->get();

        foreach ($items as $item) {
            $orderEntity->addItem(OrderItemMapper::toEntity($item, $order));
        }

        return $orderEntity;
    }

    public static function toModel(OrderEntity $order): EloquentOrder
    {
        $eloquentOrderModel = (new EloquentOrder)
            ->fill([
                'patient_id' => $order->getPatientId(),
                'order_date' => $order->getOrderDate(),
                'status' => $order->getStatus(),
                'currency' => $order->getCurrency(),
                'total' => $order->getTotal(),
            ]);

        return $eloquentOrderModel;
    }
}

<?php

namespace Src\Sales\Order\Infraestructure\Mappers;

use Src\Sales\Order\Domain\Entities\Order as OrderEntity;
use Src\Sales\Order\Domain\Entities\OrderItem as OrderItemEntity;
use Src\Sales\Order\Infraestructure\Models\Order as EloquentOrder;
use Src\Sales\Order\Infraestructure\Models\OrderItem as EloquentOrderItem;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class OrderItemMapper
{
    public static function toEntity(EloquentOrderItem $orderItem, EloquentOrder $order): OrderItemEntity
    {
        $orderEntity = new OrderItemEntity(
            $orderItem->service_id,
            $orderItem->service_code,
            $orderItem->service_name,
            $orderItem->service_unit,
            $orderItem->quantity,
            new Money($orderItem->price, new Currency($order->currency)),
            new Money($orderItem->discount, new Currency($order->currency)),
        );

        $orderEntity->setId($orderItem->id);

        return $orderEntity;
    }

    public static function toModel(OrderItemEntity $orderItem, OrderEntity $order): EloquentOrderItem
    {
        $eloquentOrderItem = (new EloquentOrderItem)
            ->fill([
                'service_id' => $orderItem->getServiceId(),
                'service_code' => $orderItem->getServiceCode(),
                'service_name' => $orderItem->getServiceName(),
                'service_unit' => $orderItem->getServiceUnit(),
                'quantity' => $orderItem->getQuantity(),
                'price' => $orderItem->getPrice(),
                'discount' => $orderItem->getDiscount(),
                'subtotal' => $orderItem->getSubTotal(),
            ]);

        return $eloquentOrderItem;
    }
}

<?php

namespace Src\Sales\Invoice\Infraestructure\Mappers;

use Src\Sales\Invoice\Domain\Entities\InvoiceItem as InvoiceItemEntity;
use Src\Sales\Invoice\Infraestructure\Models\Invoice as EloquentInvoice;
use Src\Sales\Invoice\Infraestructure\Models\InvoiceItem as EloquentInvoiceItem;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class InvoiceItemMapper
{
    public static function toEntity(EloquentInvoiceItem $invoiceItem, EloquentInvoice $invoice): InvoiceItemEntity
    {
        $invoiceEntity = new InvoiceItemEntity(
            $invoiceItem->service_id,
            $invoiceItem->service_code,
            $invoiceItem->service_name,
            $invoiceItem->service_unit,
            $invoiceItem->quantity,
            new Money($invoiceItem->price, new Currency($invoice->currency)),
            new Money($invoiceItem->discount, new Currency($invoice->currency)),
        );

        $invoiceEntity->setId($invoiceItem->id);

        return $invoiceEntity;
    }

    public static function toModel(InvoiceItemEntity $invoiceItem): EloquentInvoiceItem
    {
        $eloquentInvoiceItem = (new EloquentInvoiceItem)
            ->fill([
                'service_id' => $invoiceItem->getServiceId(),
                'service_code' => $invoiceItem->getServiceCode(),
                'service_name' => $invoiceItem->getServiceName(),
                'service_unit' => $invoiceItem->getServiceUnit(),
                'quantity' => $invoiceItem->getQuantity(),
                'price' => $invoiceItem->getPrice(),
                'discount' => $invoiceItem->getDiscount(),
                'subtotal' => $invoiceItem->getSubTotal(),
            ]);

        return $eloquentInvoiceItem;
    }
}

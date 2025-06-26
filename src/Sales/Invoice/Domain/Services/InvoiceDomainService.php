<?php

namespace Src\Sales\Invoice\Domain\Services;

use DateTimeImmutable;
use Src\Sales\Invoice\Application\Services\CompanyService;
use Src\Sales\Invoice\Application\Services\OrderService;
use Src\Sales\Invoice\Application\Services\PatientService;
use Src\Sales\Invoice\Application\Services\ServiceService;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\Entities\InvoiceItem;
use Src\Sales\Invoice\Domain\Exceptions\InvoiceAlredyCreatedException;
use Src\Sales\Invoice\Domain\Exceptions\OrderNotFoundException;
use Src\Sales\Invoice\Domain\Exceptions\PatientNotFoundException;
use Src\Sales\Invoice\Domain\Exceptions\ServiceNotFoundException;
use Src\Sales\Invoice\Domain\Repositories\InvoiceRepositoryInterface;
use Src\Sales\Invoice\Domain\ValueObject\InvoiceStatus;
use Src\Sales\Order\Domain\Entities\OrderItem;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class InvoiceDomainService
{
    private InvoiceRepositoryInterface $invoiceRepository;

    private OrderService $orderService;

    private PatientService $patientService;

    private ServiceService $serviceService;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        OrderService $orderService,
        PatientService $patientService,
        ServiceService $serviceService
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderService = $orderService;
        $this->patientService = $patientService;
        $this->serviceService = $serviceService;
    }

    public function create(
        string $orderId,
    ): ?Invoice {

        $orderInfo = $this->orderService->getOrderInfo($orderId);

        if (! $orderInfo) {
            throw new OrderNotFoundException;
        }

        if ($this->invoiceRepository->hasActiveInvoice($orderInfo->getId())) {
            throw new InvoiceAlredyCreatedException;
        }

        // $customerId = $command->getCustomerId() ? $command->getCustomerId() : $orderInfo->getPatientId();
        $customerId = $orderInfo->getPatientId();
        $patientInfo = $this->patientService->getPatientInfo($customerId);
        $companyInfo = (new CompanyService)->getInfo();

        if (! $patientInfo) {
            throw new PatientNotFoundException;
        }

        $items = $orderInfo?->getItems() ?? [];
        $serviceIds = collect($items)->map(function (OrderItem $value) {
            return $value->getServiceId();
        })->toArray();

        $servicesInfo = $this->serviceService->getServicesInfo($serviceIds);
        $currency = new Currency($orderInfo->getCurrency());

        $invoice = new Invoice(
            $companyInfo->getNit(),
            $this->invoiceRepository->count() + 1,
            $companyInfo->getAuthorizationCode(),
            new DateTimeImmutable,
            $patientInfo->getId(),
            $patientInfo->getCode(),
            $patientInfo->getName(),
            $patientInfo->getEmail(),
            $patientInfo->getNit(),
            InvoiceStatus::CREATED(),
            $currency,
            $orderInfo->getId(),
        );

        foreach ($orderInfo->getItems() as $item) {
            $serviceItem = collect($servicesInfo)->filter(function ($value) use ($item) {
                if ($value) {
                    return $item->getServiceId() == $value->getId();
                }

                return false;
            })->first();

            if (! $serviceItem) {
                throw new ServiceNotFoundException($item->getId());
            }

            $invoiceItem = new InvoiceItem(
                // $serviceItem->getId(),
                $item->getServiceId(),
                $serviceItem->getCode(),
                $serviceItem->getName(),
                $serviceItem->getUnit(),
                $item->getQuantity(),
                new Money($item->getPrice(), $currency),
                new Money($item->getDiscount(), $currency),
            );
            $invoice->addItem($invoiceItem);
        }

        $invoiceEntitySaved = $this->invoiceRepository->save($invoice);

        return $invoiceEntitySaved;

    }

    public function getInvoiceInfo() {}
}

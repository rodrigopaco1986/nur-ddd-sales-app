<?php

namespace Src\Sales\Invoice\Application\Commands\Handlers;

use Src\Sales\Invoice\Application\Commands\CreateInvoiceCommand;
use Src\Sales\Invoice\Application\Services\OrderService;
use Src\Sales\Invoice\Application\Services\PatientService;
use Src\Sales\Invoice\Application\Services\ServiceService;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\Events\InvoiceCreatedEvent;
use Src\Sales\Invoice\Domain\Repositories\InvoiceRepositoryInterface;
use Src\Sales\Invoice\Domain\Services\InvoiceDomainService;

final class CreateInvoiceHandler
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

    public function handle(CreateInvoiceCommand $command): ?Invoice
    {
        $orderId = $command->getOrderId();

        $invoiceEntitySaved = (new InvoiceDomainService(
            $this->invoiceRepository,
            $this->orderService,
            $this->patientService,
            $this->serviceService,
        ))->create(
            $command->getOrderId()
        );

        if ($invoiceEntitySaved) {
            $orderInfo = $this->orderService->getOrderInfo($orderId);
            $customerId = $orderInfo->getPatientId();
            $patientInfo = $this->patientService->getPatientInfo($customerId);
            InvoiceCreatedEvent::dispatch($invoiceEntitySaved, $patientInfo);
        }

        return $invoiceEntitySaved;
    }
}

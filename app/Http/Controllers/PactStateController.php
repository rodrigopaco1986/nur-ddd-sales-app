<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Sales\Invoice\Application\Commands\CreateInvoiceCommand;
use Src\Sales\Invoice\Application\Commands\Handlers\CreateInvoiceHandler;
use Src\Sales\Invoice\Application\Services\OrderService;
use Src\Sales\Invoice\Application\Services\PatientService;
use Src\Sales\Invoice\Application\Services\ServiceService as InvoiceServiceService;
use Src\Sales\Invoice\Infraestructure\Models\Invoice;
use Src\Sales\Invoice\Infraestructure\Repositories\InvoiceRepository;
use Src\Sales\Order\Application\Commands\CreateOrderCommand;
use Src\Sales\Order\Application\Commands\Handlers\CreateOrderHandler;
use Src\Sales\Order\Application\Services\ServiceService;
use Src\Sales\Order\Infraestructure\Models\Order;
use Src\Sales\Order\Infraestructure\Repositories\OrderRepository;
use Src\Sales\Patient\Infraestructure\Repositories\PatientRepository;
use Src\Sales\Service\Infraestructure\Repositories\ServiceRepository;

class PactStateController extends Controller
{
    /**
     * Set up the provider state as requested by Pact.
     */
    public function __invoke(Request $request)
    {
        $state = $request->input('state');
        $params = $request->input('params', []);

        switch ($state) {
            case 'An order exists':
                return $this->createOrder($params);

            case 'An order exists with the invoice generated':
                return $this->createOrderWithInvoice($params);

            default:
                return response()->json(['result' => "No state setup needed for state: $state"], 200);
        }
    }

    private function _doCreateOrder($params)
    {
        $orderId = $params['order_id'] ?? null;
        $patientId = $params['patient_id'] ?? null;
        $generateInvoice = $params['generate_invoice'] ?? 0;
        $paymentInstallments = $params['payment_installments'] ?? 2;
        $uuidService1 = $params['uuid_service_1'] ?? null;
        $uuidService2 = $params['uuid_service_2'] ?? null;

        $items = [
            [
                'service_id' => $uuidService1,
                'quantity' => 1,
                'price' => 100,
                'discount' => 0,
            ],
            [
                'service_id' => $uuidService2,
                'quantity' => 1,
                'price' => 1500,
                'discount' => 100,
            ],
        ];

        if ($orderId) {
            // Delete the order if it was created before as a test
            Order::destroy($orderId);
        } else {
            return false;
        }

        $commandOrder = new CreateOrderCommand($patientId, $items, $paymentInstallments, $generateInvoice);
        $commandOrderHandlerResponse = (new CreateOrderHandler(new OrderRepository, new ServiceService(new ServiceRepository)))
            ->handle($commandOrder);

        // HACK for pact: Update created order id to match the passed one
        $orderIdCreated = $commandOrderHandlerResponse->getId();
        DB::table('orders')->where('id', $orderIdCreated)->update(['id' => $orderId]);

        return $orderId;
    }

    private function createOrder($params)
    {
        $orderId = $this->_doCreateOrder($params);

        if ($orderId) {
            return response()->json(['result' => "Order with id $orderId created"], 200);
        } else {
            return response()->json(['error' => 'Missing orderId'], 400);
        }
    }

    private function createOrderWithInvoice($params)
    {
        $orderId = $params['order_id'] ?? false;
        $patientId = $params['patient_id'] ?? false;
        $invoiceId = $params['invoice_id'] ?? false;
        $orderIdCreated = $this->_doCreateOrder($params);

        // HACK for pact: Update created order id to match the passed one
        DB::table('orders')->where('id', $orderIdCreated)->update(['id' => $orderId]);

        // Create the invoice
        $commandInvoice = new CreateInvoiceCommand($orderId, $patientId);
        $commandInvoiceHandlerResponse = (new CreateInvoiceHandler(
            new InvoiceRepository,
            new OrderService(new OrderRepository),
            new PatientService(new PatientRepository),
            new InvoiceServiceService(new ServiceRepository),
        ))
            ->handle($commandInvoice);

        // HACK for pact: Update created invoice id to match the passed one
        $invoiceIdCreated = $commandInvoiceHandlerResponse->getId();
        DB::table('invoices')->where('id', $invoiceIdCreated)->update(['id' => $invoiceId]);

        if ($invoiceIdCreated) {
            return response()->json(['result' => "Invoice with id $invoiceIdCreated created"], 200);
        } else {
            return response()->json(['error' => 'Missing invoiceId'], 400);
        }
    }
}

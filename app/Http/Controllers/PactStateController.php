<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request; // Make sure you have an Order model
use Illuminate\Support\Facades\Log;
use Src\Sales\Order\Application\Commands\CreateOrderCommand;
use Src\Sales\Order\Application\Commands\Handlers\CreateOrderHandler;
use Src\Sales\Order\Application\Services\ServiceService;
use Src\Sales\Order\Infraestructure\Repositories\OrderRepository;
use Src\Sales\Order\Presentation\Resources\OrderResource;
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
                Log::info('State change request received', ['state' => $state, 'params' => $params]);
                // Retrieve the provided orderId
                $orderId = $params['order_id'] ?? null;
                $patientId = $params['patient_id'] ?? null;
                $generateInvoice = 1;
                $paymentInstallments = 2;
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

                if (! $orderId) {
                    return response()->json(['error' => 'Missing orderId'], 400);
                }

                $commandOrder = new CreateOrderCommand($patientId, $items, $paymentInstallments, $generateInvoice);
                $commandOrderHandlerResponse = (new CreateOrderHandler(new OrderRepository, new ServiceService(new ServiceRepository)))
                    ->handle($commandOrder);

                Log::info('State change request received', ['reponse' => new OrderResource($commandOrderHandlerResponse)]);

                return response()->json(['result' => "Order with id $orderId created"], 200);

                // You can add more cases for other states if needed.
            default:
                return response()->json(['result' => "No state setup needed for state: $state"], 200);
        }
    }
}

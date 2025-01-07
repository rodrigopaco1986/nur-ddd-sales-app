<?php

namespace Src\Sales\Payment\Presentation\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Src\Sales\Payment\Application\Queries\GetPaymentsByOrderQuery;
use Src\Sales\Payment\Application\Queries\Handlers\GetPaymentsByOrderHandler;
use Src\Sales\Payment\Infraestructure\Repositories\PaymentScheduleRepository;
use Src\Sales\Payment\Presentation\Resources\PaymentScheduleCollection;

class ViewPaymentsByOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $orderId = $request->route('orderId');

        $queryPayment = new GetPaymentsByOrderQuery($orderId);
        $queryPaymentHandlerResponse = (new GetPaymentsByOrderHandler(new PaymentScheduleRepository))
            ->handle($queryPayment);

        if ($queryPaymentHandlerResponse) {
            return new PaymentScheduleCollection($queryPaymentHandlerResponse);
        }

        throw new HttpResponseException(response()->json([
            'errors' => 'Payment Scheduled not found',
        ], 404));
    }
}

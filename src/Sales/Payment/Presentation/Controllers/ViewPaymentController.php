<?php

namespace Src\Sales\Payment\Presentation\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Src\Sales\Payment\Application\Queries\GetPaymentQuery;
use Src\Sales\Payment\Application\Queries\Handlers\GetPaymentHandler;
use Src\Sales\Payment\Infraestructure\Repositories\PaymentScheduleRepository;
use Src\Sales\Payment\Presentation\Resources\PaymentScheduleResource;

class ViewPaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $id = $request->route('id');

        $queryPayment = new GetPaymentQuery($id);
        $queryPaymentHandlerResponse = (new GetPaymentHandler(new PaymentScheduleRepository))
            ->handle($queryPayment);

        if ($queryPaymentHandlerResponse) {
            return new PaymentScheduleResource($queryPaymentHandlerResponse);
        }

        throw new HttpResponseException(response()->json([
            'errors' => 'Payment Scheduled not found',
        ], 404));
    }
}

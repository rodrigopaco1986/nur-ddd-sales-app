<?php

namespace Src\Sales\Payment\Presentation\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Src\Sales\Payment\Application\Commands\CreatePaymentRecordCommand;
use Src\Sales\Payment\Application\Commands\Handlers\CreatePaymentRecordCommandHandler;
use Src\Sales\Payment\Infraestructure\Repositories\PaymentRecordRepository;
use Src\Sales\Payment\Infraestructure\Repositories\PaymentScheduleRepository;
use Src\Sales\Payment\Presentation\Requests\MakePaymentRequest;
use Src\Sales\Payment\Presentation\Resources\PaymentRecordResource;

class MakePaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MakePaymentRequest $request)
    {
        $validated = $request->validated();

        $paymentScheduleId = $validated['payment_schedule_id'];

        $commandRecord = new CreatePaymentRecordCommand($paymentScheduleId);
        $commandRecordHandlerResponse = (new CreatePaymentRecordCommandHandler(new PaymentRecordRepository, new PaymentScheduleRepository))
            ->handle($commandRecord);

        if ($commandRecordHandlerResponse) {
            return new PaymentRecordResource($commandRecordHandlerResponse);
        }

        throw new HttpResponseException(response()->json([
            'errors' => ['order' => 'Order not saved'],
        ], 422));
    }
}

<?php

namespace Src\Sales\Payment\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;

class PaymentScheduleResource extends JsonResource
{
    public function __construct(PaymentSchedule $paymentSchedule)
    {
        $this->resource = $paymentSchedule;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment = $this->resource;

        return [
            'payment' => [
                'id' => $payment->getId(),
                'number' => $payment->getNumber(),
                'amount' => $payment->getAmount(),
                'due_date' => $payment->getDueDate(),
                'status' => $payment->getStatus(),
                'currency' => $payment->getCurrency(),
                'order_id' => $payment->getOrderId(),
                'paymentRecord' => $payment->getPaymentRecord() ? new PaymentRecordResource($payment->getPaymentRecord()) : null,
            ],
        ];
    }
}

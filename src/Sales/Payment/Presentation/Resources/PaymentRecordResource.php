<?php

namespace Src\Sales\Payment\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;

class PaymentRecordResource extends JsonResource
{
    public function __construct(PaymentRecord $paymentRecord)
    {
        $this->resource = $paymentRecord;
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
                'amount' => $payment->getAmount(),
                'payed_date' => $payment->getPayedDate(),
                'first_name' => $payment->getFirstName(),
                'last_name' => $payment->getLastName(),
                'dni' => $payment->getDni(),
                'order_id' => $payment->getOrderId(),
                'payments_schedule_id' => $payment->getPaymentScheduleId(),
            ],
        ];
    }
}

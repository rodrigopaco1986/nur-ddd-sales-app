<?php

namespace Src\Sales\Payment\Infraestructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentSchedule extends Model
{
    use HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments_schedule';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'amount',
        'due_date',
        'status',
        'currency',
        'order_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'immutable_datetime',
        ];
    }

    /**
     * Get the payment record associated with the payment schedule.
     */
    public function record(): HasOne
    {
        return $this->hasOne(PaymentRecord::class, 'payments_schedule_id');
    }
}

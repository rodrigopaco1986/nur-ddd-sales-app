<?php

namespace Src\Sales\Payment\Infraestructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRecord extends Model
{
    use HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'currency',
        'payed_date',
        'status',
        'first_name',
        'last_name',
        'dni',
        'order_id',
        'payments_schedule_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payed_date' => 'immutable_datetime',
        ];
    }

    /**
     * Get the payment schedule associated with the payment record.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payments_schedule_id');
    }
}

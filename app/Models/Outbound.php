<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outbound extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'picking_id',
        'user_id',
        'warehouse',
        'order_number',
        'order_name',
        'carrier',
        'boxes',
        'status',
        'requested_quantity',
        'dispatched_quantity',
        'guide',
        'truck_status',
        'client',
        'length',
        'width',
        'height',
        'volume',
        'weight',
        'order_date',
        'delivered_date',
        'packing_date',
        'dispatch_date',
        'shipping_date',
    ];

    /**
     * @return BelongsTo
     */
    public function picking(): BelongsTo
    {
        return $this->belongsTo(Picking::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
}

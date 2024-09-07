<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Move extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'picking_id',
        'product_uom_qty',
        'value_unit',
    ];

    /**
     * @return BelongsTo
     */
    public function picking(): BelongsTo
    {
        return $this->belongsTo(Picking::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

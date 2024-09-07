<?php

namespace App\Models;

use App\Builders\Quant\QuantBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockQuant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'location_id',
        'product_id',
        'quantity'
    ];


    /**
     * @param $query
     * @return QuantBuilder
     */
    public function newEloquentBuilder($query): QuantBuilder
    {
        return new QuantBuilder($query);
    }

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

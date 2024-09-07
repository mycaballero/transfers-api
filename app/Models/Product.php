<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'default_code',
        'name',
        'qty_available',
        'category',
        'standard_price',
        'uom',

    ];

    /**
     * @return HasMany
     */
    public function moveLines(): HasMany
    {
        return $this->hasMany(Move::class, 'product_id','id');
    }

    public function quants(): HasMany
    {
        return $this->hasMany(StockQuant::class, 'product_id','id');
    }
}

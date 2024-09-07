<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'partner_invoice_id',
        'partner_shipping_id',
        'name',
        'total_cost',
        'freight',
        'carrier',
        'packed',
    ];

    /**
     * @return BelongsTo
     */
    public function partnerInvoice(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_invoice_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function partnerShipping(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_shipping_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function picking(): HasMany
    {
        return $this->hasMany(Picking::class, 'sale_id', 'id');
    }

}

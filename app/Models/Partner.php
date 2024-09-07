<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'city_id',
        'credit',
        'credit_limit',
        'country_id',
        'display_name',
        'email',
        'id',
        'mobile',
        'parenthood_id',
        'phone',
        'state_id',
        'use_partner_credit_limit',
        'vat',
    ];

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function parenthood(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'parenthood_id', 'id');
    }
}

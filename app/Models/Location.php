<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'warehouse_id',
        'city_id',
        'urban_id',
        'name',
        'active'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(LocationsHasPartner::class);
    }

    /**
     * @return BelongsTo
     */
    public function urban(): BelongsTo
    {
        return $this->belongsTo(Partner::class,'urban_id','id');
    }
}

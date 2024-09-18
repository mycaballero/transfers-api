<?php

namespace App\Models;

use App\Builders\Picking\PickingBuilder;
use App\Enums\Picking\PickingEventEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Picking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'sale_id',
        'location_id',
        'event',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'eventRanking'
    ];

    /**
     * @return int
     */
    public function getEventRankingAttribute(): int
    {
        if (isset($this->event)) {
            return PickingEventEnum::from($this->event)->ranking();
        }
        return 4;
    }

    /**
     * @param $query
     * @return PickingBuilder
     */
    public function newEloquentBuilder($query): PickingBuilder
    {
        return new PickingBuilder($query);
    }

    /**
     * @return BelongsTo
     */
    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class,'sale_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return HasOne
     */
    public function outbound(): HasOne
    {
        return $this->hasOne(Outbound::class);
    }

    /**
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'picking_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(Move::class, 'picking_id', 'id');
    }
}

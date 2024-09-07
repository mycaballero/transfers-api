<?php


namespace App\Builders\Picking;

use Illuminate\Database\Eloquent\Builder;

class PickingBuilder extends Builder
{
    /**
     * @param string|null $name
     * @return self
     */
    public function whereName(?string $name): self
    {
        return $this->when(isset($name), function ($q) use ($name) {
            $q->where(function ($subquery) use ($name) {
                $subquery->where('name', 'LIKE', '%' . $name . '%')
                    ->orWhereHas('saleOrder', function ($q2) use ($name) {
                        $q2->where('sale_order.name', 'LIKE', '%' . $name . '%');
                    });
            });
        });
    }
}

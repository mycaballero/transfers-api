<?php


namespace App\Builders\Quant;

use Illuminate\Database\Eloquent\Builder;

class QuantBuilder extends Builder
{
    /**
     * @param string|null $name
     * @return self
     */
    public function whereName(?string $name): self
    {
        return $this->when(isset($name), function ($q) use ($name) {
            $q->where(function ($subquery) use ($name) {
                $subquery->where('name', 'LIKE', '%' . $name . '%');
            });
        });
    }
    public function whereProductId(?int $id): self
    {
        return $this->when(isset($id), function ($q) use ($id) {
            $q->where(function ($subquery) use ($id) {
                $subquery->where('product_id', '=', $id );
            });
        });
    }
}

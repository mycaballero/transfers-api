<?php

namespace App\Http\Resources\Picking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PickingResource extends JsonResource
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'ranking' => $this->eventRanking,
            'id' => $this->id,
            'name' => $this->name,
            'saleOrder' => $this->saleOrder?->name,
            'origin' => $this->location?->name,
            'destination' => $this->saleOrder?->partnerShipping?->city->name,
            'invoice' => $this->saleOrder?->partnerInvoice?->display_name,
            'carrier' => $this->saleOrder?->x_studio_transportadora === "0"? "No" : $this->saleOrder?->x_studio_transportadora,
            'event' => $this->event,
            'freight' => $this->saleOrder?->x_studio_flete === "0"? "No" : $this->saleOrder?->x_studio_flete,
            'multiple' => $this->saleOrder ? count($this->saleOrder?->picking) > 1 : false,
            'outbound' => $this->outbound,
            'notes' => $this->notes->count()
        ];
    }
}

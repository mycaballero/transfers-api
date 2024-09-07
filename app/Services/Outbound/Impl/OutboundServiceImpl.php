<?php

namespace App\Services\Outbound\Impl;

use App\DTO\Outbound\CreateData;
use App\Enums\Picking\CarrierEnum;
use App\Enums\Picking\FreightEnum;
use App\Enums\Picking\PickingEventEnum;
use App\Mail\DeliveryNoteMailable;
use App\Models\Location;
use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Services\CityService\CityService;
use App\Services\Outbound\OutboundService;
use App\Services\Pdf\PdfService;
use App\Services\PickingService\PickingService;
use App\Models\Outbound;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OutboundServiceImpl implements OutboundService
{

    /**
     * @param PickingService $pickingService
     * @param CityService $cityService
     * @param GeneralParametersRepository $generalParametersRepository
     * @param PdfService $pdfService
     */
    public function __construct(
        protected PickingService                $pickingService,
        protected CityService                   $cityService,
        protected GeneralParametersRepository   $generalParametersRepository,
        protected PdfService                    $pdfService,
    )
    {
    }

    /**
     * @param CreateData $payload
     * @return Outbound|array
     */
    public function createOrUpdate(CreateData $payload): Outbound|array
    {
        $user = Auth::user();
        try {
            $data = [
                'id' => $payload->id ?? null,
                'picking_id' => $payload->picking_id?? null,
                'warehouse' => $payload->warehouse?? null,
                'order_number' => $payload->n_order?? null,
                'order_name' => $payload->order_name?? null,
                'carrier' => $payload->carrier?? null,
                'boxes' => $payload->cajas?? null,
                'status' => $payload->sts_orden?? null,
                'requested_quantity' => $payload->q_solicitadas?? null,
                'dispatched_quantity' => $payload->q_despachada?? null,
                'guide' => $payload->guia?? null,
                'truck_status' => $payload->estada_camion?? null,
                'client' => config('blu.CLIENTS.HEAVY.name'),
                'length' => $payload->largo?? null,
                'width' => $payload->ancho?? null,
                'height' => $payload->alto?? null,
                'volume' => $payload->volumen?? null,
                'weight' => $payload->peso?? null,
                'order_date' => $payload->fecha_orden?? null,
                'packing_date' => $payload->fecha_empaque?? null,
                'dispatch_date' => $payload->fecha_despacho?? null,
                'delivered_date' => $payload->delivered_date?? null,
                'shipping_date' => $payload->shipping_date?? null,
            ];
            if (!$payload->picking_id) {
                $picking = $this->pickingService->getByName($payload->order_name);
                $data['picking_id'] = $picking?->id;
            }
            if ($payload->id) {
                $data = array_filter($data, function ($value) {
                    return $value !== null;
                });
            }
            if ($user) {
                $data['user_id'] = $user->id;
            }
            $outbound = Outbound::updateOrCreate(['id' => $payload->id], $data);
            if ($outbound->status === PickingEventEnum::DELIVERED->value || $outbound->status === PickingEventEnum::CANCELED->value)
            {
                $data = [
                    'id' => $outbound->picking_id,
                    'event' => $outbound->status
                ];
                $this->pickingService->updateEvent($data);
            }
            return $outbound;
        } catch (\Error $e) {
            Log::error($e);
            print_r($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getByPickingId(int $id): Model
    {
        return Outbound::query()->where('picking_id', '=', $id)->first();
    }

    /**
     * @param array $filters
     * @return Collection|array
     */
    public function getByLimits(array $filters = []): Collection|array
    {
        return Outbound::query()
            ->whereNotNull(['guide'])
            ->whereIn('status', [
                PickingEventEnum::DELIVERED->value,
                PickingEventEnum::DISPATCHED->value,
            ])
            ->whereNull(['delivered_date'])
            ->when(isset($filters['carrier']), function ($q) use ($filters) {
               $q->where('carrier', 'like', '%'.$filters['carrier'].'%');
            })->get();
    }

    /**
     * @throws Exception
     */
    public function initializeOutbounds(array|Collection $models): Collection|array
    {
        foreach ($models['pickings'] as $picking) {
            $warehouse = $this->getWarehouse($picking['location_id']);
            $carrier = $this->getCarrier($models['saleOrders'][0], $picking['event']);
            $shippingDate = $this->getDateWithConditions();
            $city = $this->cityService->getById($models['partners'][0]['city_id']);
            dd($city);
            try {
                if ($picking['event'] != PickingEventEnum::CLAIM_IN_WAREHOUSE->value) {
                    $this->pickingService->updateEvent([
                        'id' => $picking['id'],
                        'event' => PickingEventEnum::DELIVERED_TO_WAREHOUSE->value
                    ]);
                }
                $payload = [
                    "order_name" => $picking['name'],
                    "warehouse" => $warehouse['name'],
                    "carrier" => $carrier,
                    "shipping_date" => $shippingDate,
                ];
                $outbound = $this->createOrUpdate(CreateData::from($payload));
                $this->sendEmail([
                    'mailers' => $warehouse->partners->pluck('partner.email')->toArray(),
                    'claim' => $picking['event'] === PickingEventEnum::CLAIM_IN_WAREHOUSE->value
                        ? $warehouse['urban'] : [],
                    'order' => $picking['name'],
                    'carrier' => $carrier,
                    'packed' => $models['saleOrders'][0]['packed'],
                    'freight' => $models['saleOrders'][0]['freight'],
                    "shipping_date" => $shippingDate,
                    'destinationName' => $models['saleOrders'][0]['partner_invoice']['display_name'],
                    'destinationId' => $models['saleOrders'][0]['partner_invoice']['vat'],
                    'destinationCountry' => 'COLOMBIA',
                    'destinationCity' => $city->name,
                    'destinationState' => !empty($city->state)?$city->state?->name:'',
                    'destinationDane' => $city->code,
                    'destinationAddress' => $models['partners'][0]['address'],
                    'destinationPhone' => $models['partners'][0]['phone'],
                    'saleNumber' => $models['saleOrders'][0]['name'],
                    'order_date' => DateTime::createFromFormat('Y-m-d H:i:s',$picking['created_at'])
                        ->sub(new DateInterval('PT5H'))
                        ->format('Y-m-d H:i:s'),
                    'products' => array_map(function ($move) use ($models) {
                        $product = array_filter($models['product'], function ($product) use ($move) {
                            return $move['product_id'] === $product['id'];
                        });
                        reset($product);
                        return ['code' => $product['default_code'],
                            'product' => $product['name'],
                            'quantity' => $move['product_uom_qty']
                        ];
                    }, $models['moves'])
                ]);

            } catch (\Exception $e) {
                dd($e);
                $outbound = [];
                Log::error($e);
            }
        }
        dd($outbound);
        return $outbound;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getDateWithConditions(): string
    {
        $currentDateTime = new DateTime('now', new DateTimeZone('America/Bogota'));
        $dayOfWeek = (int) $currentDateTime->format('w');
        $hour = (int) $currentDateTime->format('H');
        $minutes = (int) $currentDateTime->format('i');
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            if ($hour < 13 || ($hour == 13 && $minutes < 30)) {
                $currentDateTime->modify('+3 hours');
            } else {
                $currentDateTime->modify('tomorrow');
                $currentDateTime->setTime(9, 0);
            }
        } elseif ($dayOfWeek == 6) {
            if ($hour >= 10) {
                $currentDateTime->modify('next monday');
                $currentDateTime->setTime(9, 0);
            }
        } else {
            $currentDateTime->modify('next monday');
            $currentDateTime->setTime(9, 0);
        }
        return $currentDateTime->format('Y-m-d H:i:s');
    }

    /**
     * @param $saleOrder
     * @param $event
     * @return string
     */
    private function getCarrier($saleOrder, $event): string {
        $partners = array_map('intval',$this->generalParametersRepository->getValueByName('partners'));
        if (in_array($saleOrder['partner_shipping_id'], $partners)
            || $event == PickingEventEnum::CLAIM_IN_WAREHOUSE->value){
            return CarrierEnum::HEAVY->description();
        } else {
            return strtoupper($saleOrder['carrier']);
        }
    }

    /**
     * @param int $locationId
     * @return Location|array
     */
    public function getWarehouse(int $locationId): Location|array
    {
        return Location::query()->with(['partners.partner', 'urban'])->find($locationId);
    }

    /**
     * @param array $models
     * @return void
     */
    private function sendEmail(array $models): void
    {
        $pdf = $this->pdfService->createDeliveryNotePdf($models);
        dd($models['mailers']);
        Mail::to($models['mailers'])->send(new DeliveryNoteMailable($models, $pdf->output()));
    }
}

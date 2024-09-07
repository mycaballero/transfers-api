<?php

namespace App\DTO\Outbound;

use Spatie\LaravelData\Data;

class CreateData extends Data
{
    /**
     * @param int|null $id
     * @param int|null $picking_id
     * @param int|null $user_id
     * @param string|null $warehouse
     * @param string|null $n_order
     * @param string|null $order_name
     * @param string|null $carrier
     * @param string|null $cajas
     * @param string|null $sts_orden
     * @param int|null $q_solicitadas
     * @param int|null $q_despachada
     * @param string|null $guia
     * @param string|null $estada_camion
     * @param int|null $largo
     * @param int|null $ancho
     * @param int|null $alto
     * @param int|null $volumen
     * @param int|null $peso
     * @param string|null $fecha_orden
     * @param string|null $fecha_empaque
     * @param string|null $fecha_despacho
     * @param string|null $delivered_date
     * @param string|null $shipping_date
     */
    public function __construct(
        public ?int     $id = null,
        public ?int     $picking_id = null,
        public ?int     $user_id = null,
        public ?string  $warehouse = null,
        public ?string  $n_order = null,
        public ?string  $order_name = null,
        public ?string  $carrier = null,
        public ?string  $cajas = null,
        public ?string  $sts_orden = null,
        public ?int     $q_solicitadas = null,
        public ?int     $q_despachada = null,
        public ?string  $guia = null,
        public ?string  $estada_camion = null,
        public ?int     $largo = null,
        public ?int     $ancho = null,
        public ?int     $alto = null,
        public ?int     $volumen = null,
        public ?int     $peso = null,
        public ?string  $fecha_orden = null,
        public ?string  $fecha_empaque = null,
        public ?string  $fecha_despacho = null,
        public ?string  $delivered_date = null,
        public ?string  $shipping_date = null,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'id' => 'int',
            'picking_id' => 'int',
            'q_solicitadas' => 'int',
            'q_despachada' => 'int',
            'largo' => 'float',
            'ancho' => 'float',
            'alto' => 'float',
            'volumen' => 'float',
            'peso' => 'float',
        ];
    }
}

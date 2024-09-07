<?php

namespace App\Services\TccService\Impl;

use App\Services\TccService\TccService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TccServiceImpl implements TccService
{
    /**
     * @param $origin
     * @param $destination
     * @param $value
     * @return int
     * @throws GuzzleException
     */
    public function settle($origin, $destination, $value): int
    {
        $client = new Client();

        $header = [
            'Accept' => '*/*',
            'Content-Type' => 'application/json',
        ];

        $data = [
            "alto" => 0.0,
            "ancho" => 0.0,
            "largo" => 0.0,
            "ciudadOrigen" => $origin . "000",
            "ciudadDestino" => $destination . "000",
            "peso" => 2.0,
            "pesoVolumen" => 2.0,
            "valorDeclarado" => $value,
            "volumen" => 0.0,
            "idCliente" => "",
            "idCuenta" => "",
            "tipoTransporte" => 1,
            "tipoServicio" => 1,
            "unidadNegocio" => 1,
            "llevaABodega" => false,
            "recogeEnBodega" => false,
            "recogeEnPls" => false,
            "despachoPuntoDeVenta" => false,
            "entregaEnAgencia" => false,
            "recogeEnAgencia" => false,
            "empleado" => false,
            "numeroBoomerangsDigitales" => 0,
            "numeroBoomerangsFisicos" => 0,
            "numeroBoomerangsFisicosYDigitales" => 0,
            "numeroUnidadesDardo" => 0,
            "numeroUnidadesPaquete" => 1,
            "liquidarPorRemesa" => false,
            "mostrarDetalleUnidad" => false
        ];
        return json_decode($client->post(
            config('app_url.TCC_SERVICE'),
            [
                'headers' => $header,
                'json' => $data
            ])->getBody(), true)['valorTotal'];
    }
}

<?php

namespace App\Enums\User;

enum RoleEnum: string
{
    case COMERCIAL = 'Comercial';
    case SUPER_ADMINISTRADOR = 'Super administrador';
    case VENTAS = 'Ventas';

    /**
     * @return array
     */
    public function permissions(): array
    {
        return match ($this) {
            self::COMERCIAL => [
                PermissionEnum::VER_LOGISTICA->value,
                PermissionEnum::VER_ADMINISTRACION->value,
                PermissionEnum::EDITAR_TRANSFERENCIAS_SALIENTES->value
            ],
            self::SUPER_ADMINISTRADOR => [
                PermissionEnum::SUPER_ADMIN->value,
            ],
            self::VENTAS => [
                PermissionEnum::VER_LOGISTICA->value,
            ],
        };
    }
}

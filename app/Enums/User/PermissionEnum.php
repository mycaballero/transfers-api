<?php

namespace App\Enums\User;

use App\Enums\EnumInterface;

enum PermissionEnum: string implements EnumInterface
{
    case SUPER_ADMIN = 'ADMIN';
    case VER_LOGISTICA = 'VEREP';
    case VER_ADMINISTRACION = 'VADM';
    case VER_TARIFAS = 'VTFS';
    case ELIMINAR_TARIFAS = 'ETFS';
    case EDITAR_TRANSFERENCIAS_SALIENTES = 'EDTS';

    /**
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Todos los permisos',
            self::VER_LOGISTICA => 'Ver logística',
            self::VER_ADMINISTRACION => 'Ver administración',
            self::VER_TARIFAS => 'Ver tarifas',
            self::ELIMINAR_TARIFAS => 'Eliminar tarifas',
            self::EDITAR_TRANSFERENCIAS_SALIENTES => 'Editar transferencias salientes'
        };
    }

    /**
     * @return PermissionEnum[]
     */
    public static function getPermissions(): array
    {
        return [
            self::SUPER_ADMIN,
            self::VER_LOGISTICA,
            self::VER_ADMINISTRACION,
            self::VER_TARIFAS,
            self::ELIMINAR_TARIFAS,
            self::EDITAR_TRANSFERENCIAS_SALIENTES
        ];
    }
}

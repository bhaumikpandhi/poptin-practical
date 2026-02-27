<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case User  = 'user';

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get all role values as a plain array.
     * Useful for validation rules: Rule::in(Role::values())
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all role names as a plain array.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Human-readable label for display in UI.
     */
    public function label(): string
    {
        return match ($this) {
            RoleEnum::Admin => 'Admin',
            RoleEnum::User  => 'User',
        };
    }
}

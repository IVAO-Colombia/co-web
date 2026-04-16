<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @see Database\Seeders\SpatieRolesAndPermissionsSeeder
 */
enum Permission: string
{
    case STAFF_ACCESS = 'staff_access';
    case VIEW_EVENTS = 'view_events';
    case CREATE_EVENTS = 'create_events';
    case UPDATE_EVENTS = 'update_events';
    case DELETE_EVENTS = 'delete_events';

    /**
     * Permissions that will be directly assigned to a user.
     * These permissions will be assigned through the UI.
     *
     * @return array<Permission>
     */
    public static function directlyAssignable(): array
    {
        return [
        ];
    }

    /**
     * @return array<Permission>
     */
    public static function eventsPermissions(): array
    {
        return [
            self::VIEW_EVENTS,
            self::CREATE_EVENTS,
            self::UPDATE_EVENTS,
            self::DELETE_EVENTS,
        ];
    }
}

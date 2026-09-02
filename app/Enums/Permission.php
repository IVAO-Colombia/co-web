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
    case GENERATE_EVENT_IMAGES = 'generate_event_images';
    case VIEW_TRAINING_REQUESTS = 'view_training_requests';
    case UPDATE_TRAINING_REQUESTS = 'update_training_requests';
    case ASSIGN_TRAINING_REQUESTS = 'assign_training_requests';
    case EDIT_TRAINING_NOTES = 'edit_training_notes';
    case BE_ASSIGNED_TO_TRAININGS = 'be_assigned_to_trainings';
    case CANCEL_PILOT_SLOT = 'cancel_pilot_slot';
    case VIEW_USERS = 'view_users';
    case MANAGE_USER_ROLES = 'manage_user_roles';

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
}

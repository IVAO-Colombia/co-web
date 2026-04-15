<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @see Database\Seeders\SpatieRolesAndPermissionsSeeder
 */
enum Role: string
{
    case DIR = 'director';
    case ADIR = 'assistant_director';
    case FOC = 'flight_operations_coordinator';
    case FOAC = 'flight_operations_assistant_coordinator';
    case AOC = 'atc_operations_coordinator';
    case AOAC = 'atc_operations_assistant_coordinator';
    case TC = 'training_coordinator';
    case TAC = 'training_assistant_coordinator';
    case TA = 'division_training_advisor';
    case T0 = 'division_trainer';
    case MC = 'membership_coordinator';
    case EC = 'event_coordinator';
    case EAC = 'event_assistant_coordinator';
    case EA = 'division_event_advisor';
    case PRC = 'public_relations_coordinator';
    case PRAC = 'public_relations_assistant_coordinator';
    case PRA = 'public_relations_advisor';
    case WM = 'webmaster';
    case AWM = 'assistant_webmaster';
    case WMA = 'webmaster_advisor';

    public function label(): string
    {
        return match ($this) {
            self::DIR => 'Director',
            self::ADIR => 'Assistant Director',
            self::FOC => 'Flight Operations Coordinator',
            self::FOAC => 'Flight Operations Assistant Coordinator',
            self::AOC => 'ATC Operations Coordinator',
            self::AOAC => 'ATC Operations Assistant Coordinator',
            self::TC => 'Training Coordinator',
            self::TAC => 'Training Assistant Coordinator',
            self::TA => 'Division Training Advisor',
            self::T0 => 'Division Trainer',
            self::MC => 'Membership Coordinator',
            self::EC => 'Event Coordinator',
            self::EAC => 'Event Assistant Coordinator',
            self::EA => 'Division Event Advisor',
            self::PRC => 'Public Relations Coordinator',
            self::PRAC => 'Public Relations Assistant Coordinator',
            self::PRA => 'Public Relations Advisor',
            self::WM => 'Webmaster',
            self::AWM => 'Assistant Webmaster',
            self::WMA => 'Webmaster Advisor',
        };
    }

    public function callsign(): string
    {
        return 'CO-'.strtoupper($this->name);
    }

    /**
     * Resolve a Role from an IVAO staffPositionId (e.g. "-WMA2", "-WM", "-T0").
     * Strips the leading dash and any trailing digits to find the matching case name.
     * Returns null if no match is found.
     */
    public static function fromStaffPositionId(string $staffPositionId): ?self
    {
        $id = ltrim($staffPositionId, '-');

        $bestMatch = null;
        $bestMatchLength = 0;

        foreach (self::cases() as $role) {
            $caseName = $role->name;

            if (! str_starts_with($id, $caseName)) {
                continue;
            }

            $remainder = substr($id, strlen($caseName));

            if (
                ($remainder === '' || ctype_digit($remainder))
                && strlen($caseName) > $bestMatchLength
            ) {
                $bestMatch = $role;
                $bestMatchLength = strlen($caseName);
            }
        }

        return $bestMatch;
    }
}

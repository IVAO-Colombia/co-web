import type { EventStatus, EventTag, EventType, SlotStatus } from './backend.d';

export type Event = {
    id: number;
    name: string;
    name_en: string | null;
    description: string;
    description_en: string | null;
    slug: string;
    image_url: string | null;
    type: EventType;
    tags: EventTag[];
    pilot_slots_enabled: boolean;
    atc_slots_enabled: boolean;
    locations: string;
    starts_at: string;
    ends_at: string | null;
    status: EventStatus;
    created_by: number;
};

type SlotUser = {
    id: number;
    name: string;
    vid: number;
};

export type PilotSlot = {
    id: number;
    event_id: number;
    pilot_id: number | null;
    airline_icao: string;
    flight_number: string;
    aircraft: string;
    origin: string;
    destination: string;
    departs_at: string;
    gate: string | null;
    status: SlotStatus;
    pilot: SlotUser | null;
};

export type AtcSlot = {
    id: number;
    event_id: number;
    atc_id: number | null;
    callsign: string;
    starts_at: string;
    ends_at: string;
    status: SlotStatus;
    atc: SlotUser | null;
};

export type EventDetail = Event & {
    pilot_slots: PilotSlot[];
    atc_slots: AtcSlot[];
};

export type AtcSlotRow = {
    callsign: string;
    starts_at: string;
    ends_at: string;
};

export type PilotSlotRow = {
    airline_icao: string;
    flight_number: string;
    aircraft: string;
    origin: string;
    destination: string;
    departs_at: string;
    gate: string;
};

export type AtcPosition = {
    id: number;
    ivao_id: number | null;
    airport_id: string;
    atc_callsign: string;
    compose_position: string;
    middle_identifier: string | null;
    position: string;
    frequency: string | null;
    created_at: string | null;
    updated_at: string | null;
};

export type AtcPositionFra = {
    id: number;
    atc_position_id: number;
    atc_compose_position: string;
    ivao_id: number;
    ivao_user_id: number | null;
    ivao_atc_position_id: number | null;
    ivao_subcenter_id: number | null;
    start_time: string;
    end_time: string;
    monday: boolean;
    tuesday: boolean;
    wednesday: boolean;
    thursday: boolean;
    friday: boolean;
    saturday: boolean;
    sunday: boolean;
    date: string | null;
    min_atc: number | null;
    active: boolean;
    is_blacklist: boolean;
    created_at: string | null;
    updated_at: string | null;
};

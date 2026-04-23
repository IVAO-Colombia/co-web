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

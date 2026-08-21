import type {
    AtcTraining,
    EventStatus,
    EventTag,
    EventType,
    PilotTraining,
    SlotStatus,
    TrainingRequestStatus,
    TrainingRequestType,
} from './backend.d';

export type Event = {
    id: number;
    parent_event_id: number | null;
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
    is_recurring: boolean;
    recurrence_interval: number | null;
    recurrence_weekdays: number[] | null;
    recurrence_ends_at: string | null;
    created_by: number;
    occurrences?: Event[];
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
    arrives_at: string | null;
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
    arrives_at: string | null;
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

export type TrainingRequestUser = {
    id: number;
    name: string;
    vid: number;
    email: string;
    atc_rating: number;
    pilot_rating: number;
};

export type TrainingRequestAssignmentEntry = {
    at: string;
    by_id: number;
    by_name: string;
    trainer_id: number | null;
    trainer_name: string | null;
};

export type TrainingRequest = {
    id: number;
    type: TrainingRequestType;
    category: AtcTraining | PilotTraining;
    status: TrainingRequestStatus;
    occurs_at: string | null;
    internal_observations: string | null;
    public_observations: string | null;
    request_observations: string;
    trainer_id: number | null;
    assignment_history: TrainingRequestAssignmentEntry[] | null;
    trainee_id: number;
    event_id: number | null;
    ivao_reminder_sent_at: string | null;
    created_at: string;
    updated_at: string;
    trainee?: TrainingRequestUser;
    trainer?: TrainingRequestUser | null;
    event?: { id: number; name: string; slug: string } | null;
};

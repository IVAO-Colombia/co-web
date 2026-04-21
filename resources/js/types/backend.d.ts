export enum ATCRating {
    AS1 = 2,
    AS2 = 3,
    AS3 = 4,
    ADC = 5,
    APC = 6,
    ACC = 7,
    SEC = 8,
    SAI = 9,
    CAI = 10,
}
export type AtcPosition = {
    id: number;
    airportId: string;
    atcCallsign: string;
    composePosition: string;
    middleIdentifier: string | null;
    position: string;
    order: number;
    frequency: number;
    radarRange: number | null;
};
export enum EventStatus {
    DRAFT = 'draft',
    ACTIVE = 'active',
    CANCELLED = 'cancelled',
    FINALIZED = 'finalized',
}
export enum EventTag {
    VFR = 'vfr',
    IFR = 'ifr',
    CrossCountry = 'cross-country',
    Division = 'division',
    Hq = 'hq',
}
export enum EventType {
    ONLINE_DAY = 'online_day',
    EXAM = 'exam',
    TRAINING = 'training',
    RFO = 'rfo',
    RFE = 'rfe',
}
export enum PagesComponents {
    EVENTS_INDEX = 'events/Index',
    EVENTS_CREATE = 'events/Create',
    EVENTS_SHOW = 'events/Show',
}
export enum Permission {
    STAFF_ACCESS = 'staff_access',
    VIEW_EVENTS = 'view_events',
    CREATE_EVENTS = 'create_events',
    UPDATE_EVENTS = 'update_events',
    DELETE_EVENTS = 'delete_events',
}
export enum PilotRating {
    FS1 = 2,
    FS2 = 3,
    FS3 = 4,
    PP = 5,
    SPP = 6,
    CP = 7,
    ATP = 8,
    SFI = 9,
    CFI = 10,
}
export enum Role {
    DIR = 'director',
    ADIR = 'assistant_director',
    FOC = 'flight_operations_coordinator',
    FOAC = 'flight_operations_assistant_coordinator',
    AOC = 'atc_operations_coordinator',
    AOAC = 'atc_operations_assistant_coordinator',
    TC = 'training_coordinator',
    TAC = 'training_assistant_coordinator',
    TA = 'division_training_advisor',
    T0 = 'division_trainer',
    MC = 'membership_coordinator',
    EC = 'event_coordinator',
    EAC = 'event_assistant_coordinator',
    EA = 'division_event_advisor',
    PRC = 'public_relations_coordinator',
    PRAC = 'public_relations_assistant_coordinator',
    PRA = 'public_relations_advisor',
    WM = 'webmaster',
    AWM = 'assistant_webmaster',
    WMA = 'webmaster_advisor',
}
export enum SlotStatus {
    AVAILABLE = 'available',
    UNAVAILABLE = 'unavailable',
    CANCELLED = 'cancelled',
}
export enum UserAwardReportStatus {
    PENDING = 'pending',
    APPROVED = 'approved',
    REJECTED = 'rejected',
    OBSERVATION = 'observation',
}

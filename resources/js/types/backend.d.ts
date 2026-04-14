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
export enum EventStatus {
    DRAFT = 'draft',
    ACTIVE = 'active',
    CANCELLED = 'cancelled',
    FINALIZED = 'finalized',
}
export enum EventType {
    ONLINE_DAY = 'online_day',
    EXAM = 'exam',
    TRAINING = 'training',
    RFO = 'rfo',
    RFE = 'rfe',
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

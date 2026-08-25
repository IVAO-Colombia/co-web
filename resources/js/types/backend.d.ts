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
export enum AtcTraining {
    As2As3Intro = 'as2_as3_intro',
    AdcTheory1 = 'adc_theory_1',
    AdcTheory2 = 'adc_theory_2',
    AdcTheory3 = 'adc_theory_3',
    AdcTheory4 = 'adc_theory_4',
    AdcPractical = 'adc_practical',
    ApcTheory1 = 'apc_theory_1',
    ApcTheory2 = 'apc_theory_2',
    ApcTheory3 = 'apc_theory_3',
    ApcPractical = 'apc_practical',
    AccTheory1 = 'acc_theory_1',
    AccTheory2 = 'acc_theory_2',
    AccPractical = 'acc_practical',
}
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
    REGULAR = 'REGULAR',
}
export enum PagesComponents {
    DASHBOARD = 'Dashboard',
    EVENTS_INDEX = 'dashboard/events/Index',
    EVENTS_CREATE = 'dashboard/events/Create',
    EVENTS_EDIT = 'dashboard/events/Edit',
    EVENTS_SHOW = 'dashboard/events/Show',
    DASHBOARD_RESERVATIONS = 'dashboard/Reservations',
    DASHBOARD_IMAGE_GENERATOR = 'dashboard/ImageGenerator',
    DASHBOARD_TRAININGS = 'dashboard/trainings/Index',
    STAFF_TRAININGS_INDEX = 'dashboard/staff/trainings/Index',
    STAFF_TRAININGS_SHOW = 'dashboard/staff/trainings/Show',
    LANDING_HOME = 'Welcome',
    LANDING_EVENTS = 'landing/events/Index',
    LANDING_EVENTS_SHOW = 'landing/events/Show',
    LANDING_ABOUT_US = 'landing/Aboutus',
    LANDING_TRAINING = 'landing/Training',
}
export enum Permission {
    STAFF_ACCESS = 'staff_access',
    VIEW_EVENTS = 'view_events',
    CREATE_EVENTS = 'create_events',
    UPDATE_EVENTS = 'update_events',
    DELETE_EVENTS = 'delete_events',
    GENERATE_EVENT_IMAGES = 'generate_event_images',
    VIEW_TRAINING_REQUESTS = 'view_training_requests',
    UPDATE_TRAINING_REQUESTS = 'update_training_requests',
    ASSIGN_TRAINING_REQUESTS = 'assign_training_requests',
    EDIT_TRAINING_NOTES = 'edit_training_notes',
    BE_ASSIGNED_TO_TRAININGS = 'be_assigned_to_trainings',
    CANCEL_PILOT_SLOT = 'cancel_pilot_slot',
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
export enum PilotSlotCategory {
    DEPARTURE = 'departure',
    ARRIVAL = 'arrival',
}
export enum PilotTraining {
    Fs2Fs3Intro = 'fs2_fs3_intro',
    PpTheory1 = 'pp_theory_1',
    PpTheory2 = 'pp_theory_2',
    PpTheory3 = 'pp_theory_3',
    PpTheory4 = 'pp_theory_4',
    PpTheory5 = 'pp_theory_5',
    PpPractical = 'pp_practical',
    SppTheory1 = 'spp_theory_1',
    SppTheory2 = 'spp_theory_2',
    SppTheory3 = 'spp_theory_3',
    SppTheory4 = 'spp_theory_4',
    SppPractical = 'spp_practical',
    CpTheory1 = 'cp_theory_1',
    CpTheory2 = 'cp_theory_2',
    CpTheory3 = 'cp_theory_3',
    CpTheory4 = 'cp_theory_4',
    CpPractical = 'cp_practical',
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
    RESERVED = 'reserved',
    CONFIRMED = 'confirmed',
}
export enum TrainingNoteVisibility {
    PublicNote = 'public',
    InternalNote = 'internal',
}
export enum TrainingRequestStatus {
    PENDING = 'pending',
    SCHEDULED = 'scheduled',
    CANCELLED = 'cancelled',
    COMPLETED = 'completed',
}
export enum TrainingRequestType {
    ATC = 'atc',
    Pilot = 'pilot',
}
export enum UserAwardReportStatus {
    PENDING = 'pending',
    APPROVED = 'approved',
    REJECTED = 'rejected',
    OBSERVATION = 'observation',
}

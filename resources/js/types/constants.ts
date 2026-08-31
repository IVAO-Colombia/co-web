import { wTrans } from 'laravel-vue-i18n';
import type { ComputedRef } from 'vue';
import type { BadgeVariants } from '@/components/ui/badge';
import {
    EventStatus,
    EventTag,
    EventType,
    PilotSlotCategory,
    Role,
    SlotStatus,
    TrainingRequestStatus,
    TrainingRequestType,
} from './backend.d';

export type EventConstantsType = {
    statusVariants: Record<EventStatus, BadgeVariants['variant']>;
    statusLabels: Record<EventStatus, string | ComputedRef<string>>;
    typeLabels: Record<EventType, string | ComputedRef<string>>;
    tagLabels: Record<EventTag, string | ComputedRef<string>>;
    // Weekday values follow Carbon's dayOfWeek convention (0 = Sunday .. 6 = Saturday),
    // ordered Monday-first for display.
    weekdays: { value: number; label: string | ComputedRef<string> }[];
};

export const EventConstants: EventConstantsType = {
    statusVariants: {
        [EventStatus.ACTIVE]: 'default',
        [EventStatus.DRAFT]: 'secondary',
        [EventStatus.CANCELLED]: 'destructive',
        [EventStatus.FINALIZED]: 'outline',
    },
    statusLabels: {
        [EventStatus.DRAFT]: wTrans('Draft'),
        [EventStatus.ACTIVE]: wTrans('Active'),
        [EventStatus.CANCELLED]: wTrans('Cancelled'),
        [EventStatus.FINALIZED]: wTrans('Finalized'),
    },
    typeLabels: {
        [EventType.ONLINE_DAY]: wTrans('Online Day'),
        [EventType.EXAM]: wTrans('Exam'),
        [EventType.TRAINING]: wTrans('Training'),
        [EventType.RFO]: wTrans('RFO'),
        [EventType.RFE]: wTrans('RFE'),
        [EventType.AIRBRIDGE]: wTrans('Airbridge'),
        [EventType.MSE]: wTrans('MSE'),
        [EventType.FLY_IN]: wTrans('Fly In'),
        [EventType.FLY_OUT]: wTrans('Fly Out'),
        [EventType.FLY_IN_FLY_OUT]: wTrans('Fly In / Fly Out'),
    },
    tagLabels: {
        [EventTag.VFR]: wTrans('VFR'),
        [EventTag.IFR]: wTrans('IFR'),
        [EventTag.CrossCountry]: wTrans('Cross Country'),
        [EventTag.Division]: wTrans('Division'),
        [EventTag.Hq]: wTrans('HQ'),
    },
    weekdays: [
        { value: 1, label: wTrans('Monday') },
        { value: 2, label: wTrans('Tuesday') },
        { value: 3, label: wTrans('Wednesday') },
        { value: 4, label: wTrans('Thursday') },
        { value: 5, label: wTrans('Friday') },
        { value: 6, label: wTrans('Saturday') },
        { value: 0, label: wTrans('Sunday') },
    ],
};

export type SlotsConstantsType = {
    statusLabels: Record<SlotStatus, string | ComputedRef<string>>;
    statusVariants: Record<SlotStatus, BadgeVariants['variant']>;
    pilotCategoryLabels: Record<
        PilotSlotCategory,
        string | ComputedRef<string>
    >;
};

export const SlotsConstants: SlotsConstantsType = {
    statusLabels: {
        [SlotStatus.AVAILABLE]: wTrans('Available'),
        [SlotStatus.RESERVED]: wTrans('Reserved'),
        [SlotStatus.CONFIRMED]: wTrans('Confirmed'),
    },
    statusVariants: {
        [SlotStatus.AVAILABLE]: 'outline',
        [SlotStatus.RESERVED]: 'outline',
        [SlotStatus.CONFIRMED]: 'default',
    },
    pilotCategoryLabels: {
        [PilotSlotCategory.DEPARTURE]: wTrans('Departure'),
        [PilotSlotCategory.ARRIVAL]: wTrans('Arrival'),
    },
};

export type TrainingRequestConstantsType = {
    statusVariants: Record<TrainingRequestStatus, BadgeVariants['variant']>;
    statusLabels: Record<TrainingRequestStatus, string | ComputedRef<string>>;
    typeLabels: Record<TrainingRequestType, string | ComputedRef<string>>;
};

export const TrainingRequestConstants: TrainingRequestConstantsType = {
    statusVariants: {
        [TrainingRequestStatus.PENDING]: 'secondary',
        [TrainingRequestStatus.SCHEDULED]: 'default',
        [TrainingRequestStatus.CANCELLED]: 'destructive',
        [TrainingRequestStatus.COMPLETED]: 'outline',
    },
    statusLabels: {
        [TrainingRequestStatus.PENDING]: wTrans('Pending'),
        [TrainingRequestStatus.SCHEDULED]: wTrans('Scheduled'),
        [TrainingRequestStatus.CANCELLED]: wTrans('Cancelled'),
        [TrainingRequestStatus.COMPLETED]: wTrans('Completed'),
    },
    typeLabels: {
        [TrainingRequestType.ATC]: wTrans('ATC'),
        [TrainingRequestType.Pilot]: wTrans('Pilot'),
    },
};

export type RoleConstantsType = {
    labels: Record<Role, string | ComputedRef<string>>;
};

export const RoleConstants: RoleConstantsType = {
    labels: {
        [Role.DIR]: wTrans('Director'),
        [Role.ADIR]: wTrans('Assistant Director'),
        [Role.FOC]: wTrans('Flight Operations Coordinator'),
        [Role.FOAC]: wTrans('Flight Operations Assistant Coordinator'),
        [Role.AOC]: wTrans('ATC Operations Coordinator'),
        [Role.AOAC]: wTrans('ATC Operations Assistant Coordinator'),
        [Role.TC]: wTrans('Training Coordinator'),
        [Role.TAC]: wTrans('Training Assistant Coordinator'),
        [Role.TA]: wTrans('Division Training Advisor'),
        [Role.T0]: wTrans('Division Trainer'),
        [Role.MC]: wTrans('Membership Coordinator'),
        [Role.EC]: wTrans('Event Coordinator'),
        [Role.EAC]: wTrans('Event Assistant Coordinator'),
        [Role.EA]: wTrans('Division Event Advisor'),
        [Role.PRC]: wTrans('Public Relations Coordinator'),
        [Role.PRAC]: wTrans('Public Relations Assistant Coordinator'),
        [Role.PRA]: wTrans('Public Relations Advisor'),
        [Role.WM]: wTrans('Webmaster'),
        [Role.AWM]: wTrans('Assistant Webmaster'),
        [Role.WMA]: wTrans('Webmaster Advisor'),
    },
};

/**
 * Submitting a request on this site is not enough: the member must also open
 * the request on the IVAO website.
 *
 * Shared with the backend through IVAO_TRAINING_REQUEST_URL in .env, which
 * also feeds config('training.ivao_request_url') for the emails. Vite inlines
 * this at build time, so changing it requires a rebuild.
 */
export const IVAO_TRAINING_REQUEST_URL: string = import.meta.env
    .VITE_IVAO_TRAINING_REQUEST_URL;

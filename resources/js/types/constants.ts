import { wTrans } from 'laravel-vue-i18n';
import type { ComputedRef } from 'vue';
import type { BadgeVariants } from '@/components/ui/badge';
import {
    EventStatus,
    EventTag,
    EventType,
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
        [EventType.REGULAR]: wTrans('Regular'),
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

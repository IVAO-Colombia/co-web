import { wTrans } from 'laravel-vue-i18n';
import type { ComputedRef } from 'vue';
import type { BadgeVariants } from '@/components/ui/badge';
import { EventStatus, EventTag, EventType, SlotStatus } from './backend.d';

export type EventConstantsType = {
    statusVariants: Record<EventStatus, BadgeVariants['variant']>;
    statusLabels: Record<EventStatus, string | ComputedRef<string>>;
    typeLabels: Record<EventType, string | ComputedRef<string>>;
    tagLabels: Record<EventTag, string | ComputedRef<string>>;
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
    },
    tagLabels: {
        [EventTag.VFR]: wTrans('VFR'),
        [EventTag.IFR]: wTrans('IFR'),
        [EventTag.CrossCountry]: wTrans('Cross Country'),
        [EventTag.Division]: wTrans('Division'),
        [EventTag.Hq]: wTrans('HQ'),
    },
};

export type SlotsConstantsType = {
    statusLabels: Record<SlotStatus, string | ComputedRef<string>>;
    statusVariants: Record<SlotStatus, BadgeVariants['variant']>;
};

export const SlotsConstants: SlotsConstantsType = {
    statusLabels: {
        [SlotStatus.AVAILABLE]: wTrans('Available'),
        [SlotStatus.UNAVAILABLE]: wTrans('Unavailable'),
        [SlotStatus.CANCELLED]: wTrans('Cancelled'),
    },
    statusVariants: {
        [SlotStatus.AVAILABLE]: 'secondary',
        [SlotStatus.UNAVAILABLE]: 'default',
        [SlotStatus.CANCELLED]: 'destructive',
    },
};

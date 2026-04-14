import type { EventStatus, EventType } from './backend.d';

export type Event = {
    id: number;
    name: string;
    name_en: string | null;
    slug: string;
    image_url: string | null;
    type: EventType;
    tags: string[];
    pilot_slots_enabled: boolean;
    atc_slots_enabled: boolean;
    locations: string;
    starts_at: string;
    ends_at: string | null;
    status: EventStatus;
};

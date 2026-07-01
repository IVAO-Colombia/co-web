<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { CalendarClock, Clock3, MapPin, MoveRight, Tag } from 'lucide-vue-next';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';
import { getDateParts } from '@/lib/utils';
import { EventConstants } from '@/types';
import type { Event } from '@/types';
import { show } from '@/routes/home/events';

const props = defineProps<{
    event: Event;
    showStatus?: boolean;
    showType?: boolean;
    showTags?: boolean;
}>();

const { locale } = useLocale();

const fallbackImage = '/img/day_2.png';

const startsAtParts = computed(() =>
    getDateParts(props.event.starts_at, locale.value),
);
const endsAtParts = computed(() =>
    props.event.ends_at
        ? getDateParts(props.event.ends_at, locale.value)
        : null,
);

function hasReservation(): boolean {
    return props.event.pilot_slots_enabled || props.event.atc_slots_enabled;
}

function localizedName(): string {
    if (locale.value === 'en') {
        return props.event.name_en ?? props.event.name;
    }

    return props.event.name;
}

function localizedDescription(): string {
    if (locale.value === 'en') {
        return props.event.description_en ?? props.event.description;
    }

    return props.event.description;
}
</script>

<template>
    <article
        class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-slate-700/60 dark:bg-slate-800"
    >
        <div class="relative h-52 overflow-hidden">
            <img
                :src="event.image_url ?? fallbackImage"
                :alt="localizedName()"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />

            <div class="absolute top-3 left-3 z-10 flex flex-wrap gap-2">
                <span
                    v-if="hasReservation()"
                    class="rounded-full border border-emerald-300/70 bg-emerald-500/90 px-3 py-1 font-sans text-xs font-semibold tracking-wide text-white shadow-sm backdrop-blur-sm dark:border-emerald-200/30 dark:bg-emerald-400/85"
                >
                    {{ wTrans('Booking Available') }}
                </span>
            </div>

            <div
                class="absolute inset-0 bg-linear-to-t from-black/55 via-black/20 to-black/10"
            ></div>

            <div
                class="absolute right-3 bottom-3 rounded-xl bg-white/90 px-3 py-2 backdrop-blur-sm dark:bg-slate-900/90"
            >
                <p
                    class="font-heading text-2xl leading-none font-black text-slate-900 sm:text-3xl dark:text-white"
                >
                    {{ startsAtParts.day }}
                </p>
                <div class="mt-1 flex items-end gap-1">
                    <span
                        class="font-sans text-xs font-bold text-slate-600 dark:text-slate-300"
                    >
                        {{ startsAtParts.month }}
                    </span>
                    <span
                        class="font-sans text-xs text-slate-500 dark:text-slate-400"
                    >
                        {{ startsAtParts.year }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex grow flex-col items-start gap-4 p-5">
            <div class="flex items-start justify-between gap-3">
                <h3
                    class="line-clamp-2 font-heading text-xl leading-tight font-black tracking-tight text-slate-900 sm:text-2xl dark:text-white"
                >
                    {{ localizedName() }}
                </h3>
                <span
                    v-if="showType"
                    class="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 font-sans text-[11px] font-semibold tracking-[0.2em] text-slate-600 uppercase dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300"
                >
                    {{ EventConstants.typeLabels[event.type] }}
                </span>
            </div>

            <div
                class="flex w-full flex-wrap items-center justify-between gap-x-4 gap-y-2 font-sans text-sm text-slate-600 dark:text-slate-300"
            >
                <span class="inline-flex items-center gap-1.5">
                    <Clock3 class="h-4 w-4" />
                    {{ startsAtParts.time }}
                    {{ endsAtParts ? `- ${endsAtParts.time}` : '' }}
                </span>
                <span
                    class="inline-flex max-w-full min-w-0 items-center gap-1.5"
                >
                    <MapPin class="h-4 w-4" />
                    <span class="truncate">{{
                        event.locations || wTrans('All FIRs')
                    }}</span>
                </span>
            </div>

            <p
                class="line-clamp-3 font-sans text-sm leading-relaxed text-slate-600 dark:text-slate-400"
            >
                {{ localizedDescription() }}
            </p>

            <div
                v-if="showTags && event.tags?.length"
                class="flex flex-wrap gap-2"
            >
                <span
                    v-for="tag in event.tags"
                    :key="tag"
                    class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 font-sans text-[11px] font-semibold tracking-[0.18em] text-slate-600 uppercase dark:border-slate-600 dark:bg-slate-700/85 dark:text-slate-300"
                >
                    <Tag class="h-3.5 w-3.5" />
                    {{ EventConstants.tagLabels[tag] }}
                </span>
            </div>

            <Link
                :href="show(event.slug)"
                class="mt-auto inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-2 font-sans text-sm font-semibold text-slate-800 transition-colors hover:bg-slate-100 sm:w-auto dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700"
            >
                <CalendarClock class="h-4 w-4" />
                {{ wTrans('Details') }}
                <MoveRight class="h-4 w-4" />
            </Link>
        </div>
    </article>
</template>

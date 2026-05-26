<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CalendarDays,
    Clock,
    MapPin,
    MoveRight,
    PlaneTakeoff,
    Radio,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AtcSlotsTab from '@/components/landing/events/AtcSlotsTab.vue';
import PilotSlotsTab from '@/components/landing/events/PilotSlotsTab.vue';
import Header from '@/components/landing/Header.vue';
import { useLocale } from '@/composables/useLocale';
import { getDateParts } from '@/lib/utils';
import type { AtcPositionFra, EventDetail } from '@/types';
import { EventConstants } from '@/types';
import { events } from '@/routes/home';

const props = defineProps<{
    event: EventDetail;
    frasByCallsign: Record<string, AtcPositionFra[]>;
}>();

const { locale } = useLocale();

const fallbackImage = '/img/16x9_image.jpg';

const localizedName = computed(() =>
    locale.value === 'en'
        ? (props.event.name_en ?? props.event.name)
        : props.event.name,
);

const localizedDescription = computed(() =>
    locale.value === 'en'
        ? (props.event.description_en ?? props.event.description)
        : props.event.description,
);

const startsAtParts = computed(() =>
    getDateParts(props.event.starts_at, locale.value),
);
const endsAtParts = computed(() =>
    props.event.ends_at
        ? getDateParts(props.event.ends_at, locale.value)
        : null,
);

const hasBothSlotTypes = computed(
    () => props.event.pilot_slots_enabled && props.event.atc_slots_enabled,
);

type SlotTab = 'pilot' | 'atc';

const activeTab = ref<SlotTab>(props.event.atc_slots_enabled ? 'atc' : 'pilot');
</script>

<template>
    <Head :title="localizedName" />

    <div
        class="min-h-screen bg-white text-slate-900 dark:bg-slate-950 dark:text-white"
    >
        <!-- Hero banner -->
        <section class="relative">
            <Header :brand-text="$t('Events').toLocaleUpperCase()" />

            <div class="absolute inset-0">
                <img
                    :src="fallbackImage"
                    alt="events"
                    class="h-full w-full object-cover opacity-35"
                />
                <div
                    class="absolute inset-0 bg-linear-to-b from-white/40 via-slate-100/70 to-slate-50 dark:from-black/35 dark:via-slate-950/75 dark:to-slate-950"
                ></div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(13,44,153,0.18),transparent_45%)] dark:bg-[radial-gradient(circle_at_top,rgba(47,69,255,0.28),transparent_45%)]"
                ></div>
            </div>

            <div
                class="relative z-10 mx-auto w-full max-w-7xl px-4 pt-8 pb-8 sm:px-6 lg:px-8"
            >
                <Link
                    :href="events()"
                    class="inline-flex items-center gap-2 text-sm text-slate-700/70 transition-colors hover:text-slate-900 dark:text-white/60 dark:hover:text-white"
                >
                    <ArrowLeft class="h-4 w-4" />
                    {{ $t('Back to Events') }}
                </Link>

                <div class="mt-8 max-w-4xl">
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full border border-slate-300/70 bg-white/60 px-3 py-1 text-xs font-semibold tracking-[0.2em] text-slate-700 uppercase dark:border-white/15 dark:bg-white/10 dark:text-white/80"
                        >
                            {{ EventConstants.typeLabels[event.type] }}
                        </span>
                        <span
                            v-for="tag in event.tags"
                            :key="tag"
                            class="rounded-full border border-slate-200 bg-white/40 px-3 py-1 text-xs font-medium text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60"
                        >
                            {{ EventConstants.tagLabels[tag] }}
                        </span>
                    </div>

                    <h1
                        class="text-4xl leading-tight font-black tracking-tight text-balance text-slate-900 sm:text-5xl lg:text-6xl dark:text-white"
                    >
                        {{ localizedName }}
                    </h1>

                    <div
                        class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-600 dark:text-white/65"
                    >
                        <span class="inline-flex items-center gap-2">
                            <CalendarDays class="h-4 w-4 shrink-0" />
                            {{ startsAtParts.day }}
                            {{ startsAtParts.month }}
                            {{ startsAtParts.year }}
                            <template v-if="endsAtParts">
                                &ndash;
                                {{ endsAtParts.day }}
                                {{ endsAtParts.month }}
                                {{ endsAtParts.year }}
                            </template>
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <Clock class="h-4 w-4 shrink-0" />
                            {{ startsAtParts.time }}
                            <template v-if="endsAtParts">
                                <MoveRight
                                    class="h-3.5 w-3.5 text-slate-400 dark:text-white/40"
                                />
                                {{ endsAtParts.time }}
                            </template>
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <MapPin class="h-4 w-4 shrink-0" />
                            {{ event.locations }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <div class="mx-auto w-full max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <!-- Description -->
            <div class="my-7 flex justify-between">
                <div class="my-7 w-2/3">
                    <p
                        class="text-base leading-relaxed text-slate-600 sm:text-lg dark:text-white/70"
                    >
                        {{ localizedDescription }}
                    </p>
                </div>
                <div class="aspect-video w-1/3 overflow-hidden">
                    <img
                        :src="event.image_url ?? fallbackImage"
                        :alt="localizedName"
                        class="h-full w-full object-contain"
                    />
                </div>
            </div>

            <!-- Slots section -->
            <section
                v-if="event.pilot_slots_enabled || event.atc_slots_enabled"
                class="mb-16"
            >
                <!-- Tab bar (only shown when both slot types are enabled) -->
                <div
                    v-if="hasBothSlotTypes"
                    class="mb-8 inline-flex rounded-xl border border-slate-200 bg-slate-100 p-1 dark:border-white/10 dark:bg-white/5"
                >
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all"
                        :class="
                            activeTab === 'atc'
                                ? 'bg-emerald-600 text-white shadow'
                                : 'text-slate-500 hover:text-slate-900 dark:text-white/50 dark:hover:text-white'
                        "
                        @click="activeTab = 'atc'"
                    >
                        <Radio class="h-4 w-4" />
                        {{ $t('ATC Slots') }}
                        <span
                            class="rounded-full px-1.5 py-0.5 text-xs"
                            :class="
                                activeTab === 'atc'
                                    ? 'bg-white/20'
                                    : 'bg-slate-200 dark:bg-white/10'
                            "
                        >
                            {{ event.atc_slots.length }}
                        </span>
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all"
                        :class="
                            activeTab === 'pilot'
                                ? 'bg-sky-600 text-white shadow'
                                : 'text-slate-500 hover:text-slate-900 dark:text-white/50 dark:hover:text-white'
                        "
                        @click="activeTab = 'pilot'"
                    >
                        <PlaneTakeoff class="h-4 w-4" />
                        {{ $t('Pilot Slots') }}
                        <span
                            class="rounded-full px-1.5 py-0.5 text-xs"
                            :class="
                                activeTab === 'pilot'
                                    ? 'bg-white/20'
                                    : 'bg-slate-200 dark:bg-white/10'
                            "
                        >
                            {{ event.pilot_slots.length }}
                        </span>
                    </button>
                </div>

                <!-- Pilot Slots panel -->
                <div
                    v-if="
                        event.pilot_slots_enabled &&
                        (!hasBothSlotTypes || activeTab === 'pilot')
                    "
                >
                    <!-- Section heading (shown when no tabs) -->
                    <div
                        v-if="!hasBothSlotTypes"
                        class="mb-6 flex items-center gap-3"
                    >
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500/15 ring-1 ring-sky-500/30"
                        >
                            <PlaneTakeoff class="h-4 w-4 text-sky-400" />
                        </span>
                        <h2
                            class="text-xl font-bold text-slate-900 dark:text-white"
                        >
                            {{ $t('Pilot Slots') }}
                        </h2>
                        <span
                            class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:border-white/15 dark:bg-white/5 dark:text-white/60"
                        >
                            {{ event.pilot_slots.length }}
                        </span>
                    </div>

                    <PilotSlotsTab
                        :event-slug="event.slug"
                        :event-starts-at="event.starts_at"
                        :pilot-slots="event.pilot_slots"
                    />
                </div>

                <!-- ATC Slots panel -->
                <div
                    v-if="
                        event.atc_slots_enabled &&
                        (!hasBothSlotTypes || activeTab === 'atc')
                    "
                >
                    <!-- Section heading (shown when no tabs) -->
                    <div
                        v-if="!hasBothSlotTypes"
                        class="mb-6 flex items-center gap-3"
                    >
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/15 ring-1 ring-emerald-500/30"
                        >
                            <Radio class="h-4 w-4 text-emerald-400" />
                        </span>
                        <h2
                            class="text-xl font-bold text-slate-900 dark:text-white"
                        >
                            {{ $t('ATC Slots') }}
                        </h2>
                        <span
                            class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:border-white/15 dark:bg-white/5 dark:text-white/60"
                        >
                            {{ event.atc_slots.length }}
                        </span>
                    </div>

                    <AtcSlotsTab
                        :event-slug="event.slug"
                        :event-starts-at="event.starts_at"
                        :atc-slots="event.atc_slots"
                        :fras-by-callsign="frasByCallsign"
                    />
                </div>
            </section>
        </div>
    </div>
</template>

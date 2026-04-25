<script setup lang="ts">
import { utc } from '@date-fns/utc';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { enUS } from 'date-fns/locale';
import {
    ArrowLeft,
    CalendarDays,
    Clock,
    MapPin,
    MoveRight,
    PlaneTakeoff,
    Radio,
} from 'lucide-vue-next';
import { computed, onBeforeMount, ref } from 'vue';
import { store as reserveAtcSlot } from '@/actions/App/Http/Controllers/Landing/AtcSlotReservationsController';
import Header from '@/components/landing/Header.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useLocale } from '@/composables/useLocale';
import { Ivao } from '@/lib/ivao';
import { formatAtcTime, formatDateTime, getDateParts } from '@/lib/utils';
import auth from '@/routes/auth';
import { events } from '@/routes/home';
import {
    ATCRatings,
    EventConstants,
    SlotStatus,
    SlotsConstants,
    ATCRating,
} from '@/types';
import type {
    AtcPositionFra,
    ATCRatingValue,
    AtcSlot,
    EventDetail,
} from '@/types';

const props = defineProps<{
    event: EventDetail;
    frasByCallsign: Record<string, AtcPositionFra[]>;
}>();

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isLoggedIn = computed(() => !!user.value);
const { locale } = useLocale();
const airlineLogos = ref<Record<string, string>>({});

const fallbackImage = '/img/16x9_image.jpg';

onBeforeMount(async () => {
    // Preload airline logos for pilot slots
    const icos = new Set(
        props.event.pilot_slots.map((slot) => slot.airline_icao),
    );

    for (const icao of icos) {
        airlineLogos.value[icao] = await Ivao.getAirlineLogoUrl(icao);
    }
});

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

const atcSlotsByCallsign = computed(() => {
    const groups: Record<
        string,
        {
            min_atc_rating: ATCRatingValue;
            slots: AtcSlot[];
            can_reserve: boolean;
        }
    > = {};

    for (const slot of props.event.atc_slots) {
        if (!groups[slot.callsign]) {
            const minAtcRating = getMinAtcRating(slot.callsign);
            groups[slot.callsign] = {
                min_atc_rating: minAtcRating,
                slots: [],
                can_reserve: user.value
                    ? ATCRating[minAtcRating.key] <= user.value?.atc_rating
                    : false,
            };
        }

        groups[slot.callsign].slots.push(slot);
    }

    return groups;
});

const pilotSlotsByAirline = computed(() => {
    const groups: Record<string, typeof props.event.pilot_slots> = {};

    for (const slot of props.event.pilot_slots) {
        if (!groups[slot.airline_icao]) {
            groups[slot.airline_icao] = [];
        }

        groups[slot.airline_icao].push(slot);
    }

    return groups;
});

function getMinAtcRating(callsign: string): ATCRatingValue {
    const fras = props.frasByCallsign[callsign] ?? [];

    if (fras.length === 0) {
        return ATCRatings[ATCRating.AS1];
    }

    const utcDate = parseISO(props.event.starts_at, { in: utc });
    const eventDateStr = format(utcDate, 'yyyy-MM-dd');
    // const eventTimeStr = format(utcDate, 'HH:mm');
    const dayOfWeek = format(utcDate, 'EEEE', {
        locale: enUS,
    }).toLocaleLowerCase();

    const applyableFras = fras.filter((fra) => {
        if (fra.date !== null) {
            return fra.date === eventDateStr;
        }

        const dayMatches = fra[
            `${dayOfWeek}` as keyof AtcPositionFra
        ] as boolean;

        return dayMatches;
        // return (
        //     dayMatches &&
        //     eventTimeStr >= fra.startTime &&
        //     eventTimeStr <= fra.endTime
        // );
    });

    return applyableFras.length === 0
        ? ATCRatings[ATCRating.AS1]
        : ATCRatings[applyableFras[0].min_atc as ATCRating];
}
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
                    class="absolute inset-0 bg-linear-to-b from-black/35 via-slate-950/75 to-slate-950"
                ></div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(47,69,255,0.28),transparent_45%)]"
                ></div>
            </div>

            <div
                class="relative z-10 mx-auto w-full max-w-7xl px-4 pt-8 pb-8 sm:px-6 lg:px-8"
            >
                <Link
                    :href="events()"
                    class="inline-flex items-center gap-2 text-sm text-white/60 transition-colors hover:text-white"
                >
                    <ArrowLeft class="h-4 w-4" />
                    {{ $t('Back to Events') }}
                </Link>

                <div class="mt-8 max-w-4xl">
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold tracking-[0.2em] text-white/80 uppercase"
                        >
                            {{ EventConstants.typeLabels[event.type] }}
                        </span>
                        <span
                            v-for="tag in event.tags"
                            :key="tag"
                            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-white/60"
                        >
                            {{ EventConstants.tagLabels[tag] }}
                        </span>
                    </div>

                    <h1
                        class="text-4xl leading-tight font-black tracking-tight text-balance text-white sm:text-5xl lg:text-6xl"
                    >
                        {{ localizedName }}
                    </h1>

                    <div
                        class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/65"
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
                                <MoveRight class="h-3.5 w-3.5 text-white/40" />
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

                    <!-- Guest CTA -->
                    <div
                        v-if="!isLoggedIn"
                        class="mb-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-sky-500/20 bg-sky-500/10 px-4 py-3"
                    >
                        <p class="text-sm text-sky-700 dark:text-sky-200/75">
                            {{
                                $t(
                                    'Log in to see slot availability and reserve your flight.',
                                )
                            }}
                        </p>
                        <Link
                            :href="auth.redirect()"
                            class="shrink-0 rounded-full bg-sky-600 px-4 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-sky-500"
                        >
                            {{ $t('Log in to reserve') }}
                        </Link>
                    </div>

                    <!-- Empty state -->
                    <div
                        v-if="event.pilot_slots.length === 0"
                        class="rounded-xl border border-slate-200 bg-slate-50 py-12 text-center dark:border-white/10 dark:bg-white/5"
                    >
                        <p class="text-sm text-slate-400 dark:text-white/40">
                            {{ $t('No pilot slots available for this event.') }}
                        </p>
                    </div>

                    <!-- Grouped tables by airline -->
                    <div v-else class="flex flex-col gap-4">
                        <div
                            v-for="(slots, airlineIcao) in pilotSlotsByAirline"
                            :key="airlineIcao"
                            class="overflow-hidden rounded-xl border border-slate-200 dark:border-white/10"
                        >
                            <!-- Airline header -->
                            <div
                                class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5"
                            >
                                <img
                                    v-if="airlineLogos[airlineIcao]"
                                    :src="airlineLogos[airlineIcao]"
                                    class="h-8 w-20 object-contain"
                                    :alt="`${airlineIcao} logo`"
                                />
                                <span
                                    v-else
                                    class="font-mono text-sm font-bold tracking-wider text-slate-900 dark:text-white"
                                >
                                    {{ airlineIcao }}
                                </span>
                                <span
                                    class="ml-auto rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:border-white/15 dark:bg-white/5 dark:text-white/60"
                                >
                                    {{ slots.length }}
                                </span>
                            </div>

                            <!-- Flights table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr
                                            class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase dark:border-white/10 dark:bg-white/5 dark:text-white/45"
                                        >
                                            <th class="px-4 py-3">
                                                {{ $t('Callsign') }}
                                            </th>
                                            <th class="px-4 py-3">
                                                {{ $t('Route') }}
                                            </th>
                                            <th class="px-4 py-3">
                                                {{ $t('Aircraft') }}
                                            </th>
                                            <th class="px-4 py-3">
                                                {{ $t('Departs At') }}
                                            </th>
                                            <th class="px-4 py-3">
                                                {{ $t('Gate') }}
                                            </th>
                                            <th
                                                v-if="isLoggedIn"
                                                class="px-4 py-3"
                                            >
                                                {{ $t('Status') }}
                                            </th>
                                            <th
                                                v-if="isLoggedIn"
                                                class="px-4 py-3"
                                            >
                                                {{ $t('Pilot') }}
                                            </th>
                                            <th
                                                v-if="isLoggedIn"
                                                class="w-10 px-4 py-3"
                                            />
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-slate-100 dark:divide-white/5"
                                    >
                                        <tr
                                            v-for="slot in slots"
                                            :key="slot.id"
                                            class="transition-colors hover:bg-slate-50 dark:hover:bg-white/3"
                                        >
                                            <td
                                                class="px-4 py-3.5 font-mono font-semibold text-slate-900 dark:text-white"
                                            >
                                                {{
                                                    `${slot.airline_icao}${slot.flight_number}`
                                                }}
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <span
                                                    class="inline-flex items-center gap-1.5 text-slate-700 dark:text-white/75"
                                                >
                                                    <span class="font-mono">{{
                                                        slot.origin
                                                    }}</span>
                                                    <MoveRight
                                                        class="h-3.5 w-3.5 shrink-0 text-slate-400 dark:text-white/35"
                                                    />
                                                    <span class="font-mono">{{
                                                        slot.destination
                                                    }}</span>
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-slate-600 dark:text-white/65"
                                            >
                                                {{ slot.aircraft }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-slate-600 dark:text-white/65"
                                            >
                                                {{
                                                    formatDateTime(
                                                        slot.departs_at,
                                                        locale,
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-slate-500 dark:text-white/45"
                                            >
                                                {{ slot.gate ?? '—' }}
                                            </td>
                                            <td
                                                v-if="isLoggedIn"
                                                class="px-4 py-3.5"
                                            >
                                                <Badge
                                                    :variant="
                                                        SlotsConstants
                                                            .statusVariants[
                                                            slot.status
                                                        ]
                                                    "
                                                    class="text-xs"
                                                >
                                                    {{
                                                        SlotsConstants
                                                            .statusLabels[
                                                            slot.status
                                                        ]
                                                    }}
                                                </Badge>
                                            </td>
                                            <td
                                                v-if="isLoggedIn"
                                                class="px-4 py-3.5 text-xs text-slate-600 dark:text-white/55"
                                            >
                                                <template v-if="slot.pilot">
                                                    {{ slot.pilot.name }} ({{
                                                        slot.pilot.vid
                                                    }})
                                                </template>
                                                <span
                                                    v-else
                                                    class="text-slate-400 dark:text-white/25"
                                                    >—</span
                                                >
                                            </td>
                                            <td
                                                v-if="isLoggedIn"
                                                class="px-4 py-3.5"
                                            >
                                                <Button
                                                    v-if="
                                                        slot.status ===
                                                        SlotStatus.AVAILABLE
                                                    "
                                                    size="sm"
                                                    variant="outline"
                                                    class="border-sky-500/40 text-xs text-sky-300 hover:bg-sky-500/15 hover:text-sky-200"
                                                    disabled
                                                >
                                                    {{ $t('Reserve') }}
                                                </Button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

                    <!-- Guest CTA -->
                    <div
                        v-if="!isLoggedIn"
                        class="mb-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3"
                    >
                        <p
                            class="text-sm text-emerald-700 dark:text-emerald-200/75"
                        >
                            {{
                                $t(
                                    'Log in to see slot availability and reserve your ATC position.',
                                )
                            }}
                        </p>
                        <Link
                            :href="auth.redirect()"
                            class="shrink-0 rounded-full bg-emerald-600 px-4 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-500"
                        >
                            {{ $t('Log in to reserve') }}
                        </Link>
                    </div>

                    <!-- Empty state -->
                    <div
                        v-if="event.atc_slots.length === 0"
                        class="rounded-xl border border-slate-200 bg-slate-50 py-12 text-center dark:border-white/10 dark:bg-white/5"
                    >
                        <p class="text-sm text-slate-400 dark:text-white/40">
                            {{ $t('No ATC slots available for this event.') }}
                        </p>
                    </div>

                    <!-- Grouped cards -->
                    <div
                        v-else
                        class=""
                        :class="{
                            'grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3':
                                Object.keys(atcSlotsByCallsign).length > 2,
                            'flex flex-col gap-4':
                                Object.keys(atcSlotsByCallsign).length <= 2,
                        }"
                    >
                        <div
                            v-for="(
                                callsignContent, callsign
                            ) in atcSlotsByCallsign"
                            :key="callsign"
                            class="relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/3 dark:shadow-none"
                        >
                            <!-- Insufficient rating overlay -->
                            <div
                                v-if="
                                    isLoggedIn && !callsignContent.can_reserve
                                "
                                class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 rounded-xl bg-slate-950/80 backdrop-blur-none"
                            >
                                <Radio class="h-5 w-5 text-white/40" />
                                <p
                                    class="text-center text-sm font-semibold text-white/80"
                                >
                                    {{
                                        $t("You don't have the required rating")
                                    }}
                                </p>
                                <img
                                    :src="
                                        callsignContent.min_atc_rating.imageUrl
                                    "
                                    class="mt-1 w-20 opacity-70"
                                    :alt="callsignContent.min_atc_rating.label"
                                />
                            </div>
                            <!-- Card header: callsign -->
                            <div
                                class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5"
                            >
                                <!-- Placeholder: future min ATC rating image -->
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400 dark:border-white/10 dark:bg-slate-900 dark:text-white/25"
                                >
                                    <Radio class="h-4 w-4" />
                                </div>
                                <h3
                                    class="font-mono text-sm font-bold tracking-wider text-slate-900 dark:text-white"
                                >
                                    {{ callsign }}
                                </h3>
                                <div class="ml-auto flex items-center gap-2">
                                    <span class="text-xs">min.</span>
                                    <img
                                        :src="
                                            callsignContent.min_atc_rating
                                                .imageUrl
                                        "
                                        class="w-20"
                                        :alt="
                                            callsignContent.min_atc_rating.label
                                        "
                                    />
                                </div>
                            </div>

                            <!-- Time slot rows -->
                            <div
                                class="flex flex-col divide-y divide-slate-100 dark:divide-white/5"
                            >
                                <div
                                    v-for="slot in callsignContent.slots"
                                    :key="slot.id"
                                    class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                                >
                                    <!-- Time range -->
                                    <p
                                        class="text-sm text-slate-700 dark:text-white/75"
                                    >
                                        {{ formatAtcTime(slot.starts_at) }}
                                        <span
                                            class="mx-1 text-slate-400 dark:text-white/35"
                                        >
                                            →
                                        </span>
                                        {{ formatAtcTime(slot.ends_at) }}
                                    </p>

                                    <!-- Right side: status + controller + reserve -->
                                    <div
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <Badge
                                            v-if="
                                                isLoggedIn &&
                                                slot.status !==
                                                    SlotStatus.AVAILABLE
                                            "
                                            :variant="
                                                SlotsConstants.statusVariants[
                                                    slot.status
                                                ]
                                            "
                                            class="text-xs"
                                        >
                                            {{
                                                SlotsConstants.statusLabels[
                                                    slot.status
                                                ]
                                            }}
                                        </Badge>
                                        <Link
                                            v-if="
                                                isLoggedIn &&
                                                slot.status ===
                                                    SlotStatus.AVAILABLE
                                            "
                                            size="sm"
                                            variant="outline"
                                            class="h-7 border-emerald-500/40 px-2.5 text-xs text-emerald-300 hover:bg-emerald-500/15 hover:text-emerald-200"
                                            method="post"
                                            :as="Button"
                                            :href="
                                                reserveAtcSlot.url({
                                                    event: event.slug,
                                                    slot: slot.id,
                                                })
                                            "
                                            :disabled="
                                                !callsignContent.can_reserve
                                            "
                                        >
                                            {{ $t('Reserve') }}
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

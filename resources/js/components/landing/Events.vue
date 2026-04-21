<script setup lang="ts">
import { CalendarClock, Clock3, MapPin, MoveRight, Eye } from 'lucide-vue-next';
import { computed } from 'vue';
import { wTrans } from 'laravel-vue-i18n';
import { useLocale } from '@/composables/useLocale';
import { EventStatus, EventTag, EventType } from '@/types/backend.d';
import type { Event } from '@/types';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onBeforeUnmount, onMounted, ref } from 'vue';

gsap.registerPlugin(ScrollTrigger);

const sectionRef = ref<HTMLElement | null>(null);
const titleRef = ref<HTMLElement | null>(null);
const subtitleRef = ref<HTMLElement | null>(null);
const cardsContainerRef = ref<HTMLElement | null>(null);

let animationContext: gsap.Context | null = null;

onMounted(() => {
    animationContext = gsap.context(() => {
        const triggerElement = cardsContainerRef.value ?? sectionRef.value;

        if (!triggerElement) {
            return;
        }

        if (titleRef.value) {
            gsap.fromTo(
                titleRef.value,
                { y: 30, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: triggerElement,
                        start: 'top 80%',
                        once: true,
                    },
                }
            );
        }

        if (subtitleRef.value) {
            gsap.fromTo(
                subtitleRef.value,
                { y: 20, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    delay: 0.1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: triggerElement,
                        start: 'top 80%',
                        once: true,
                    },
                }
            );
        }

        if (cardsContainerRef.value) {
            const cards = cardsContainerRef.value.querySelectorAll('article');

            gsap.fromTo(
                cards,
                { y: 40, opacity: 0, scale: 0.95 },
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.6,
                    stagger: 0.1,
                    delay: 0.3,
                    ease: 'back.out(1.5)',
                    scrollTrigger: {
                        trigger: triggerElement,
                        start: 'top 75%',
                        once: true,
                    },
                }
            );
        }
    });
});

onBeforeUnmount(() => {
    animationContext?.revert();
});

const props = defineProps<{
    events?: Event[];
}>();

const { locale } = useLocale();

const fallbackImage = '/img/day_2.png';

// Mock static events for demo purposes
const staticEvents: Event[] = [
    {
        id: 1,
        name: 'Operación Canadá Online',
        name_en: 'Canada Online Day Operations',
        description: 'Participa en una operación internacional conjunta entre IVAO Canadá y la comunidad global.',
        description_en: 'Join an international joint operation between IVAO Canada and the global community.',
        slug: 'canada-online-day',
        image_url: '/img/day_1.png',
        type: EventType.ONLINE_DAY,
        tags: [EventTag.Division],
        pilot_slots_enabled: true,
        atc_slots_enabled: true,
        locations: 'CYUL - CYYZ - CYAD - CYVR',
        starts_at: new Date(2026, 3, 23, 18, 0).toISOString(),
        ends_at: new Date(2026, 3, 23, 23, 59).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
    {
        id: 2,
        name: 'Entrenamiento ATC - ACC Bogotá',
        name_en: 'ATC Training - Bogotá ACC',
        description: 'Sesión de entrenamiento especializada en control de aproximación. Dirigido a todos los niveles.',
        description_en: 'Specialized training session on approach control. Aimed at all levels.',
        slug: 'atc-training-bogota-acc',
        image_url: '/img/day_2.png',
        type: EventType.TRAINING,
        tags: [EventTag.VFR],
        pilot_slots_enabled: false,
        atc_slots_enabled: true,
        locations: 'SKBO ACC',
        starts_at: new Date(2026, 3, 25, 19, 30).toISOString(),
        ends_at: new Date(2026, 3, 25, 22, 0).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
    {
        id: 3,
        name: 'Operación Vuelos de Largo Radio',
        name_en: 'Long-Haul Flight Operation',
        description: 'Operación mensual de vuelos de larga distancia. Aeropuertos: Miami, Nueva York, Ciudad de México.',
        description_en: 'Monthly long-distance flight operation. Airports: Miami, New York, Mexico City.',
        slug: 'long-haul-operation',
        image_url: '/img/day_3.png',
        type: EventType.ONLINE_DAY,
        tags: [EventTag.IFR, EventTag.CrossCountry],
        pilot_slots_enabled: true,
        atc_slots_enabled: true,
        locations: 'KMIA - KJFK - MMMX',
        starts_at: new Date(2026, 3, 27, 16, 0).toISOString(),
        ends_at: new Date(2026, 3, 28, 4, 0).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
    {
        id: 4,
        name: 'Examen de Rating Piloto Comercial',
        name_en: 'Commercial Pilot Rating Exam',
        description: 'Examen teórico y práctico para obtener el rating de piloto comercial. Plazas limitadas.',
        description_en: 'Theoretical and practical exam to obtain commercial pilot rating. Limited slots.',
        slug: 'cpl-exam',
        image_url: '/img/day_1.png',
        type: EventType.EXAM,
        tags: [EventTag.Division],
        pilot_slots_enabled: true,
        atc_slots_enabled: false,
        locations: 'Virtual',
        starts_at: new Date(2026, 4, 2, 20, 0).toISOString(),
        ends_at: new Date(2026, 4, 2, 23, 30).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
    {
        id: 5,
        name: 'Campeonato Regional de Vuelo de Precisión',
        name_en: 'Regional Precision Flight Championship',
        description: 'Competición de precisión aeronáutica. Rutas definidas con criterios de exactitud muy estrictos.',
        description_en: 'Aeronautical precision competition. Defined routes with very strict accuracy criteria.',
        slug: 'precision-championship',
        image_url: '/img/day_2.png',
        type: EventType.RFE,
        tags: [EventTag.VFR, EventTag.IFR],
        pilot_slots_enabled: true,
        atc_slots_enabled: true,
        locations: 'SKBQ - SKSM - SKCC',
        starts_at: new Date(2026, 4, 10, 14, 0).toISOString(),
        ends_at: new Date(2026, 4, 10, 20, 0).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
    {
        id: 6,
        name: 'Sesión Abierta: Vuelos Locales y Training',
        name_en: 'Open Session: Local Flights and Training',
        description: 'Sesión abierta sin restricciones. Ideal para volar localmente, practicar procedimientos y entrenar.',
        description_en: 'Unrestricted open session. Ideal for local flying, practicing procedures, and training.',
        slug: 'open-session-local',
        image_url: '/img/day_3.png',
        type: EventType.TRAINING,
        tags: [EventTag.VFR],
        pilot_slots_enabled: false,
        atc_slots_enabled: false,
        locations: 'Aeropuertos Locales',
        starts_at: new Date(2026, 4, 15, 18, 0).toISOString(),
        ends_at: new Date(2026, 4, 15, 22, 0).toISOString(),
        status: EventStatus.ACTIVE,
        created_by: 1,
    },
];

const featuredEvents = computed(() => (props.events?.length ? props.events.slice(0, 6) : staticEvents));

const labels = computed(() => ({
    title: wTrans('Featured Events'),
    subtitle: wTrans('Join our next online operations and training sessions.'),
    locations: wTrans('All FIRs'),
    details: wTrans('Details'),
    reservation: wTrans('Booking Available'),
    empty: wTrans('No events available right now.'),
}));

function hasReservation(event: Event): boolean {
    return event.pilot_slots_enabled || event.atc_slots_enabled;
}

function getLocalizedName(event: Event): string {
    if (locale.value === 'en') {
        return event.name_en ?? event.name;
    }

    return event.name;
}

function getLocalizedDescription(event: Event): string {
    if (locale.value === 'en') {
        return event.description_en ?? event.description;
    }

    return event.description;
}

function getDateParts(startsAt: string): {
    day: string;
    month: string;
    year: string;
    time: string;
} {
    const date = new Date(startsAt);
    const dateLocale = locale.value === 'en' ? 'en-US' : 'es-CO';

    const day = new Intl.DateTimeFormat(dateLocale, {
        day: '2-digit',
        timeZone: 'UTC',
    }).format(date);
    const month = new Intl.DateTimeFormat(dateLocale, {
        month: 'short',
        timeZone: 'UTC',
    })
        .format(date)
        .replace('.', '')
        .toUpperCase();
    const year = new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        timeZone: 'UTC',
    }).format(date);
    const time = `${new Intl.DateTimeFormat(dateLocale, {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: 'UTC',
    }).format(date)} UTC`;

    return { day, month, year, time };
}
</script>

<template>
    <section ref="sectionRef" class="relative overflow-hidden bg-slate-100 py-16 dark:bg-slate-900 sm:py-20">
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-48 bg-linear-to-b from-slate-300/60 to-transparent dark:from-slate-800/40"
        ></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2 ref="titleRef" class="font-heading text-4xl font-black leading-tight tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">
                    {{ labels.title }}
                </h2>
                <p ref="subtitleRef" class="font-sans mt-6 text-base text-slate-600 dark:text-slate-300 sm:text-lg lg:text-xl">
                    {{ labels.subtitle }}
                </p>
            </div>

            <div
                v-if="featuredEvents.length"
                ref="cardsContainerRef"
                class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
            >
                <article
                    v-for="event in featuredEvents"
                    :key="event.id"
                    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-slate-700/60 dark:bg-slate-800"
                >
                    <div class="relative h-52 overflow-hidden">
                        <img
                            :src="event.image_url ?? fallbackImage"
                            :alt="getLocalizedName(event)"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />

                        <span
                            v-if="hasReservation(event)"
                            class="font-sans absolute top-3 left-3 z-10 rounded-full border border-emerald-300/70 bg-emerald-500/90 px-3 py-1 text-xs font-semibold tracking-wide text-white shadow-sm backdrop-blur-sm dark:border-emerald-200/30 dark:bg-emerald-400/85"
                        >
                            {{ labels.reservation }}
                        </span>

                        <div
                            class="absolute inset-0 bg-linear-to-t from-black/55 via-black/20 to-black/10"
                        ></div>

                        <div class="absolute right-3 bottom-3 rounded-xl bg-white/90 px-3 py-2 backdrop-blur-sm dark:bg-slate-900/90">
                            <p class="font-heading text-2xl leading-none font-black text-slate-900 dark:text-white sm:text-3xl">
                                {{ getDateParts(event.starts_at).day }}
                            </p>
                            <div class="mt-1 flex items-end gap-1">
                                <span class="font-sans text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ getDateParts(event.starts_at).month }}
                                </span>
                                <span class="font-sans text-xs text-slate-500 dark:text-slate-400">
                                    {{ getDateParts(event.starts_at).year }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <h3 class="font-heading line-clamp-2 text-xl leading-tight font-black tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                            {{ getLocalizedName(event) }}
                        </h3>

                        <div class="font-sans flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <span class="inline-flex items-center gap-1.5">
                                <Clock3 class="h-4 w-4" />
                                {{ getDateParts(event.starts_at).time }}
                            </span>
                            <span class="inline-flex min-w-0 max-w-full items-center gap-1.5">
                                <MapPin class="h-4 w-4" />
                                <span class="truncate">{{ event.locations || labels.locations }}</span>
                            </span>
                        </div>

                        <p class="font-sans line-clamp-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ getLocalizedDescription(event) }}
                        </p>

                        <button
                            type="button"
                            class="font-sans inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 transition-colors hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700 sm:w-auto cursor-pointer"
                        >
                            <CalendarClock class="h-4 w-4" />
                            {{ labels.details }}
                            <MoveRight class="h-4 w-4" />
                        </button>
                    </div>
                </article>

                <div class="col-span-full flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="#" class="inline-flex w-full max-w-xs items-center justify-center rounded-xl border border-calendar-outline bg-calendar-surface px-6 py-3 text-sm font-semibold text-white backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-calendar-surface-hover sm:w-auto sm:px-8 sm:py-3.5">
                        <CalendarClock class="h-5 w-5 mr-2" />
                        Calendario
                    </a>
                    <a href="/events" class="inline-flex w-full max-w-xs items-center justify-center gap-2 rounded-xl bg-calendar-primary px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-calendar-primary-hover sm:w-auto sm:px-8 sm:py-3.5">
                        <Eye class="h-5 w-5 mr-2" />
                        Ver más
                    </a>
                </div>

            </div>
            

            <div
                v-else
                class="font-sans mt-10 rounded-2xl border border-dashed border-slate-300 bg-white/70 px-6 py-10 text-center text-slate-600 dark:border-slate-600 dark:bg-slate-800/40 dark:text-slate-400"
            >
                {{ labels.empty }}
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import EventCard from '@/components/landing/EventCard.vue';
import Header from '@/components/landing/Header.vue';
import { useLocale } from '@/composables/useLocale';
import type { Event } from '@/types';

type LandingCopy = {
    badge: string;
    headerText: string;
    title: string;
    description: string;
    primaryCta: string;
    secondaryCta: string;
    localeLabel: string;
    allEvents: string;
    schedule: string;
    location: string;
    noImage: string;
    bookingAvailable: string;
    active: string;
    draft: string;
    cancelled: string;
    finalized: string;
};

const props = defineProps<{
    events: Event[];
}>();

const { locale } = useLocale();

const copy = computed<Record<'es' | 'en', LandingCopy>>(() => ({
    es: {
        badge: 'EVENTOS',
        headerText: 'EVENTOS',
        title: 'Explora nuestros próximos eventos en la división.',
        description:
            'Operaciones, entrenamientos y encuentros especiales reunidos en una sola landing. Cambia entre español e inglés con un clic.',
        primaryCta: 'Ver eventos',
        secondaryCta: 'Cambiar a inglés',
        localeLabel: 'Idioma',
        allEvents: 'Todos los eventos',
        schedule: 'Horario',
        location: 'Ubicación',
        noImage: 'Sin imagen',
        bookingAvailable: 'Reserva disponible',
        active: 'Activo',
        draft: 'Borrador',
        cancelled: 'Cancelado',
        finalized: 'Finalizado',
    },
    en: {
        badge: 'EVENTS',
        headerText: 'EVENTS',
        title: 'Explore our upcoming division events.',
        description:
            'Operations, trainings, and special gatherings in one landing. Switch between Spanish and English with a single click.',
        primaryCta: 'View events',
        secondaryCta: 'Switch to Spanish',
        localeLabel: 'Language',
        allEvents: 'All events',
        schedule: 'Schedule',
        location: 'Location',
        noImage: 'No image',
        bookingAvailable: 'Booking available',
        active: 'Active',
        draft: 'Draft',
        cancelled: 'Cancelled',
        finalized: 'Finalized',
    },
}));

const currentCopy = computed(() => copy.value[locale.value]);

const orderedEvents = computed(() =>
    [...props.events].sort(
        (left, right) =>
            new Date(left.starts_at).getTime() -
            new Date(right.starts_at).getTime(),
    ),
);
</script>

<template>
    <Head :title="currentCopy.title" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-950 text-white"
    >
        <Header brand-tone="dark" :brand-text="currentCopy.headerText" />

        <div class="absolute inset-0">
            <img
                src="/fonodo.jpg"
                alt="IVAO Colombia events background"
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
            class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-5 sm:px-6 lg:px-8"
        >
            <div
                class="flex flex-1 flex-col justify-center py-10 sm:py-14 lg:py-16"
            >
                <div class="max-w-3xl">
                    <p
                        class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-calendar-surface/80 px-3 py-1 text-xs font-semibold tracking-[0.22em] text-white/90 backdrop-blur-md sm:px-4 sm:py-1.5"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-calendar-primary"
                        ></span>
                        {{ currentCopy.badge }}
                    </p>

                    <h1
                        class="mt-5 text-4xl leading-tight font-black tracking-tight text-balance sm:text-5xl lg:text-7xl"
                    >
                        {{ currentCopy.title }}
                    </h1>

                    <p
                        class="mt-5 max-w-2xl text-base leading-relaxed text-white/78 sm:text-lg lg:text-xl"
                    >
                        {{ currentCopy.description }}
                    </p>
                </div>
            </div>

            <div id="events-grid" class="pb-10 sm:pb-14 lg:pb-16">
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <p
                            class="text-sm font-medium tracking-[0.24em] text-white/55 uppercase"
                        >
                            {{ currentCopy.allEvents }}
                        </p>
                        <h2
                            class="mt-2 text-2xl font-black tracking-tight sm:text-3xl"
                        >
                            {{ orderedEvents.length }}
                            {{ currentCopy.allEvents.toLowerCase() }}
                        </h2>
                    </div>

                    <p class="hidden text-sm text-white/55 sm:block">
                        {{ currentCopy.schedule }} + {{ currentCopy.location }}
                    </p>
                </div>

                <div
                    class="dark grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
                >
                    <EventCard
                        v-for="event in orderedEvents"
                        :key="event.id"
                        :event="event"
                        :show-status="true"
                        :show-type="true"
                        :show-tags="true"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

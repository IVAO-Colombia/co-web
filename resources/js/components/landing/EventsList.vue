<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { wTrans } from 'laravel-vue-i18n';
import { CalendarClock, Clock3, MapPin, MoveRight, Eye } from 'lucide-vue-next';
import { computed } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useLocale } from '@/composables/useLocale';
import { getDateParts } from '@/lib/utils';
import home from '@/routes/home';
import type { Event } from '@/types';
import { EventStatus, EventTag, EventType } from '@/types/backend.d';

const props = defineProps<{
    events: Event[];
}>();

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
                },
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
                },
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
                },
            );
        }
    });
});

onBeforeUnmount(() => {
    animationContext?.revert();
});

const { locale } = useLocale();

const fallbackImage = '/img/day_2.png';

function hasReservation(event: Event): boolean {
    return event.pilot_slots_enabled || event.atc_slots_enabled;
}

function getLocalizedName(event: Event): string {
    if (locale.value === 'en') {
        return event.name_en ?? event.name;
    }

    return event.name;
}

function localizedDescription(event: Event): string {
    if (locale.value === 'en') {
        return event.description_en ?? event.description;
    }

    return event.description;
}
</script>

<template>
    <section
        ref="sectionRef"
        class="relative overflow-hidden bg-slate-100 py-16 sm:py-20 dark:bg-slate-900"
    >
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-48 bg-linear-to-b from-slate-300/60 to-transparent dark:from-slate-800/40"
        ></div>

        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2
                    ref="titleRef"
                    class="font-heading text-4xl leading-tight font-black tracking-tight text-slate-900 sm:text-5xl lg:text-6xl dark:text-white"
                >
                    {{ wTrans('Featured Events') }}
                </h2>
                <p
                    ref="subtitleRef"
                    class="mt-6 font-sans text-base text-slate-600 sm:text-lg lg:text-xl dark:text-slate-300"
                >
                    {{
                        wTrans(
                            'Join our next online operations and training sessions.',
                        )
                    }}
                </p>
            </div>

            <div
                v-if="events.length"
                ref="cardsContainerRef"
                class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
            >
                <article
                    v-for="event in events"
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
                            class="absolute top-3 left-3 z-10 rounded-full border border-emerald-300/70 bg-emerald-500/90 px-3 py-1 font-sans text-xs font-semibold tracking-wide text-white shadow-sm backdrop-blur-sm dark:border-emerald-200/30 dark:bg-emerald-400/85"
                        >
                            {{ wTrans('Booking Available') }}
                        </span>

                        <div
                            class="absolute inset-0 bg-linear-to-t from-black/55 via-black/20 to-black/10"
                        ></div>

                        <div
                            class="absolute right-3 bottom-3 rounded-xl bg-white/90 px-3 py-2 backdrop-blur-sm dark:bg-slate-900/90"
                        >
                            <p
                                class="font-heading text-2xl leading-none font-black text-slate-900 sm:text-3xl dark:text-white"
                            >
                                {{ getDateParts(event.starts_at, locale).day }}
                            </p>
                            <div class="mt-1 flex items-end gap-1">
                                <span
                                    class="font-sans text-xs font-bold text-slate-600 dark:text-slate-300"
                                >
                                    {{
                                        getDateParts(event.starts_at, locale)
                                            .month
                                    }}
                                </span>
                                <span
                                    class="font-sans text-xs text-slate-500 dark:text-slate-400"
                                >
                                    {{
                                        getDateParts(event.starts_at, locale)
                                            .year
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <h3
                            class="line-clamp-2 font-heading text-xl leading-tight font-black tracking-tight text-slate-900 sm:text-2xl dark:text-white"
                        >
                            {{ getLocalizedName(event) }}
                        </h3>

                        <div
                            class="flex flex-wrap items-center gap-x-4 gap-y-2 font-sans text-sm text-slate-600 dark:text-slate-300"
                        >
                            <span class="inline-flex items-center gap-1.5">
                                <Clock3 class="h-4 w-4" />
                                {{ getDateParts(event.starts_at, locale).time }}
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
                            {{ localizedDescription(event) }}
                        </p>

                        <button
                            type="button"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-2 font-sans text-sm font-semibold text-slate-800 transition-colors hover:bg-slate-100 sm:w-auto dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700"
                        >
                            <CalendarClock class="h-4 w-4" />
                            {{ wTrans('Details') }}
                            <MoveRight class="h-4 w-4" />
                        </button>
                    </div>
                </article>

                <div
                    class="col-span-full flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <a
                        href="#"
                        class="inline-flex w-full max-w-xs items-center justify-center rounded-xl border border-calendar-outline bg-calendar-surface px-6 py-3 text-sm font-semibold text-white backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-calendar-surface-hover sm:w-auto sm:px-8 sm:py-3.5"
                    >
                        <CalendarClock class="mr-2 h-5 w-5" />
                        Calendario
                    </a>
                    <Link
                        :href="home.events()"
                        class="inline-flex w-full max-w-xs items-center justify-center gap-2 rounded-xl bg-calendar-primary px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-calendar-primary-hover sm:w-auto sm:px-8 sm:py-3.5"
                    >
                        <Eye class="mr-2 h-5 w-5" />
                        Ver más
                    </Link>
                </div>
            </div>

            <div
                v-else
                class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white/70 px-6 py-10 text-center font-sans text-slate-600 dark:border-slate-600 dark:bg-slate-800/40 dark:text-slate-400"
            >
                {{ wTrans('No events available right now.') }}
            </div>
        </div>
    </section>
</template>

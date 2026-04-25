<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { wTrans } from 'laravel-vue-i18n';
import { Eye } from 'lucide-vue-next';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import EventCard from '@/components/landing/EventCard.vue';
import type { Event } from '@/types';
import home from '@/routes/home';

const props = defineProps<{
    events: Event[];
}>();

const { events } = props;

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
                <EventCard
                    v-for="event in events"
                    :key="event.id"
                    :event="event"
                />

                <div
                    class="col-span-full flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <!-- <a
                        href="#"
                        class="inline-flex w-full max-w-xs items-center justify-center rounded-xl border border-calendar-outline bg-calendar-surface px-6 py-3 text-sm font-semibold text-white backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-calendar-surface-hover sm:w-auto sm:px-8 sm:py-3.5"
                    >
                        <CalendarClock class="mr-2 h-5 w-5" />
                        Calendario
                    </a> -->
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

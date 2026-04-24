<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { transChoice } from 'laravel-vue-i18n';
import EventCard from '@/components/landing/EventCard.vue';
import Header from '@/components/landing/Header.vue';
import type { Event } from '@/types';

defineProps<{
    events: Event[];
}>();
</script>

<template>
    <Head :title="$t('Explore our upcoming division events')" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white"
    >
        <Header
            :brand-text="$t('Events').toLocaleUpperCase()"
        />

    <div class="absolute inset-0">
        <img
            src="/fonodo.jpg"
            alt="IVAO Colombia events background"
            class="h-full w-full object-cover opacity-75 dark:opacity-35"
        />
        <div
            class="absolute inset-0 bg-linear-to-b from-white/40 via-slate-100/70 to-slate-50 dark:from-black/35 dark:via-slate-950/75 dark:to-slate-950"
        ></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(13,44,153,0.18),transparent_45%)] dark:bg-[radial-gradient(circle_at_top,rgba(47,69,255,0.28),transparent_45%)]"
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
                        class="inline-flex items-center gap-2 rounded-full border border-slate-300/70 bg-white/80 px-3 py-1 text-xs font-semibold tracking-[0.22em] text-slate-700 backdrop-blur-md sm:px-4 sm:py-1.5 dark:border-white/15 dark:bg-calendar-surface/80 dark:text-white/90"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-calendar-primary"
                        ></span>
                        {{ $t('Events') }}
                    </p>

                    <h1
                        class="mt-5 text-4xl leading-tight font-black tracking-tight text-balance text-slate-900 sm:text-5xl lg:text-7xl dark:text-white"
                    >
                        {{ $t('Explore our upcoming division events') }}
                    </h1>

                    <p
                        class="mt-5 max-w-2xl text-base leading-relaxed text-slate-700 sm:text-lg lg:text-xl dark:text-white/78"
                    >
                        {{
                            $t(
                                'Join our next online operations and training sessions.',
                            )
                        }}
                    </p>
                </div>
            </div>

            <div id="events-grid" class="pb-10 sm:pb-14 lg:pb-16">
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <h2
                            class="mt-2 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl dark:text-white"
                        >
                            {{ events.length }}
                            {{
                                transChoice(
                                    'Event|Events',
                                    events.length,
                                ).toLowerCase()
                            }}
                        </h2>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
                >
                    <EventCard
                        v-for="event in events"
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

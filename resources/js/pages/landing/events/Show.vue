<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Header from '@/components/landing/Header.vue';
import type { EventDetail } from '@/types';

defineProps<{
    event: EventDetail;
}>();
</script>
<template>
    <Head :title="$t('Explore our upcoming division events')" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-950 text-white"
    >
        <Header
            brand-tone="dark"
            :brand-text="$t('Events').toLocaleUpperCase()"
        />

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
                        {{ $t('Events') }}
                    </p>

                    <h1
                        class="mt-5 text-4xl leading-tight font-black tracking-tight text-balance sm:text-5xl lg:text-7xl"
                    >
                        {{ $t('Explore our upcoming division events') }}
                    </h1>

                    <p
                        class="mt-5 max-w-2xl text-base leading-relaxed text-white/78 sm:text-lg lg:text-xl"
                    >
                        {{
                            $t(
                                'Join our next online operations and training sessions.',
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

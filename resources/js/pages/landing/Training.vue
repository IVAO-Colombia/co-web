<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowUpRight,
    ClipboardCheck,
    GraduationCap,
    Radio,
} from 'lucide-vue-next';

import Header from '@/components/landing/Header.vue';
import trainings from '@/routes/dashboard/trainings';

const examStatusUrl = 'https://www.ivao.aero/training/exam/status.asp';

const trainingImage =
    'https://i.pinimg.com/1200x/d8/ab/8c/d8ab8ce3dcba0fb459cb60a4c7ed4e98.jpg';
</script>

<template>
    <Head title="Training | IVAO Colombia" />

    <main
        class="min-h-screen bg-slate-50 text-slate-950 transition-colors duration-500 dark:bg-[#05070d] dark:text-white"
    >
        <Header />

        <section
            class="relative overflow-hidden bg-slate-50 transition-colors duration-500 dark:bg-[#05070d]"
        >
            <div
                class="mx-auto grid min-h-[calc(100vh-100px)] max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16 lg:px-8 lg:py-20"
            >
                <div
                    class="group relative order-2 h-[460px] overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-200 shadow-2xl shadow-slate-900/10 transition-colors lg:order-1 lg:h-[650px] dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-black/40"
                >
                    <img
                        :src="trainingImage"
                        alt="IVAO Colombia Training"
                        class="h-full w-full object-cover object-center transition duration-1000 group-hover:scale-[1.03]"
                    />

                    <div
                        class="absolute inset-0 bg-linear-to-t from-white/65 via-transparent to-white/10 dark:hidden"
                    />

                    <div
                        class="absolute inset-0 hidden bg-linear-to-t from-[#05070d]/90 via-[#05070d]/10 to-[#05070d]/20 dark:block"
                    />

                    <div
                        class="absolute inset-0 bg-blue-600/0 transition duration-700 group-hover:bg-blue-600/[0.05]"
                    />

                    <div
                        class="absolute right-6 bottom-6 left-6 flex items-end justify-between"
                    >
                        <div>
                            <div
                                class="mb-2 flex items-center gap-2 text-[9px] font-bold tracking-[0.25em] text-white/70 uppercase"
                            >
                                <span
                                    class="size-1.5 rounded-full bg-blue-400"
                                />

                                IVAO COLOMBIA
                            </div>

                            <p class="text-sm font-semibold text-white">
                                {{ $t('Training Department') }}
                            </p>
                        </div>

                        <div
                            class="hidden rounded-full border border-white/20 bg-black/20 px-3 py-1.5 text-[9px] font-semibold tracking-[0.2em] text-white/70 uppercase backdrop-blur-md sm:block"
                        >
                            FIR SKED
                        </div>
                    </div>
                </div>

                <div class="order-1 flex flex-col justify-center lg:order-2">
                    <div
                        class="mb-8 inline-flex w-fit items-center gap-3 rounded-full border border-slate-300/80 bg-white/70 px-4 py-2 text-[10px] font-bold tracking-[0.22em] text-slate-600 uppercase shadow-sm backdrop-blur-md dark:border-white/10 dark:bg-white/[0.04] dark:text-white/55 dark:shadow-none"
                    >
                        <span
                            class="flex size-6 items-center justify-center rounded-full bg-blue-600/10 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400"
                        >
                            <GraduationCap class="size-3.5" />
                        </span>

                        {{ $t('Requests & Training') }}
                    </div>

                    <div
                        class="mb-6 flex items-center gap-3 text-[10px] font-bold tracking-[0.22em] text-blue-600 uppercase dark:text-blue-400"
                    >
                        <span class="h-px w-8 bg-blue-600 dark:bg-blue-400" />

                        IVAO Colombia

                        <span class="text-slate-400 dark:text-white/20">
                            /
                        </span>

                        {{ $t('Training Department') }}
                    </div>

                    <!-- Main title -->
                    <h1
                        class="font-heading text-[4rem] leading-[0.88] font-black tracking-[-0.055em] sm:text-[5rem] lg:text-[6.5rem]"
                    >
                        <span class="block text-slate-950 dark:text-white">
                            {{ $t('Train') }}
                        </span>

                        <span class="block text-slate-400 dark:text-white/30">
                            {{ $t('Learn') }}
                        </span>

                        <span class="block text-blue-600 dark:text-blue-400">
                            {{ $t('Control') }}
                        </span>
                    </h1>

                    <p
                        class="mt-8 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg dark:text-white/50"
                    >
                        {{
                            $t(
                                'Develop your knowledge, improve your air traffic control skills and prepare for your next qualification with the support of IVAO Colombia instructors.',
                            )
                        }}
                    </p>

                    <div
                        class="mt-10 grid max-w-xl grid-cols-3 border-y border-slate-200 py-5 dark:border-white/10"
                    >
                        <div
                            class="border-r border-slate-200 pr-4 dark:border-white/10"
                        >
                            <span
                                class="text-[10px] font-bold tracking-[0.2em] text-blue-600 dark:text-blue-400"
                            >
                                01
                            </span>

                            <h3 class="mt-2 text-sm font-bold sm:text-base">
                                {{ $t('Learn') }}
                            </h3>

                            <p
                                class="mt-1 text-[9px] tracking-[0.08em] text-slate-400 uppercase dark:text-white/30"
                            >
                                {{ $t('Build knowledge') }}
                            </p>
                        </div>

                        <div
                            class="border-r border-slate-200 px-4 dark:border-white/10"
                        >
                            <span
                                class="text-[10px] font-bold tracking-[0.2em] text-blue-600 dark:text-blue-400"
                            >
                                02
                            </span>

                            <h3 class="mt-2 text-sm font-bold sm:text-base">
                                {{ $t('Practice') }}
                            </h3>

                            <p
                                class="mt-1 text-[9px] tracking-[0.08em] text-slate-400 uppercase dark:text-white/30"
                            >
                                {{ $t('Train with us') }}
                            </p>
                        </div>

                        <div class="pl-4">
                            <span
                                class="text-[10px] font-bold tracking-[0.2em] text-blue-600 dark:text-blue-400"
                            >
                                03
                            </span>

                            <h3 class="mt-2 text-sm font-bold sm:text-base">
                                {{ $t('Qualify') }}
                            </h3>

                            <p
                                class="mt-1 text-[9px] tracking-[0.08em] text-slate-400 uppercase dark:text-white/30"
                            >
                                {{ $t('Get your rating') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-5">
                        <Link
                            :href="trainings.index.url()"
                            class="inline-flex items-center gap-3 rounded-full bg-[#1d4ed8] px-6 py-3 text-sm font-semibold text-white transition-all hover:gap-5 hover:bg-[#1e40af] focus:ring-4 focus:ring-blue-600/20 focus:outline-none"
                        >
                            {{ $t('Start training') }}

                            <ArrowUpRight class="size-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section
            class="relative border-t border-slate-200 bg-white py-24 transition-colors lg:py-32 dark:border-white/[0.06] dark:bg-[#05070d]"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Section heading -->
                <div
                    class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end"
                >
                    <div class="max-w-2xl">
                        <div
                            class="flex items-center gap-3 text-[10px] font-bold tracking-[0.25em] text-blue-600 uppercase dark:text-blue-400"
                        >
                            <Radio class="size-3.5" />

                            IVAO Colombia
                        </div>

                        <h2
                            class="mt-5 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl"
                        >
                            {{ $t('Choose your path.') }}
                        </h2>
                    </div>

                    <p
                        class="max-w-md text-sm leading-relaxed text-slate-500 dark:text-white/40"
                    >
                        {{
                            $t(
                                'Start your training journey or continue towards your next controller qualification.',
                            )
                        }}
                    </p>
                </div>

                <!-- Cards -->
                <div class="mt-16 grid gap-5 lg:grid-cols-2">
                    <article
                        class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm transition duration-500 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-950/5 sm:p-10 dark:border-white/[0.08] dark:bg-white/[0.025] dark:shadow-none dark:hover:border-blue-500/30 dark:hover:bg-white/[0.04]"
                    >
                        <!-- Decoration -->
                        <div
                            class="absolute -top-20 -right-20 size-64 rounded-full bg-blue-500/10 blur-3xl transition duration-700 group-hover:bg-blue-500/20"
                        />

                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="flex size-14 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600 dark:border-blue-500/10 dark:bg-blue-500/10 dark:text-blue-400"
                            >
                                <GraduationCap class="size-6" />
                            </div>

                            <div class="mt-12">
                                <p
                                    class="text-[10px] font-bold tracking-[0.25em] text-blue-600 uppercase dark:text-blue-400"
                                >
                                    {{ $t('Start your journey') }}
                                </p>

                                <h3
                                    class="mt-3 text-3xl font-black tracking-tight"
                                >
                                    {{ $t('Request Training') }}
                                </h3>

                                <p
                                    class="mt-5 max-w-xl text-sm leading-relaxed text-slate-500 dark:text-white/45"
                                >
                                    {{
                                        $t(
                                            'Request a training session with our instructors and begin your training process towards your next controller qualification.',
                                        )
                                    }}
                                </p>
                            </div>

                            <Link
                                :href="trainings.index.url()"
                                class="mt-10 inline-flex items-center gap-3 rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition-all hover:gap-5 hover:bg-blue-600 dark:bg-white dark:text-slate-950 dark:hover:bg-blue-500 dark:hover:text-white"
                            >
                                {{ $t('Start training') }}

                                <ArrowUpRight class="size-4" />
                            </Link>
                        </div>
                    </article>

                    <article
                        class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm transition duration-500 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-950/5 sm:p-10 dark:border-white/[0.08] dark:bg-white/[0.025] dark:shadow-none dark:hover:border-indigo-500/30 dark:hover:bg-white/[0.04]"
                    >
                        <!-- Decoration -->
                        <div
                            class="absolute -top-20 -right-20 size-64 rounded-full bg-indigo-500/10 blur-3xl transition duration-700 group-hover:bg-indigo-500/20"
                        />

                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="flex size-14 items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-500/10 dark:bg-indigo-500/10 dark:text-indigo-400"
                            >
                                <ClipboardCheck class="size-6" />
                            </div>

                            <div class="mt-12">
                                <p
                                    class="text-[10px] font-bold tracking-[0.25em] text-indigo-600 uppercase dark:text-indigo-400"
                                >
                                    {{ $t('Ready for the next step?') }}
                                </p>

                                <h3
                                    class="mt-3 text-3xl font-black tracking-tight"
                                >
                                    {{ $t('Request an Exam') }}
                                </h3>

                                <p
                                    class="mt-5 max-w-xl text-sm leading-relaxed text-slate-500 dark:text-white/45"
                                >
                                    {{
                                        $t(
                                            'Review the official IVAO exam requirements and prepare for your theoretical and practical evaluation.',
                                        )
                                    }}
                                </p>
                            </div>

                            <a
                                :href="examStatusUrl"
                                target="_blank"
                                rel="noreferrer noopener"
                                class="mt-10 inline-flex items-center gap-3 rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition-all hover:gap-5 hover:bg-indigo-600 dark:bg-white dark:text-slate-950 dark:hover:bg-indigo-500 dark:hover:text-white"
                            >
                                {{ $t('Request exam') }}

                                <ArrowUpRight class="size-4" />
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section
            class="border-t border-slate-200 bg-slate-50 py-20 dark:border-white/[0.06] dark:bg-[#05070d]"
        >
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div
                    class="flex flex-col items-start justify-between gap-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10 lg:flex-row lg:items-center dark:border-white/[0.08] dark:bg-white/[0.025]"
                >
                    <div>
                        <div
                            class="flex items-center gap-3 text-[10px] font-bold tracking-[0.25em] text-blue-600 uppercase dark:text-blue-400"
                        >
                            <Radio class="size-3.5" />

                            IVAO Colombia
                        </div>

                        <h2
                            class="mt-4 text-2xl font-black tracking-tight sm:text-3xl"
                        >
                            {{ $t('Ready to take the next step?') }}
                        </h2>

                        <p
                            class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-500 dark:text-white/40"
                        >
                            {{
                                $t(
                                    'Explore our training resources and start building your experience as an IVAO Colombia controller.',
                                )
                            }}
                        </p>
                    </div>

                    <Link
                        :href="trainings.index.url()"
                        class="inline-flex shrink-0 items-center gap-3 rounded-full bg-[#1d4ed8] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#1e40af]"
                    >
                        {{ $t('Request training') }}

                        <ArrowUpRight class="size-4" />
                    </Link>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, GraduationCap } from 'lucide-vue-next';
import Header from '@/components/landing/Header.vue';
import trainings from '@/routes/dashboard/trainings';

const examStatusUrl = 'https://www.ivao.aero/training/exam/status.asp';
</script>

<template>
    <Head title="Training | IVAO Colombia" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white"
    >
        <Header :brand-text="$t('Training').toLocaleUpperCase()" />

        <div class="absolute inset-0">
            <img
                src="/img/bog_torre.jpg"
                alt="IVAO Colombia Training"
                class="h-full w-full object-cover opacity-90 dark:opacity-35"
            />

            <!-- Light overlays -->
            <div
                class="to-slate-25 absolute inset-0 bg-linear-to-b from-white/35 via-slate-100/50 dark:hidden"
            ></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(13,44,153,0.18),transparent_45%)] dark:hidden"
            ></div>

            <!-- Dark overlays -->
            <div
                class="absolute inset-0 hidden bg-linear-to-b from-black/35 via-slate-950/75 to-slate-950 dark:block"
            ></div>
            <div
                class="absolute inset-0 hidden bg-[radial-gradient(circle_at_top,rgba(47,69,255,0.28),transparent_45%)] dark:block"
            ></div>
        </div>

        <div
            class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8"
        >
            <div class="mt-6">
                <Link
                    href="/"
                    class="inline-flex items-center space-x-3 rounded-full border border-slate-300/80 bg-white/70 px-4 py-1.5 font-heading text-xs font-semibold tracking-[0.2em] text-slate-800 uppercase backdrop-blur-md transition hover:bg-white dark:border-white/20 dark:bg-white/10 dark:text-white/85 dark:hover:bg-white/20"
                >
                    <GraduationCap />
                    <span>{{ $t('Requests & Training') }}</span>
                </Link>
            </div>

            <div class="py-12 sm:py-16 lg:py-20">
                <h1
                    class="font-heading text-4xl leading-tight font-black tracking-tight sm:text-5xl lg:text-6xl"
                >
                    {{ $t('Training Center') }}
                </h1>

                <p
                    class="mt-6 max-w-3xl text-base leading-relaxed text-slate-700 sm:text-lg dark:text-white/80"
                >
                    {{
                        $t(
                            'Our division has an excellent team of instructors, some with real-world experience, all doing their best to help you learn how to take over flight control and safely guide aircraft.',
                        )
                    }}
                </p>
            </div>

            <div class="grid gap-6 pb-16 lg:grid-cols-2">
                <div
                    class="flex flex-col rounded-2xl border border-slate-300/70 bg-white/75 p-6 backdrop-blur-sm sm:p-8 dark:border-white/12 dark:bg-black/25"
                >
                    <h2 class="text-2xl font-black tracking-tight sm:text-3xl">
                        {{ $t('Request Training') }}
                    </h2>

                    <p
                        class="mt-4 flex-1 text-sm leading-relaxed text-slate-700 sm:text-base dark:text-white/78"
                    >
                        {{
                            $t(
                                "It is possible to request initial controller training or start studying by reading the IVAO Wiki, which will help you understand how the network works and explain topics such as flight rules, airspaces, phraseology, etc. IVAO Colombia's training sessions have some specificities which are listed in the IVAO Colombia Wiki. For advanced controller qualifications (ADC, APC, ACC, ...), the same resources will help you improve your knowledge for the theoretical exam. Our team of instructors will be happy to help you put your knowledge into practice and give you some tips before requesting your practical exam.",
                            )
                        }}
                    </p>

                    <a
                        :href="trainings.index.url()"
                        class="mt-6 inline-flex w-fit items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-white/85"
                    >
                        <span>{{ $t('Request training') }}</span>
                        <ArrowRight class="size-4" />
                    </a>
                </div>

                <div
                    class="flex flex-col rounded-2xl border border-slate-300/70 bg-white/75 p-6 backdrop-blur-sm sm:p-8 dark:border-white/12 dark:bg-black/25"
                >
                    <h2 class="text-2xl font-black tracking-tight sm:text-3xl">
                        {{ $t('Request Exam') }}
                    </h2>

                    <p
                        class="mt-4 flex-1 text-sm leading-relaxed text-slate-700 sm:text-base dark:text-white/78"
                    >
                        {{
                            $t(
                                "Access the official IVAO page for exam briefings and carefully read the corresponding exam briefing document. IVAO Colombia's exams have some specificities which are listed in the IVAO Colombia Wiki. The theoretical part is always carried out before the practical one, and if your knowledge is not sufficient, you will fail the exam, to ensure you have the necessary skills to pass the practical part of the exam.",
                            )
                        }}
                    </p>

                    <a
                        :href="examStatusUrl"
                        target="_blank"
                        rel="noreferrer noopener"
                        class="mt-6 inline-flex w-fit items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-white/85"
                    >
                        <span>{{ $t('Request exam') }}</span>
                        <ArrowRight class="size-4" />
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

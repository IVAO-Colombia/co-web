<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Header from '@/components/landing/Header.vue';
import { BookOpen } from 'lucide-vue-next';

gsap.registerPlugin(ScrollTrigger);

const heroRef = ref<HTMLElement | null>(null);
const missionRef = ref<HTMLElement | null>(null);
const imageRef = ref<HTMLElement | null>(null);

onMounted(() => {
    const targets = [heroRef.value, missionRef.value, imageRef.value].filter(
        Boolean,
    );

    gsap.from(targets, {
        opacity: 0,
        y: 40,
        duration: 1,
        ease: 'power3.out',
        stagger: 0.15,
        scrollTrigger: {
            trigger: heroRef.value,
            start: 'top 80%',
        },
    });

    gsap.to('.parallax-bg', {
        yPercent: 12,
        ease: 'none',
        scrollTrigger: {
            trigger: heroRef.value,
            start: 'top top',
            end: 'bottom top',
            scrub: true,
        },
    });
});

</script>

<template>
    <Head title="About IVAO Colombia" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white"
    >
        <Header brand-tone="auto" />

        <div class="absolute inset-0 parallax-bg">
            <!-- Light image -->
            <img
                src="/about_day.jpg"
                alt="About IVAO Colombia"
                class="h-full w-full object-cover opacity-90 dark:hidden"
            />

            <!-- Dark image -->
            <img
                src="/about_nigth.jpg"
                alt="About IVAO Colombia night"
                class="hidden h-full w-full object-cover opacity-35 dark:block"
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
                    <BookOpen />
                    <span>{{ $t('History') }}</span>
                </Link>
            </div>

            <div ref="heroRef" class="flex flex-1 items-center py-12 sm:py-16 lg:py-20">
                <div class="grid w-full gap-10 lg:grid-cols-12 lg:gap-12">
                    <div class="lg:col-span-7">
                        <h1
                            class="font-heading text-4xl leading-tight font-black tracking-tight sm:text-5xl lg:text-6xl"
                        >
                            {{ $t('The History of IVAO Colombia') }}
                        </h1>

                        <p
                            class="mt-6 max-w-2xl text-base leading-relaxed text-slate-700 sm:text-lg dark:text-white/80"
                        >
                            {{
                                $t(
                                    'IVAO Colombia is the national division of IVAO (International Virtual Aviation Organisation), a global online flight simulation network that brings together aviation enthusiasts from around the world. Deeply rooted in the country’s rich aviation culture, IVAO Colombia is dedicated to offering its members a realistic and authentic experience, promoting flight simulation and virtual air traffic control over Colombian skies. From the Andes to the Caribbean, from the Pacific to the Amazon, join us on this exciting journey through Colombia’s virtual aviation.',
                                )
                            }}
                        </p>
                    </div>
                    <div ref="imageRef" class="lg:col-span-5 w-full ">
                        <div class="mt-8">
                            <img src="/image_about.jpg" alt="About IVAO Colombia" class="rounded-2xl border border-slate-300/70 bg-white/75 object-cover backdrop-blur-sm dark:border-white/12 dark:bg-black/25 hover:transform hover:scale-105 hover:duration-200"  />
                        </div>
                    </div>

                    <div class="lg:col-span-5"></div>
                </div>
            </div>

            <div ref="missionRef" id="our-mission" class="pb-10 sm:pb-14">
                <div class="max-w-4xl rounded-2xl border border-slate-300/70 bg-white/75 p-6 backdrop-blur-sm sm:p-8 dark:border-white/12 dark:bg-black/25">
                    <h2 class="text-2xl font-black tracking-tight sm:text-3xl">{{ $t('Our Beginnings') }}</h2>
                    <p class="mt-4 text-sm leading-relaxed text-slate-700 sm:text-base dark:text-white/78">
                        Impulsar la aviación virtual en Colombia con profesionalismo, respeto y pasión,
                        construyendo experiencias que inspiren a nuevos miembros y fortalezcan a toda la división.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

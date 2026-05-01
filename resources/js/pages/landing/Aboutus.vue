<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { BookOpen } from 'lucide-vue-next';
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import Header from '@/components/landing/Header.vue';

gsap.registerPlugin(ScrollTrigger);

const heroRef = ref<HTMLElement | null>(null);
const missionRef = ref<HTMLElement | null>(null);
const imageRef = ref<HTMLElement | null>(null);
const lightboxRef = ref<HTMLElement | null>(null);
const selectedImage = ref<string | null>(null);

const openImage = async (imageSource: string): Promise<void> => {
    selectedImage.value = imageSource;

    await nextTick();

    if (lightboxRef.value) {
        gsap.fromTo(
            lightboxRef.value,
            { opacity: 0 },
            { opacity: 1, duration: 0.2, ease: 'power2.out' },
        );

        const lightboxImage = lightboxRef.value.querySelector('img');

        if (lightboxImage) {
            gsap.fromTo(
                lightboxImage,
                { scale: 0.92, y: 20 },
                { scale: 1, y: 0, duration: 0.35, ease: 'power3.out' },
            );
        }
    }
};

const closeImage = (): void => {
    selectedImage.value = null;
};

const handleKeydown = (event: KeyboardEvent): void => {
    if (event.key === 'Escape') {
        closeImage();
    }
};

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

    window.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <Head title="About IVAO Colombia" />

    <section
        class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white"
    >
        <Header brand-tone="auto" />

        <div class="parallax-bg absolute inset-0">
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

            <div
                ref="heroRef"
                class="flex flex-1 items-center py-12 sm:py-16 lg:py-20"
            >
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
                    <div ref="imageRef" class="w-full lg:col-span-5">
                        <div class="mt-8">
                            <img
                                src="/image_about.jpg"
                                alt="About IVAO Colombia"
                                class="rounded-2xl border border-slate-300/70 bg-white/75 object-cover backdrop-blur-sm hover:scale-105 hover:transform hover:duration-200 dark:border-white/12 dark:bg-black/25"
                            />
                        </div>
                    </div>

                    <div class="lg:col-span-5"></div>
                </div>
            </div>

            <div ref="missionRef" id="our-mission" class="pb-10 sm:pb-14">
                <div
                    class="max-w-4xl rounded-2xl border border-slate-300/70 bg-white/75 p-6 backdrop-blur-sm sm:p-8 dark:border-white/12 dark:bg-black/25"
                >
                    <h2 class="text-2xl font-black tracking-tight sm:text-3xl">
                        {{ $t('Our Beginnings') }}
                    </h2>
                    <p
                        class="mt-4 text-sm leading-relaxed text-slate-700 sm:text-base dark:text-white/78"
                    >
                        {{
                            $t(
                                'Founded in 2000 by Victor Hugo Martínez, IVAO Colombia was created with the vision of bringing high-quality flight simulation to a growing community, providing a space for learning and connecting virtual pilots and air traffic controllers across the country. At that time, organized online air traffic in Colombia was practically non-existent, making this initiative a pioneering step for the region.With an initial group of around 30 official members, the Colombian division began to take shape, driven by passion and commitment to the simulation community. After months of development and coordination, the first official IVAO Colombia website was launched on July 11, 2006, marking an important milestone in the division’s growth and digital presence.Since then, IVAO Colombia has continued to evolve, strengthening its community, improving training standards, and promoting realistic flight simulation operations. Today, it stands as a recognized and respected division within the IVAO network, representing the dedication and enthusiasm of its members.',
                            )
                        }}
                    </p>
                </div>
            </div>

            <div class="ite grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <button
                    type="button"
                    class="group flex flex-col text-left"
                    @click="openImage('/modernization_2006.png')"
                >
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        2006
                    </p>
                    <img
                        src="/modernization_2006.png"
                        alt="2006"
                        class="modernization-image h-64 w-full rounded-lg border border-slate-300/70 object-cover transition-transform duration-300 group-hover:scale-[1.02] dark:border-white/12"
                    />
                </button>
                <button
                    type="button"
                    class="group flex flex-col text-left"
                    @click="openImage('/modernization_2017.png')"
                >
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        2017
                    </p>
                    <img
                        src="/modernization_2017.png"
                        alt="2017"
                        class="modernization-image h-64 w-full rounded-lg border border-slate-300/70 object-cover transition-transform duration-300 group-hover:scale-[1.02] dark:border-white/12"
                    />
                </button>
                <button
                    type="button"
                    class="group flex flex-col text-left"
                    @click="openImage('/modernization_2021.png')"
                >
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        2021
                    </p>
                    <img
                        src="/modernization_2021.png"
                        alt="2021"
                        class="modernization-image h-64 w-full rounded-lg border border-slate-300/70 object-cover transition-transform duration-300 group-hover:scale-[1.02] dark:border-white/12"
                    />
                </button>
                <button
                    type="button"
                    class="group flex flex-col text-left"
                    @click="openImage('/modernization_2024.png')"
                >
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        2024
                    </p>
                    <img
                        src="/modernization_2024.png"
                        alt="2024"
                        class="modernization-image h-64 w-full rounded-lg border border-slate-300/70 object-cover transition-transform duration-300 group-hover:scale-[1.02] dark:border-white/12"
                    />
                </button>
                <button
                    type="button"
                    class="group flex flex-col text-left"
                    @click="openImage('/modernization_2026.png')"
                >
                    <p class="text-lg font-bold text-slate-800 dark:text-white">
                        2026
                    </p>
                    <img
                        src="/modernization_2026.png"
                        alt="2026"
                        class="modernization-image h-64 w-full rounded-lg border border-slate-300/70 object-cover transition-transform duration-300 group-hover:scale-[1.02] dark:border-white/12"
                    />
                </button>
            </div>

            <Teleport to="body">
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="selectedImage"
                        ref="lightboxRef"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 px-4 py-8 backdrop-blur-sm"
                        @click.self="closeImage"
                    >
                        <div class="relative max-h-[90vh] w-full max-w-6xl">
                            <button
                                type="button"
                                class="absolute -top-12 right-0 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20"
                                @click="closeImage"
                            >
                                Cerrar
                            </button>
                            <img
                                :src="selectedImage"
                                alt="Modernización ampliada"
                                class="max-h-[90vh] w-full rounded-2xl object-contain shadow-2xl"
                            />
                        </div>
                    </div>
                </Transition>
            </Teleport>
        </div>
    </section>
</template>

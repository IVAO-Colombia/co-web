<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ArrowUpRight, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import EventsList from '@/components/landing/EventsList.vue';
import Header from '@/components/landing/Header.vue';
import WhazzupFlights from '@/components/landing/WhazzupFlights.vue';
import { useAppearance } from '@/composables/useAppearance';
import type { Event } from '@/types';

type HeroSlide = {
    eyebrow: string;
    title: string;
    description: string;
    tagline: string;
    lightImage: string;
    darkImage: string;
    buttontextone?: string;
    urlone?: string;
    buttontexttwo?: string;
    urltwo?: string;
};

defineProps<{
    events: Event[];
}>();

const slides: HeroSlide[] = [
    {
        eyebrow: "WELCOME TO DIVISION'S IVAO COLOMBIA",
        title: 'The skies of Colombia, now in your hands',
        description:
            "Join the world's largest flight simulation community from Colombia. More than 1,180 local members are connecting airports, routes, and virtual skies every day.",
        tagline: 'Start your flight',
        lightImage: '/img/day_1.png',
        darkImage: '/img/nigth_1.png',
        buttontextone: 'Getting Started',
        urlone: 'https://ivao.aero/members/person/register.htm',
        buttontexttwo: 'Get Started Now',
        urltwo: '#',
    },
    {
        eyebrow: 'ATC TRAINING',
        title: 'You are the voice that directs traffic<br>in the skies of Colombia',
        description:
            'From flight clearance to air traffic control, train using real-world procedures and become part of the team that keeps Colombia’s airspace organized and safe.',
        tagline: 'ATCO career',
        lightImage: '/img/day_2.png',
        darkImage: '/img/nigth_2.png',
        buttontextone: 'Request Training',
        urlone: '#',
        buttontexttwo: 'Join ATC Now',
        urltwo: 'https://ivao.aero/members/person/register.htm',
    },
    {
        eyebrow: 'PILOTS',
        title: 'Colombian virtual aviation has a new home<br>for pilots of all levels',
        description:
            ' Fly real-world routes over Colombia and around the globe, communicate with air traffic controllers over the radio, and experience every takeoff as if it were your first. The runway awaits you.',
        tagline: 'Start your journey',
        lightImage: '/img/day_3.png',
        darkImage: '/img/nigth_3.png',
        buttontextone: 'Request Training',
        urlone: '#',
        buttontexttwo: 'Join IVAO Now',
        urltwo: 'https://ivao.aero/members/person/register.htm',
    },
];

const { resolvedAppearance } = useAppearance();

const currentSlideIndex = ref(0);
const heroImageRef = ref<HTMLElement | null>(null);
const heroContentRef = ref<HTMLElement | null>(null);

let autoPlayTimer: ReturnType<typeof setInterval> | null = null;
let animationContext: gsap.Context | null = null;

const currentSlide = computed(() => slides[currentSlideIndex.value]);
const currentImage = computed(() =>
    resolvedAppearance.value === 'dark'
        ? currentSlide.value.darkImage
        : currentSlide.value.lightImage,
);

function nextSlide(): void {
    currentSlideIndex.value = (currentSlideIndex.value + 1) % slides.length;
}

function previousSlide(): void {
    currentSlideIndex.value =
        (currentSlideIndex.value - 1 + slides.length) % slides.length;
}

function goToSlide(index: number): void {
    currentSlideIndex.value = index;
}

function restartAutoPlay(): void {
    if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
    }

    autoPlayTimer = setInterval(() => {
        nextSlide();
    }, 7000);
}

watch(currentSlideIndex, () => {
    if (!heroImageRef.value || !heroContentRef.value) {
        return;
    }

    gsap.fromTo(
        heroImageRef.value,
        { opacity: 0.65, scale: 1.03 },
        { opacity: 1, scale: 1, duration: 0.7, ease: 'power2.out' },
    );

    gsap.fromTo(
        heroContentRef.value.children,
        { y: 24, opacity: 0 },
        {
            y: 0,
            opacity: 1,
            duration: 0.55,
            stagger: 0.08,
            ease: 'power3.out',
        },
    );
});

onMounted(() => {
    animationContext = gsap.context(() => {
        if (!heroContentRef.value) {
            return;
        }

        gsap.fromTo(
            heroContentRef.value.children,
            { y: 26, opacity: 0 },
            {
                y: 0,
                opacity: 1,
                duration: 0.65,
                stagger: 0.1,
                ease: 'power3.out',
            },
        );
    });

    restartAutoPlay();
});

onBeforeUnmount(() => {
    if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
    }

    animationContext?.revert();
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <section class="h-screen bg-slate-950 text-white">
        <div class="relative h-full w-full overflow-hidden">
            <div class="absolute inset-0">
                <img
                    ref="heroImageRef"
                    :src="currentImage"
                    :alt="currentSlide.title"
                    class="h-full w-full object-cover"
                />
                <div
                    class="absolute inset-0 bg-linear-to-b from-black/35 via-black/20 to-black/55"
                ></div>
            </div>

            <Header />

            <main
                class="absolute inset-0 z-10 flex flex-col items-center justify-center px-4 sm:px-6"
            >
                <section
                    ref="heroContentRef"
                    class="mx-auto flex w-full max-w-4xl flex-col items-center text-center"
                >
                    <p
                        v-if="currentSlide.eyebrow"
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-calendar-surface/80 px-3 py-1 text-xs font-medium tracking-wide text-white/95 backdrop-blur-md sm:px-4 sm:py-1.5"
                    >
                        <span
                            class="inline-block h-2 w-2 rounded-full bg-blue-500"
                        ></span>
                        {{ $t(currentSlide.eyebrow) }}
                    </p>

                    <h1
                        class="mt-4 text-[32px] leading-[1.1] font-bold tracking-tight text-balance text-white sm:mt-6 sm:text-[50px] md:text-[60px] lg:text-[72px]"
                        v-html="$t(currentSlide.title)"
                    ></h1>

                    <p
                        class="mt-4 text-sm leading-relaxed text-white/80 sm:mt-6 sm:text-lg md:text-[20px]"
                        v-html="$t(currentSlide.description)"
                    ></p>

                    <div
                        class="mt-8 flex w-full flex-col items-center justify-center gap-3 sm:mt-12 sm:w-auto sm:flex-row sm:gap-4"
                        v-if="
                            currentSlide.buttontextone &&
                            currentSlide.urlone &&
                            currentSlide.buttontexttwo &&
                            currentSlide.urltwo
                        "
                    >
                        <a
                            :href="currentSlide.urlone"
                            class="inline-flex w-full max-w-xs items-center justify-center rounded-xl border border-white/20 bg-calendar-surface/60 px-6 py-3 text-sm font-semibold text-white backdrop-blur-md transition-colors hover:bg-white/10 sm:w-auto sm:px-8 sm:py-3.5"
                        >
                            {{ $t(currentSlide.buttontextone) }}
                        </a>
                        <a
                            :href="currentSlide.urltwo"
                            class="inline-flex w-full max-w-xs items-center justify-center gap-2 rounded-xl bg-calendar-primary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-calendar-primary/40 transition-colors hover:bg-calendar-primary-hover sm:w-auto sm:px-8 sm:py-3.5"
                        >
                            {{ $t(currentSlide.buttontexttwo) }}
                            <span>→</span>
                        </a>
                    </div>

                    <div class="mt-8 flex items-center gap-2 sm:mt-10">
                        <p
                            class="text-sm text-[#9ca3af] italic sm:text-lg"
                            style="
                                font-family:
                                    'Homemade Apple', cursive, sans-serif;
                            "
                        >
                            {{ $t(currentSlide.tagline) }}
                        </p>
                        <ArrowUpRight
                            class="size-6 text-[#9ca3af] opacity-70"
                        />
                    </div>
                </section>

                <button
                    type="button"
                    class="absolute top-1/2 left-6 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-calendar-surface/60 text-white/90 backdrop-blur-md transition-colors hover:bg-white/15 md:left-12 md:inline-flex"
                    @click="
                        previousSlide();
                        restartAutoPlay();
                    "
                    aria-label="Previous slide"
                >
                    <ChevronLeft class="h-5 w-5" />
                </button>

                <button
                    type="button"
                    class="absolute top-1/2 right-6 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-calendar-surface/60 text-white/90 backdrop-blur-md transition-colors hover:bg-white/15 md:right-12 md:inline-flex"
                    @click="
                        nextSlide();
                        restartAutoPlay();
                    "
                    aria-label="Next slide"
                >
                    <ChevronRight class="h-5 w-5" />
                </button>

                <div
                    class="absolute bottom-10 left-1/2 flex -translate-x-1/2 items-center gap-1.5 sm:gap-2"
                >
                    <button
                        type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/15 bg-calendar-surface/55 text-white/90 backdrop-blur-md md:hidden"
                        @click="
                            previousSlide();
                            restartAutoPlay();
                        "
                        aria-label="Previous slide"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </button>

                    <button
                        v-for="(slide, index) in slides"
                        :key="slide.title"
                        type="button"
                        class="h-0.5 rounded-full transition-all duration-300"
                        :class="
                            index === currentSlideIndex
                                ? 'w-8 bg-white'
                                : 'w-3 bg-white/40 hover:bg-white/60'
                        "
                        @click="
                            goToSlide(index);
                            restartAutoPlay();
                        "
                        :aria-label="`Go to slide ${index + 1}`"
                    ></button>

                    <button
                        type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/15 bg-calendar-surface/55 text-white/90 backdrop-blur-md md:hidden"
                        @click="
                            nextSlide();
                            restartAutoPlay();
                        "
                        aria-label="Next slide"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </button>
                </div>
            </main>
        </div>
    </section>
    <EventsList :events="events" />
    <WhazzupFlights />
</template>

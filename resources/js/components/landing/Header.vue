<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowRightLeft,
    ArrowUpRight,
    Award,
    BookOpen,
    Briefcase,
    CalendarClock,
    Compass,
    Crown,
    Globe,
    GraduationCap,
    Landmark,
    Link2,
    MessageCircle,
    Plane,
    Radar,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import type { Component } from 'vue';
import LogoIvaoCo from '@/components/LogoIvaoCo.vue';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuContent,
    NavigationMenuLink,
    NavigationMenuTrigger,
    NavigationMenuList,
} from '@/components/ui/navigation-menu';
import { useAppearance } from '@/composables/useAppearance';
import { useLocale } from '@/composables/useLocale';
import { dashboard } from '@/routes';
import auth from '@/routes/auth';
import type { Locale } from '@/types';

type SectionLink = {
    title: string;
    href: string;
    description: string;
};

type MenuSection = {
    title: string;
    href: string;
    description: string;
    imageSrc?: string;
    items: SectionLink[];
};

const menuSections: MenuSection[] = [
    {
        title: 'ABOUT US',
        href: '#about-us',
        description: '',
        imageSrc: '/about_img.jpg',
        items: [
            {
                title: 'About Us',
                href: '#about-us-introduction',
                description: 'Learn more about IVAO Colombia.',
            },
            {
                title: 'Division Staff',
                href: '#about-us-history',
                description: 'Meet our team',
            },
            {
                title: 'Work With Us',
                href: '#about-us-staff',
                description: 'Join our team and contribute to the division.',
            },
            {
                title: 'Rating Transfer',
                href: '#about-us-contact',
                description:
                    'Transfer your ratings to IVAO Colombia and continue your progression with us.',
            },
        ],
    },
    {
        title: 'CONTROLLERS',
        href: '#controllers',
        description:
            'Information and resources for controllers of the division.',
        imageSrc: '/bog_torre.jpg',
        items: [
            {
                title: 'First Steps',
                href: '#controllers-training',
                description:
                    'Instructions to start controlling in IVAO Colombia.',
            },
            {
                title: 'Special Positions',
                href: '#controllers-ratings',
                description:
                    'List of special positions and how to apply for them.',
            },
            {
                title: 'ATC Booking',
                href: '#controllers-events',
                description: 'Book ATC positions and check the schedule.',
            },
            {
                title: 'Operations Manual',
                href: '#controllers-training',
                description:
                    'Specific procedures and guidelines for controlling in Colombia.',
            },
            {
                title: 'Requests & Training',
                href: '#controllers-training',
                description:
                    'ATC training programs and resources for skill development.',
            },
        ],
    },
    {
        title: 'PILOTS',
        href: '#pilots',
        description: 'Sections for pilots and flight operations.',
        imageSrc: '/pilots_img.jpg',
        items: [
            {
                title: 'First Steps',
                href: '#pilots-guides',
                description: 'Instructions to start flying in IVAO Colombia.',
            },
            {
                title: 'Tours',
                href: '#pilots-flights',
                description:
                    'Check out our division tours and fly with the community.',
            },
            {
                title: 'Virtual Airlines',
                href: '#pilots-procedures',
                description:
                    'List of virtual airlines based in Colombia and how to join them.',
            },
            {
                title: 'World Tours',
                href: '#pilots-events',
                description:
                    'Check out our flights around the world and fly with the community.',
            },
            {
                title: 'Requests & Training',
                href: '#pilots-events',
                description:
                    'Pilot training programs and resources for skill development.',
            },
        ],
    },
    {
        title: 'COMMUNITY',
        href: '#community',
        description: 'Activities, integration, and community participation.',
        imageSrc: '/community_img.jpg',
        items: [
            {
                title: 'Discord',
                href: '#community-news',
                description:
                    'Authentic community interactions, announcements, and support.',
            },
            {
                title: 'Useful Links',
                href: '#community-discord',
                description:
                    'Important resources and platforms for IVAO Colombia members.',
            },
            {
                title: 'Calendar',
                href: '#community-gallery',
                description:
                    'Stay updated with our events and activities schedule.',
            },
            {
                title: 'Division Awards',
                href: '#community-events',
                description:
                    'Recognition and rewards for outstanding contributions to the division.',
            },
            {
                title: 'International Awards',
                href: '#community-projects',
                description:
                    'Recognition and rewards for outstanding contributions to the international IVAO community.',
            },
        ],
    },
];

const { locale, updateLocale } = useLocale();
const { resolvedAppearance, updateAppearance } = useAppearance();

const languageOptions: Array<{ value: Locale; label: string }> = [
    { value: 'es', label: 'ES' },
    { value: 'en', label: 'EN' },
];

const isDarkMode = computed(() => resolvedAppearance.value === 'dark');
const logoSrc = computed(() =>
    isDarkMode.value ? '/logo-small-white.png' : '/logo-small-blue.svg',
);
const loginLogoSrc = computed(() => '/logo-small-white.png');

const itemIcons: Record<string, Component> = {
    'About Us': Compass,
    'Division Staff': Users,
    'Work With Us': Briefcase,
    'Rating Transfer': ArrowRightLeft,
    'First Steps': Compass,
    'Special Positions': Radar,
    'ATC Booking': CalendarClock,
    'Operations Manual': BookOpen,
    'Requests & Training': GraduationCap,
    Tours: Globe,
    'Virtual Airlines': Plane,
    'World Tours': Globe,
    Discord: MessageCircle,
    'Useful Links': Link2,
    Calendar: CalendarClock,
    'Division Awards': Award,
    'International Awards': Crown,
};

function resolveItemIcon(title: string): Component {
    return itemIcons[title] ?? Landmark;
}

function toggleAppearance(): void {
    updateAppearance(isDarkMode.value ? 'light' : 'dark');
}
</script>

<template>
    <header class="relative z-50 px-4 pt-4 lg:px-6">
        <nav
            class="mx-auto max-w-7xl rounded-[28px] border border-slate-200/70 bg-white/80 px-4 py-3 shadow-[0_18px_50px_rgba(15,23,42,0.16)] backdrop-blur-xl dark:border-slate-700/60 dark:bg-slate-900/75"
        >
            <div class="flex flex-wrap items-center justify-between gap-4">
                <a href="/" class="flex items-center">
                    <LogoIvaoCo
                        :height="60"
                        :src="logoSrc"
                        title-text="IVAO"
                        country-text="COLOMBIA"
                    />
                </a>
                <div class="flex items-center gap-3 lg:order-2">
                    <div class="hidden items-center gap-2 sm:flex">
                        <div
                            class="inline-flex rounded-full border border-slate-300/90 bg-white/80 p-1 shadow-sm dark:border-slate-600 dark:bg-slate-800/80"
                        >
                            <button
                                v-for="option in languageOptions"
                                :key="option.value"
                                type="button"
                                @click="updateLocale(option.value)"
                                :class="[
                                    'rounded-full px-2.5 py-1 text-xs font-semibold tracking-wide transition-colors',
                                    locale === option.value
                                        ? 'bg-[#1d4ed8] text-white'
                                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white',
                                ]"
                            >
                                {{ option.label }}
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="toggleAppearance"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300/90 bg-white text-slate-700 shadow-sm transition-colors hover:bg-slate-100 focus:ring-2 focus:ring-[#1d4ed8]/40 focus:outline-none dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
                            :aria-label="
                                isDarkMode
                                    ? $t('Switch to light mode')
                                    : $t('Switch to dark mode')
                            "
                        >
                            <svg
                                v-if="isDarkMode"
                                class="h-4 w-4 text-amber-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M12 3V5"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M12 19V21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M5.64 5.64L7.05 7.05"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M16.95 16.95L18.36 18.36"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M3 12H5"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M19 12H21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M5.64 18.36L7.05 16.95"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M16.95 7.05L18.36 5.64"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="4"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-4 w-4 text-slate-700 dark:text-slate-100"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M21 12.79A9 9 0 1 1 11.21 3C11.61 3 11.81 3 12 3.04C9.91 4.07 8.5 6.27 8.5 8.8C8.5 12.34 11.36 15.2 14.9 15.2C17.43 15.2 19.63 13.79 20.66 11.7C20.7 11.89 20.7 12.09 20.7 12.49L21 12.79Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </button>
                    </div>

                    <div
                        class="hidden h-6 w-px bg-slate-400/80 sm:block dark:bg-slate-600"
                    ></div>

                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center rounded-full border border-slate-300 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:border-slate-400 hover:text-slate-900 dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-100 dark:hover:border-slate-500"
                    >
                        {{ $t('Dashboard') }}
                    </Link>
                    <template v-else>
                        <Link
                            :href="auth.redirect()"
                            class="inline-flex items-center gap-1.5 rounded-full border border-transparent bg-[#1d4ed8] px-4 py-2.5 text-center text-sm leading-5 font-semibold text-white transition-colors hover:bg-[#1e40af] focus:ring-4 focus:ring-[#1d4ed8]/40 focus:outline-none dark:bg-[#2563eb] dark:hover:bg-[#1d4ed8] dark:focus:ring-[#2563eb]/45"
                        >
                            <img
                                :src="loginLogoSrc"
                                :alt="$t('IVAO logo')"
                                class="me-1.5 h-4 w-4 shrink-0 object-contain"
                            />
                            {{ $t('Log in') }}
                        </Link>
                    </template>
                    <button
                        data-collapse-toggle="mobile-menu-2"
                        type="button"
                        class="inline-flex items-center rounded-lg p-2 text-sm text-slate-600 transition-colors hover:bg-slate-100 focus:ring-2 focus:ring-slate-300 focus:outline-none lg:hidden dark:text-slate-300 dark:hover:bg-slate-800 dark:focus:ring-slate-600"
                        aria-controls="mobile-menu-2"
                        aria-expanded="false"
                    >
                        <span class="sr-only">{{ $t('Open main menu') }}</span>
                        <svg
                            class="h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <svg
                            class="hidden h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                </div>
                <NavigationMenu class="hidden lg:order-1 lg:flex">
                    <NavigationMenuList class="gap-2">
                        <NavigationMenuItem
                            v-for="section in menuSections"
                            :key="section.title"
                        >
                            <NavigationMenuTrigger
                                class="bg-transparent font-heading text-[0.84rem] font-semibold tracking-wide text-slate-600 transition-colors hover:bg-slate-100 hover:text-[#1d4ed8] data-[state=open]:bg-transparent data-[state=open]:text-[#1d4ed8] dark:bg-transparent dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300 dark:data-[state=open]:bg-transparent dark:data-[state=open]:text-blue-300"
                            >
                                {{ $t(section.title) }}
                            </NavigationMenuTrigger>

                            <NavigationMenuContent>
                                <div
                                    class="grid w-135 gap-3 p-4 md:grid-cols-[0.95fr_1.05fr]"
                                >
                                    <div
                                        class="relative overflow-hidden rounded-lg bg-muted/50"
                                    >
                                        <a
                                            :href="section.href"
                                            class="group relative flex h-full min-h-72 w-full items-end"
                                        >
                                            <img
                                                :src="
                                                    section.imageSrc ??
                                                    '/logo-white copy.webp'
                                                "
                                                :alt="$t(section.title)"
                                                class="absolute inset-0 h-full w-full object-cover"
                                            />
                                            <div
                                                class="relative z-10 w-full bg-linear-to-t from-black/65 to-black/0 p-4 text-white"
                                            >
                                                <div
                                                    class="text-sm font-semibold tracking-wide"
                                                >
                                                    {{ $t(section.title) }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="grid gap-2">
                                        <a
                                            v-for="item in section.items"
                                            :key="item.title"
                                            :href="item.href"
                                            class="group/item relative overflow-hidden rounded-md border border-transparent p-3 transition-all duration-200 hover:-translate-y-0.5 hover:border-[#1d4ed8]/40 hover:bg-accent/60 hover:shadow-sm focus-visible:ring-2 focus-visible:ring-[#1d4ed8]/35 focus-visible:outline-none"
                                        >
                                            <span
                                                class="absolute top-0 left-0 h-full w-1 bg-[#1d4ed8] opacity-0 transition-opacity duration-200 group-hover/item:opacity-100"
                                            ></span>

                                            <div
                                                class="flex items-start gap-2.5"
                                            >
                                                <span
                                                    class="mt-0.5 rounded-md bg-slate-100 p-1.5 text-slate-600 transition-all duration-200 group-hover/item:scale-110 group-hover/item:bg-[#1d4ed8]/15 group-hover/item:text-[#1d4ed8] dark:bg-slate-800 dark:text-slate-300 dark:group-hover/item:bg-blue-500/20 dark:group-hover/item:text-blue-300"
                                                >
                                                    <component
                                                        :is="
                                                            resolveItemIcon(
                                                                item.title,
                                                            )
                                                        "
                                                        class="h-4 w-4"
                                                    />
                                                </span>

                                                <div class="min-w-0 flex-1">
                                                    <div
                                                        class="flex items-center gap-1.5 text-sm font-medium text-foreground"
                                                    >
                                                        <span>{{
                                                            $t(item.title)
                                                        }}</span>
                                                        <ArrowUpRight
                                                            class="h-3.5 w-3.5 -translate-x-1 text-[#1d4ed8] opacity-0 transition-all duration-200 group-hover/item:translate-x-0 group-hover/item:opacity-100 dark:text-blue-300"
                                                        />
                                                    </div>

                                                    <p
                                                        class="mt-1 text-sm leading-5 text-muted-foreground"
                                                    >
                                                        {{
                                                            $t(item.description)
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </NavigationMenuContent>
                        </NavigationMenuItem>
                        <NavigationMenuItem>
                            <NavigationMenuLink
                                as-child
                                class="inline-flex h-9 items-center justify-center rounded-md bg-transparent px-4 py-2 text-[0.84rem] font-semibold tracking-wide text-slate-600 transition-colors hover:bg-slate-100 hover:text-[#1d4ed8] dark:bg-transparent dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300"
                            >
                                <a href="/" aria-current="page">{{
                                    $t('SOCO')
                                }}</a>
                            </NavigationMenuLink>
                        </NavigationMenuItem>
                    </NavigationMenuList>
                </NavigationMenu>
            </div>
        </nav>
    </header>
</template>

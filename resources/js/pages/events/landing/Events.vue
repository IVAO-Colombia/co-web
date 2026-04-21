<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
  ArrowRight,
  CalendarClock,
  Clock3,
  Globe,
  MapPin,
  PlaneTakeoff,
  Tag,
} from 'lucide-vue-next';
import { computed } from 'vue';
import Header from '@/components/landing/Header.vue';
import { useLocale } from '@/composables/useLocale';
import { EventStatus, EventTag, EventType } from '@/types/backend.d';
import type { Event } from '@/types';

type LandingCopy = {
  badge: string;
  headerText: string;
  title: string;
  description: string;
  primaryCta: string;
  secondaryCta: string;
  localeLabel: string;
  allEvents: string;
  schedule: string;
  location: string;
  noImage: string;
  bookingAvailable: string;
  active: string;
  draft: string;
  cancelled: string;
  finalized: string;
};

const props = defineProps<{
  events: Event[];
}>();

const { locale, updateLocale } = useLocale();

const copy = computed<Record<'es' | 'en', LandingCopy>>(() => ({
  es: {
    badge: 'EVENTOS',
    headerText: 'EVENTOS',
    title: 'Explora nuestros próximos eventos en la división.',
    description:
      'Operaciones, entrenamientos y encuentros especiales reunidos en una sola landing. Cambia entre español e inglés con un clic.',
    primaryCta: 'Ver eventos',
    secondaryCta: 'Cambiar a inglés',
    localeLabel: 'Idioma',
    allEvents: 'Todos los eventos',
    schedule: 'Horario',
    location: 'Ubicación',
    noImage: 'Sin imagen',
    bookingAvailable: 'Reserva disponible',
    active: 'Activo',
    draft: 'Borrador',
    cancelled: 'Cancelado',
    finalized: 'Finalizado',
  },
  en: {
    badge: 'EVENTS',
    headerText: 'EVENTS',
    title: 'Explore our upcoming division events.',
    description:
      'Operations, trainings, and special gatherings in one landing. Switch between Spanish and English with a single click.',
    primaryCta: 'View events',
    secondaryCta: 'Switch to Spanish',
    localeLabel: 'Language',
    allEvents: 'All events',
    schedule: 'Schedule',
    location: 'Location',
    noImage: 'No image',
    bookingAvailable: 'Booking available',
    active: 'Active',
    draft: 'Draft',
    cancelled: 'Cancelled',
    finalized: 'Finalized',
  },
}));

const currentCopy = computed(() => copy.value[locale.value]);

const statusLabels: Record<EventStatus, string> = {
  [EventStatus.ACTIVE]: currentCopy.value.active,
  [EventStatus.DRAFT]: currentCopy.value.draft,
  [EventStatus.CANCELLED]: currentCopy.value.cancelled,
  [EventStatus.FINALIZED]: currentCopy.value.finalized,
};

const typeLabels: Record<EventType, string> = {
  [EventType.ONLINE_DAY]: 'Online Day',
  [EventType.EXAM]: 'Exam',
  [EventType.TRAINING]: 'Training',
  [EventType.RFO]: 'RFO',
  [EventType.RFE]: 'RFE',
};

const tagLabels: Record<EventTag, string> = {
  [EventTag.VFR]: 'VFR',
  [EventTag.IFR]: 'IFR',
  [EventTag.CrossCountry]: 'Cross Country',
  [EventTag.Division]: 'Division',
  [EventTag.Hq]: 'HQ',
};

const orderedEvents = computed(() =>
  [...props.events].sort(
    (left, right) =>
      new Date(left.starts_at).getTime() - new Date(right.starts_at).getTime(),
  ),
);

function getLocalizedName(event: Event): string {
  return locale.value === 'en' && event.name_en ? event.name_en : event.name;
}

function getLocalizedDescription(event: Event): string {
  return locale.value === 'en' && event.description_en
    ? event.description_en
    : event.description;
}

function getDateParts(startsAt: string): { day: string; month: string; time: string } {
  const date = new Date(startsAt);
  const dateLocale = locale.value === 'en' ? 'en-US' : 'es-CO';

  return {
    day: new Intl.DateTimeFormat(dateLocale, {
      day: '2-digit',
      timeZone: 'UTC',
    }).format(date),
    month: new Intl.DateTimeFormat(dateLocale, {
      month: 'short',
      timeZone: 'UTC',
    })
      .format(date)
      .replace('.', '')
      .toUpperCase(),
    time: `${new Intl.DateTimeFormat(dateLocale, {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
      timeZone: 'UTC',
    }).format(date)} UTC`,
  };
}

function hasReservation(event: Event): boolean {
  return event.pilot_slots_enabled || event.atc_slots_enabled;
}

function toggleLocale(): void {
  updateLocale(locale.value === 'en' ? 'es' : 'en');
}
</script>

<template>
  <Head :title="currentCopy.title" />

  <section class="relative min-h-screen overflow-hidden bg-slate-950 text-white">

    <Header brand-tone="dark" :brand-text="currentCopy.headerText" />
    
    <div class="absolute inset-0">
      <img
        src="/fonodo.jpg"
        alt="IVAO Colombia events background"
        class="h-full w-full object-cover opacity-35"
      />
      <div class="absolute inset-0 bg-linear-to-b from-black/35 via-slate-950/75 to-slate-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(47,69,255,0.28),transparent_45%)]"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-5 sm:px-6 lg:px-8">



      <div class="flex flex-1 flex-col justify-center py-10 sm:py-14 lg:py-16">
        <div class="max-w-3xl">
          <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-calendar-surface/80 px-3 py-1 text-xs font-semibold tracking-[0.22em] text-white/90 backdrop-blur-md sm:px-4 sm:py-1.5">
            <span class="h-2 w-2 rounded-full bg-calendar-primary"></span>
            {{ currentCopy.badge }}
          </p>

          <h1 class="mt-5 text-4xl font-black leading-tight tracking-tight text-balance sm:text-5xl lg:text-7xl">
            {{ currentCopy.title }}
          </h1>

          <p class="mt-5 max-w-2xl text-base leading-relaxed text-white/78 sm:text-lg lg:text-xl">
            {{ currentCopy.description }}
          </p>
        </div>
      </div>

      <div id="events-grid" class="pb-10 sm:pb-14 lg:pb-16">
        <div class="mb-5 flex items-end justify-between gap-4">
          <div>
            <p class="text-sm font-medium uppercase tracking-[0.24em] text-white/55">
              {{ currentCopy.allEvents }}
            </p>
            <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">
              {{ orderedEvents.length }} {{ currentCopy.allEvents.toLowerCase() }}
            </h2>
          </div>

          <p class="hidden text-sm text-white/55 sm:block">
            {{ currentCopy.schedule }} + {{ currentCopy.location }}
          </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
          <article
            v-for="event in orderedEvents"
            :key="event.id"
            class="group overflow-hidden rounded-3xl border border-white/10 bg-slate-900/80 shadow-[0_20px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-[0_24px_80px_rgba(0,0,0,0.45)]"
          >
            <div class="relative h-56 overflow-hidden">
              <img
                :src="event.image_url ?? '/fondo.jpg'"
                :alt="getLocalizedName(event)"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-linear-to-t from-black via-black/25 to-transparent"></div>

              <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                <span
                  v-if="hasReservation(event)"
                  class="rounded-full border border-emerald-300/30 bg-emerald-400/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-200 backdrop-blur-md"
                >
                  {{ currentCopy.bookingAvailable }}
                </span>
                <span
                  class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/85 backdrop-blur-md"
                >
                  {{ statusLabels[event.status] }}
                </span>
              </div>

              <div class="absolute right-4 bottom-4 rounded-2xl border border-white/10 bg-slate-950/80 px-3 py-2 backdrop-blur-xl">
                <p class="text-2xl font-black leading-none text-white">
                  {{ getDateParts(event.starts_at).day }}
                </p>
                <p class="mt-1 text-xs font-semibold tracking-[0.22em] text-white/70">
                  {{ getDateParts(event.starts_at).month }}
                </p>
              </div>
            </div>

            <div class="space-y-4 p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="line-clamp-2 text-xl font-black leading-tight tracking-tight text-white">
                  {{ getLocalizedName(event) }}
                </h3>
                <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
                  {{ typeLabels[event.type] }}
                </span>
              </div>

              <p class="line-clamp-3 text-sm leading-relaxed text-white/68">
                {{ getLocalizedDescription(event) }}
              </p>

              <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-white/65">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                  <Clock3 class="h-4 w-4" />
                  {{ getDateParts(event.starts_at).time }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1.5">
                  <MapPin class="h-4 w-4" />
                                    <span class="max-w-56 truncate">{{ event.locations }}</span>
                </span>
              </div>

              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tag in event.tags"
                  :key="tag"
                  class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-slate-800/85 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/75"
                >
                  <Tag class="h-3.5 w-3.5" />
                  {{ tagLabels[tag] }}
                </span>
              </div>

              <div class="flex items-center justify-between border-t border-white/10 pt-4 text-sm text-white/72">
                <span>{{ getDateParts(event.starts_at).time }}</span>
                <button
                  type="button"
                  class="inline-flex items-center gap-2 font-semibold text-white transition-colors hover:text-[#9bb0ff]"
                >
                  <CalendarClock class="h-4 w-4" />
                  {{ currentCopy.primaryCta }}
                </button>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>
</template>
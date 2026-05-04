<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Ivao } from '@/lib/ivao';
import whazzupRoutes from '@/routes/ivao/whazzup';

type WhazzupFlight = {
    flight_id: number;
    ivao_id: number | null;
    callsign: string;
    airline: string;
    dep_icao: string;
    dep_name: string;
    arr_icao: string;
    arr_name: string;
    aircraft: string;
    aircraft_model: string | null;
    latitude: number;
    longitude: number;
    altitude: number;
    ground_speed: number;
    heading: number;
    state: string;
    timestamp: string;
};

type WhazzupResponse = {
    success: boolean;
    flights: WhazzupFlight[];
    count: number;
    cached_at: string;
};

const flights = ref<WhazzupFlight[]>([]);
const isLoading = ref(true);
const errorMessage = ref('');
const lastUpdatedAt = ref<string | null>(null);

let intervalId: number | null = null;
let abortController: AbortController | null = null;

const getAirlineLogo = (airline: string): string => {
    const cacheKey = `airlines/${airline}/logo`;
    return Ivao.cache.get(cacheKey) || '';
};

const getStateColor = (state: string): string => {
    const stateMap: Record<string, string> = {
        Airborne:
            'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
        Approach:
            'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        Enroute:
            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        Landed: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
    };

    return (
        stateMap[state] ||
        'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'
    );
};

const fetchFlights = async (): Promise<void> => {
    abortController?.abort();
    abortController = new AbortController();

    isLoading.value = flights.value.length === 0;
    errorMessage.value = '';

    try {
        const response = await fetch(whazzupRoutes.sk.url(), {
            headers: {
                Accept: 'application/json',
            },
            signal: abortController.signal,
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = (await response.json()) as WhazzupResponse;

        flights.value = data.flights ?? [];
        lastUpdatedAt.value = data.cached_at;

        // Preload logos for all airlines using Ivao cache
        for (const flight of flights.value) {
            void getAirlineLogo(flight.airline);
        }
    } catch (error) {
        if (error instanceof DOMException && error.name === 'AbortError') {
            return;
        }

        errorMessage.value =
            'No se pudieron cargar los vuelos en este momento.';
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    void fetchFlights();

    intervalId = window.setInterval(() => {
        void fetchFlights();
    }, 15000);
});

onBeforeUnmount(() => {
    if (intervalId !== null) {
        window.clearInterval(intervalId);
    }

    abortController?.abort();
});
</script>

<template>
    <section class="relative overflow-hidden py-16 sm:py-20 dark:bg-slate-950">
        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2
                    class="mt-5 text-4xl leading-tight font-black tracking-tight text-balance text-slate-900 sm:text-5xl lg:text-6xl dark:text-white"
                >
                    {{ $t('Flights in real time') }}
                </h2>

                <p
                    class="mt-6 text-base leading-relaxed text-slate-600 sm:text-lg lg:text-xl dark:text-slate-300"
                >
                    {{
                        $t(
                            'Track flights taking off or landing at Colombian airports via the IVAO network.',
                        )
                    }}
                </p>
            </div>

            <!-- Header Card -->
            <div
                class="mt-10 rounded-2xl border border-sky-200/40 bg-white/80 shadow-sm backdrop-blur-sm dark:border-sky-900/40 dark:bg-slate-800/60"
            >
                <div
                    class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6"
                >
                    <div>
                        <h3
                            class="text-lg font-bold text-slate-900 dark:text-white"
                        >
                            {{ flights.length }}
                            {{ flights.length === 1 ? 'vuelo' : 'vuelos' }} en
                            el aire
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            <span v-if="lastUpdatedAt"
                                > {{ $t('Last updated') }}: {{ lastUpdatedAt }}</span
                            >
                            <span v-else>{{ $t('Loading...') }}</span>
                        </p>
                    </div>

                    <div
                        v-if="isLoading && flights.length > 0"
                        class="inline-flex items-center gap-2 text-sm text-sky-600 dark:text-sky-400"
                    >
                        <span
                            class="h-2.5 w-2.5 animate-spin rounded-full border-2 border-current border-t-transparent"
                        ></span>
                        {{ $t('Updating...') }}
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div
                v-if="errorMessage"
                class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200"
            >
                {{ errorMessage }}
            </div>

            <!-- Empty State -->
            <div
                v-if="flights.length === 0 && !isLoading && !errorMessage"
                class="mt-10 rounded-2xl border border-slate-200/60 bg-white/80 px-5 py-16 text-center backdrop-blur-sm dark:border-slate-700/40 dark:bg-slate-800/60"
            >
                <div
                    class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700"
                >
                    <svg
                        class="h-6 w-6 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
                <h4
                    class="mt-4 text-lg font-semibold text-slate-900 dark:text-white"
                >
                    {{ $t('There are no flights at this time') }}
                </h4>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ $t(' Flights taking off from or landing at Colombian airports will appear here') }}.
                </p>
            </div>

            <!-- Flights Table - Compact and Beautiful -->
            <div
                v-else
                class="mt-8 overflow-hidden rounded-xl border border-slate-200/60 bg-white/80 backdrop-blur-sm dark:border-slate-700/40 dark:bg-slate-800/60"
            >
                <!-- Table Rows -->
                <div
                    class="divide-y divide-slate-100/60 dark:divide-slate-700/30"
                >
                    <div
                        v-for="flight in flights"
                        :key="`${flight.flight_id}-${flight.timestamp}`"
                        class="group grid grid-cols-12 items-center gap-4 px-4 py-4 transition-colors duration-200 hover:bg-sky-50/30 lg:px-6 lg:py-5 dark:hover:bg-sky-950/10"
                    >
                        <!-- Logo Section with Background -->
                        <div
                            class="col-span-3 flex items-center justify-center rounded-lg border border-slate-100/50 bg-gradient-to-br from-slate-50 to-slate-50/50 p-3 lg:col-span-2 lg:p-4 dark:border-slate-700/30 dark:from-slate-700/20 dark:to-slate-800/20"
                        >
                            <div
                                v-if="!getAirlineLogo(flight.airline)"
                                class="h-12 w-16 animate-pulse rounded bg-slate-300 dark:bg-slate-600"
                            ></div>
                            <img
                                v-else
                                :src="getAirlineLogo(flight.airline)"
                                :alt="`${flight.airline} logo`"
                                class="max-h-14 max-w-24 object-contain drop-shadow"
                            />
                            <div
                                v-if="!getAirlineLogo(flight.airline)"
                                class="flex max-h-14 max-w-24 items-center justify-center"
                            >
                                <div class="text-center">
                                    <div
                                        class="mb-1 text-xs font-bold text-slate-600 dark:text-slate-300"
                                    >
                                        IVAO
                                    </div>
                                    <div
                                        class="text-xs font-semibold text-slate-500 dark:text-slate-400"
                                    >
                                        CO
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Callsign -->
                        <div class="lg:col-span-1.5 col-span-2">
                            <p
                                class="mb-1 text-xs font-semibold tracking-wide text-slate-500 uppercase lg:hidden dark:text-slate-400"
                            >
                                Vuelo
                            </p>
                            <p
                                class="font-mono text-sm font-bold text-slate-900 lg:text-base dark:text-white"
                            >
                                {{ flight.callsign }}
                            </p>
                        </div>

                        <!-- Route -->
                        <div class="col-span-3 hidden sm:block lg:col-span-2">
                            <p
                                class="mb-1 text-xs font-semibold tracking-wide text-slate-500 uppercase lg:hidden dark:text-slate-400"
                            >
                                Ruta
                            </p>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="font-mono text-xs font-bold text-slate-900 lg:text-sm dark:text-white"
                                    >{{ flight.dep_icao }}</span
                                >
                                <svg
                                    class="h-3 w-3 flex-shrink-0 text-slate-400 dark:text-slate-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                    ></path>
                                </svg>
                                <span
                                    class="font-mono text-xs font-bold text-slate-900 lg:text-sm dark:text-white"
                                    >{{ flight.arr_icao }}</span
                                >
                            </div>
                        </div>

                        <!-- Aircraft (hidden on mobile) -->
                        <div class="hidden lg:col-span-2 lg:block">
                            <p
                                class="truncate font-mono text-xs font-medium text-slate-700 dark:text-slate-300"
                            >
                                {{ flight.aircraft_model ?? flight.aircraft }}
                            </p>
                        </div>

                        <!-- Estado / Status Badge -->
                        <div
                            class="col-span-3 flex items-center justify-end lg:col-span-2 lg:justify-start"
                        >
                            <span
                                :class="`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold whitespace-nowrap ${getStateColor(flight.state)}`"
                            >
                                {{ flight.state }}
                            </span>
                        </div>

                        <div
                            class="col-span-3 flex items-center justify-end lg:col-span-2 lg:justify-start"
                        >
                            <a
                                :href="`https://webeye.ivao.aero/?pilotId=${flight.flight_id}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="box-border inline-block cursor-pointer rounded-xl border border-transparent bg-[var(--color-primary)] px-4 py-2.5 text-sm leading-5 font-medium text-white shadow-xs transition-all duration-200 hover:brightness-90 focus:ring-4 focus:ring-[var(--color-primary)] focus:ring-offset-2 focus:outline-none"
                                >{{ $t('Live Flight') }}</a
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div
                v-if="isLoading && flights.length === 0"
                class="mt-8 overflow-hidden rounded-xl border border-slate-200/60 bg-white/80 dark:border-slate-700/40 dark:bg-slate-800/60"
            >
                <div class="divide-y divide-slate-100 dark:divide-slate-700/30">
                    <div
                        v-for="i in 6"
                        :key="i"
                        class="grid animate-pulse grid-cols-12 gap-4 px-4 py-4 lg:px-6 lg:py-5"
                    >
                        <div
                            class="col-span-3 h-14 rounded-lg bg-slate-200 lg:col-span-2 dark:bg-slate-700"
                        ></div>
                        <div
                            class="lg:col-span-1.5 col-span-2 h-4 rounded bg-slate-200 dark:bg-slate-700"
                        ></div>
                        <div
                            class="col-span-3 hidden h-4 rounded bg-slate-200 sm:block lg:col-span-2 dark:bg-slate-700"
                        ></div>
                        <div
                            class="col-span-2 h-4 rounded bg-slate-200 lg:col-span-1 dark:bg-slate-700"
                        ></div>
                        <div
                            class="col-span-2 h-4 rounded bg-slate-200 lg:col-span-1 dark:bg-slate-700"
                        ></div>
                        <div
                            class="hidden h-4 rounded bg-slate-200 lg:col-span-2 lg:block dark:bg-slate-700"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

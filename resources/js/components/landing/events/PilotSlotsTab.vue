<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { MoveRight } from 'lucide-vue-next';
import { computed, onBeforeMount, ref } from 'vue';
import { toast } from 'vue-sonner';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { useLocale } from '@/composables/useLocale';
import { Ivao } from '@/lib/ivao';
import { formatDateTime } from '@/lib/utils';
import { SlotsConstants, SlotStatus } from '@/types';
import type { PilotSlot } from '@/types';
import auth from '@/routes/auth';
import dashboardPilotSlotRoutes from '@/routes/dashboard/events/pilot-slot';

const props = defineProps<{
    eventSlug: string;
    eventStartsAt: string;
    pilotSlots: PilotSlot[];
}>();

const { locale } = useLocale();
const page = usePage();
const user = computed(() => page.props.auth?.user);
const isLoggedIn = computed(() => !!user.value);
const pilotSlotForm = useForm();

const airlineLogos = ref<Record<string, string | null>>({});

onBeforeMount(async () => {
    // Preload airline logos for pilot slots
    const icos = new Set(props.pilotSlots.map((slot) => slot.airline_icao));

    for (const icao of icos) {
        airlineLogos.value[icao] = await Ivao.getAirlineLogoUrl(icao);
    }
});

const pilotSlotsByAirline = computed(() => {
    const groups: Record<string, typeof props.pilotSlots> = {};

    for (const slot of props.pilotSlots) {
        if (!groups[slot.airline_icao]) {
            groups[slot.airline_icao] = [];
        }

        groups[slot.airline_icao].push(slot);
    }

    return groups;
});

function reservePilotSlot(slotId: number) {
    pilotSlotForm.post(
        dashboardPilotSlotRoutes.store.url({
            event: props.eventSlug,
            slot: slotId,
        }),
        {
            preserveScroll: true,
            onSuccess: () =>
                toast.success(wTrans('Pilot slot reserved successfully!')),
            onError: (errors) => {
                if (errors.reservation) {
                    toast.error(errors.reservation);
                }
            },
        },
    );
}
function cancelPilotSlotReservation(slotId: number) {
    pilotSlotForm.delete(
        dashboardPilotSlotRoutes.destroy.url({
            event: props.eventSlug,
            slot: slotId,
        }),
        {
            preserveScroll: true,
            onSuccess: () =>
                toast.success(wTrans('Pilot slot cancelled successfully!')),
        },
    );
}
</script>
<template>
    <div>
        <!-- Guest CTA -->
        <div
            v-if="!isLoggedIn"
            class="mb-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-sky-500/20 bg-sky-500/10 px-4 py-3"
        >
            <p class="text-sm text-sky-700 dark:text-sky-200/75">
                {{
                    $t(
                        'Log in to see slot availability and reserve your flight.',
                    )
                }}
            </p>
            <Link
                :href="auth.redirect()"
                class="shrink-0 rounded-full bg-sky-600 px-4 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-sky-500"
            >
                {{ $t('Log in to reserve') }}
            </Link>
        </div>

        <!-- Empty state -->
        <div
            v-if="pilotSlots.length === 0"
            class="rounded-xl border border-slate-200 bg-slate-50 py-12 text-center dark:border-white/10 dark:bg-white/5"
        >
            <p class="text-sm text-slate-400 dark:text-white/40">
                {{ $t('No pilot slots available for this event.') }}
            </p>
        </div>

        <div v-else class="flex flex-col gap-4">
            <div
                v-for="(slots, airlineIcao) in pilotSlotsByAirline"
                :key="airlineIcao"
                class="overflow-hidden rounded-xl border border-slate-200 dark:border-white/10"
            >
                <!-- Airline header -->
                <div
                    class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5"
                >
                    <img
                        v-if="airlineLogos[airlineIcao]"
                        :src="airlineLogos[airlineIcao]"
                        class="h-8 w-20 object-contain"
                        :alt="`${airlineIcao} logo`"
                    />
                    <span
                        v-else
                        class="font-mono text-sm font-bold tracking-wider text-slate-900 dark:text-white"
                    >
                        {{ airlineIcao }}
                    </span>
                    <span
                        class="ml-auto rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:border-white/15 dark:bg-white/5 dark:text-white/60"
                    >
                        {{ slots.length }}
                    </span>
                </div>

                <!-- Flights table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase dark:border-white/10 dark:bg-white/5 dark:text-white/45"
                            >
                                <th class="px-4 py-3">
                                    {{ $t('Callsign') }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ $t('Route') }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ $t('Aircraft') }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ $t('Departs At') }}
                                </th>
                                <th class="px-4 py-3">
                                    {{ $t('Gate') }}
                                </th>
                                <th v-if="isLoggedIn" class="px-4 py-3">
                                    {{ $t('Status') }}
                                </th>
                                <th v-if="isLoggedIn" class="px-4 py-3">
                                    {{ $t('Pilot') }}
                                </th>
                                <th v-if="isLoggedIn" class="w-10 px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-slate-100 dark:divide-white/5"
                        >
                            <tr
                                v-for="slot in slots"
                                :key="slot.id"
                                class="transition-colors hover:bg-slate-50 dark:hover:bg-white/3"
                            >
                                <td
                                    class="px-4 py-3.5 font-mono font-semibold text-slate-900 dark:text-white"
                                >
                                    {{
                                        `${slot.airline_icao}${slot.flight_number}`
                                    }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span
                                        class="inline-flex items-center gap-1.5 text-slate-700 dark:text-white/75"
                                    >
                                        <span class="font-mono">{{
                                            slot.origin
                                        }}</span>
                                        <MoveRight
                                            class="h-3.5 w-3.5 shrink-0 text-slate-400 dark:text-white/35"
                                        />
                                        <span class="font-mono">{{
                                            slot.destination
                                        }}</span>
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3.5 text-slate-600 dark:text-white/65"
                                >
                                    {{ slot.aircraft }}
                                </td>
                                <td
                                    class="px-4 py-3.5 text-slate-600 dark:text-white/65"
                                >
                                    {{
                                        formatDateTime(slot.departs_at, locale)
                                    }}
                                </td>
                                <td
                                    class="px-4 py-3.5 text-slate-500 dark:text-white/45"
                                >
                                    {{ slot.gate ?? '—' }}
                                </td>
                                <td v-if="isLoggedIn" class="px-4 py-3.5">
                                    <Badge
                                        :variant="
                                            SlotsConstants.statusVariants[
                                                slot.status
                                            ]
                                        "
                                        class="text-xs"
                                    >
                                        {{
                                            SlotsConstants.statusLabels[
                                                slot.status
                                            ]
                                        }}
                                    </Badge>
                                </td>
                                <td
                                    v-if="isLoggedIn"
                                    class="px-4 py-3.5 text-xs text-slate-600 dark:text-white/55"
                                >
                                    <template v-if="slot.pilot">
                                        {{ slot.pilot.name }} ({{
                                            slot.pilot.vid
                                        }})
                                    </template>
                                    <span
                                        v-else
                                        class="text-slate-400 dark:text-white/25"
                                        >—</span
                                    >
                                </td>
                                <td v-if="isLoggedIn" class="px-4 py-3.5">
                                    <Button
                                        v-if="
                                            slot.status === SlotStatus.AVAILABLE
                                        "
                                        variant="outline"
                                        size="sm"
                                        class="h-7 border-primary/90 px-2.5 text-xs text-primary hover:bg-primary/15 hover:text-primary/90"
                                        :disabled="pilotSlotForm.processing"
                                        @click="reservePilotSlot(slot.id)"
                                    >
                                        {{
                                            pilotSlotForm.processing
                                                ? $t('Processing...')
                                                : $t('Reserve')
                                        }}
                                    </Button>
                                    <Button
                                        v-if="
                                            isLoggedIn &&
                                            slot.pilot_id === user?.id &&
                                            slot.status !== SlotStatus.AVAILABLE
                                        "
                                        variant="destructive"
                                        size="sm"
                                        class="h-7"
                                        :disabled="pilotSlotForm.processing"
                                        @click="
                                            cancelPilotSlotReservation(slot.id)
                                        "
                                    >
                                        {{
                                            pilotSlotForm.processing
                                                ? $t('Processing...')
                                                : $t('Cancel')
                                        }}
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { utc } from '@date-fns/utc';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { enUS } from 'date-fns/locale';
import { Radio } from 'lucide-vue-next';
import { computed } from 'vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { formatAtcTime } from '@/lib/utils';
import auth from '@/routes/auth';
import atcSlotRoutes from '@/routes/home/events/atc-slot';
import { ATCRating, ATCRatings, SlotsConstants, SlotStatus } from '@/types';
import type { AtcPositionFra, ATCRatingValue, AtcSlot } from '@/types';

const props = defineProps<{
    eventSlug: string;
    eventStartsAt: string;
    atcSlots: AtcSlot[];
    frasByCallsign: Record<string, AtcPositionFra[]>;
}>();

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isLoggedIn = computed(() => !!user.value);
const atcSlotForm = useForm();

const atcSlotsByCallsign = computed(() => {
    const groups: Record<
        string,
        {
            min_atc_rating: ATCRatingValue;
            slots: AtcSlot[];
            can_reserve: boolean;
        }
    > = {};

    for (const slot of props.atcSlots) {
        if (!groups[slot.callsign]) {
            const minAtcRating = getMinAtcRating(slot.callsign);
            groups[slot.callsign] = {
                min_atc_rating: minAtcRating,
                slots: [],
                can_reserve: user.value
                    ? ATCRating[minAtcRating.key] <= user.value?.atc_rating
                    : false,
            };
        }

        groups[slot.callsign].slots.push(slot);
    }

    return groups;
});

function reserveAtcSlot(slotId: number) {
    atcSlotForm.post(
        atcSlotRoutes.store.url({
            event: props.eventSlug,
            slot: slotId,
        }),
    );
}
function cancelAtcSlotReservation(slotId: number) {
    atcSlotForm.delete(
        atcSlotRoutes.destroy.url({
            event: props.eventSlug,
            slot: slotId,
        }),
    );
}

function getMinAtcRating(callsign: string): ATCRatingValue {
    const fras = props.frasByCallsign[callsign] ?? [];

    if (fras.length === 0) {
        return ATCRatings[ATCRating.AS1];
    }

    const utcDate = parseISO(props.eventStartsAt, { in: utc });
    const eventDateStr = format(utcDate, 'yyyy-MM-dd');
    // const eventTimeStr = format(utcDate, 'HH:mm');
    const dayOfWeek = format(utcDate, 'EEEE', {
        locale: enUS,
    }).toLocaleLowerCase();

    const frasByDate = fras.filter((fra) => fra.date === eventDateStr);

    if (frasByDate.length > 0) {
        return ATCRatings[frasByDate[0].min_atc as ATCRating];
    }

    const frasByWeekDay = fras.filter((fra) => {
        const dayMatches = fra[
            `${dayOfWeek}` as keyof AtcPositionFra
        ] as boolean;

        return dayMatches;
        // return (
        //     dayMatches &&
        //     eventTimeStr >= fra.startTime &&
        //     eventTimeStr <= fra.endTime
        // );
    });

    return frasByWeekDay.length === 0
        ? ATCRatings[ATCRating.AS1]
        : ATCRatings[frasByWeekDay[0].min_atc as ATCRating];
}
</script>
<template>
    <div>
        <!-- Guest CTA -->
        <div
            v-if="!isLoggedIn"
            class="mb-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3"
        >
            <p class="text-sm text-emerald-700 dark:text-emerald-200/75">
                {{
                    $t(
                        'Log in to see slot availability and reserve your ATC position.',
                    )
                }}
            </p>
            <Link
                :href="auth.redirect()"
                class="shrink-0 rounded-full bg-emerald-600 px-4 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-500"
            >
                {{ $t('Log in to reserve') }}
            </Link>
        </div>

        <!-- Empty state -->
        <div
            v-if="atcSlots.length === 0"
            class="rounded-xl border border-slate-200 bg-slate-50 py-12 text-center dark:border-white/10 dark:bg-white/5"
        >
            <p class="text-sm text-slate-400 dark:text-white/40">
                {{ $t('No ATC slots available for this event.') }}
            </p>
        </div>
        <div
            v-else
            :class="{
                'grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3':
                    Object.keys(atcSlotsByCallsign).length > 2,
                'flex flex-col gap-4':
                    Object.keys(atcSlotsByCallsign).length <= 2,
            }"
        >
            <div
                v-for="(callsignContent, callsign) in atcSlotsByCallsign"
                :key="callsign"
                class="relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/3 dark:shadow-none"
            >
                <!-- Insufficient rating overlay -->
                <div
                    v-if="isLoggedIn && !callsignContent.can_reserve"
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 rounded-xl bg-slate-950/80 backdrop-blur-none"
                >
                    <Radio class="h-5 w-5 text-white/40" />
                    <p class="text-center text-sm font-semibold text-white/80">
                        {{ $t("You don't have the required rating") }}
                    </p>
                    <img
                        :src="callsignContent.min_atc_rating.imageUrl"
                        class="mt-1 w-20 opacity-70"
                        :alt="callsignContent.min_atc_rating.label"
                    />
                </div>
                <!-- Card header: callsign -->
                <div
                    class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5"
                >
                    <!-- Placeholder: future min ATC rating image -->
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-slate-400 dark:border-white/10 dark:bg-slate-900 dark:text-white/25"
                    >
                        <Radio class="h-4 w-4" />
                    </div>
                    <h3
                        class="font-mono text-sm font-bold tracking-wider text-slate-900 dark:text-white"
                    >
                        {{ callsign }}
                    </h3>
                    <div class="ml-auto flex items-center gap-2">
                        <span class="text-xs">min.</span>
                        <img
                            :src="callsignContent.min_atc_rating.imageUrl"
                            class="w-20"
                            :alt="callsignContent.min_atc_rating.label"
                        />
                    </div>
                </div>

                <!-- Time slot rows -->
                <div
                    class="flex flex-col divide-y divide-slate-100 dark:divide-white/5"
                >
                    <div
                        v-for="slot in callsignContent.slots"
                        :key="slot.id"
                        class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                    >
                        <!-- Time range -->
                        <p class="text-sm text-slate-700 dark:text-white/75">
                            {{ formatAtcTime(slot.starts_at) }}
                            <span
                                class="mx-1 text-slate-400 dark:text-white/35"
                            >
                                →
                            </span>
                            {{ formatAtcTime(slot.ends_at) }}
                        </p>

                        <!-- Right side: status + controller + reserve -->
                        <div class="flex items-center gap-2">
                            <Badge
                                v-if="
                                    isLoggedIn &&
                                    slot.status !== SlotStatus.AVAILABLE
                                "
                                :variant="
                                    SlotsConstants.statusVariants[slot.status]
                                "
                                class="border-yellow-500/90 bg-yellow-500/10 text-xs text-yellow-500"
                            >
                                {{ SlotsConstants.statusLabels[slot.status] }}
                            </Badge>
                            <Button
                                v-if="
                                    isLoggedIn &&
                                    slot.status === SlotStatus.AVAILABLE
                                "
                                variant="outline"
                                size="sm"
                                class="h-7 border-primary/90 px-2.5 text-xs text-primary hover:bg-primary/15 hover:text-primary/90"
                                :disabled="
                                    !callsignContent.can_reserve ||
                                    atcSlotForm.processing
                                "
                                @click="reserveAtcSlot(slot.id)"
                            >
                                {{
                                    atcSlotForm.processing
                                        ? $t('Processing...')
                                        : $t('Reserve')
                                }}
                            </Button>
                            <Button
                                v-if="
                                    isLoggedIn &&
                                    slot.atc_id === user.id &&
                                    slot.status !== SlotStatus.AVAILABLE
                                "
                                variant="destructive"
                                size="sm"
                                class="h-7"
                                :disabled="atcSlotForm.processing"
                                @click="cancelAtcSlotReservation(slot.id)"
                            >
                                {{
                                    atcSlotForm.processing
                                        ? $t('Processing...')
                                        : $t('Cancel')
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

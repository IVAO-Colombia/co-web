<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { PlaneTakeoff, Radio } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { useLocale } from '@/composables/useLocale';
import { formatDateTime } from '@/lib/utils';
import { SlotsConstants, SlotStatus } from '@/types';
import type { AtcSlot, Event, PilotSlot } from '@/types';
import dashboardAtcSlot from '@/routes/dashboard/events/atc-slot';
import dashboardPilotSlot from '@/routes/dashboard/events/pilot-slot';
import { index } from '@/routes/dashboard/reservations';
import { show as eventShow } from '@/routes/home/events';

type AtcSlotWithEvent = AtcSlot & { event: Event };
type PilotSlotWithEvent = PilotSlot & { event: Event };

defineProps<{
    atcSlots: AtcSlotWithEvent[];
    pilotSlots: PilotSlotWithEvent[];
}>();

setLayoutProps({
    breadcrumbs: [{ title: wTrans('My Reservations'), href: index() }],
});

const { locale } = useLocale();

type Tab = 'atc' | 'pilot';
const activeTab = ref<Tab>('atc');

const slotForm = useForm({});

function confirmAtcSlot(slot: AtcSlotWithEvent) {
    slotForm.patch(
        dashboardAtcSlot.update.url({
            event: slot.event.slug,
            slot: slot.id,
        }),
        {
            onSuccess: () =>
                toast.success(wTrans('ATC slot confirmed successfully.')),
        },
    );
}

function cancelAtcSlot(slot: AtcSlotWithEvent) {
    slotForm.delete(
        dashboardAtcSlot.destroy.url({
            event: slot.event.slug,
            slot: slot.id,
        }),
        {
            onSuccess: () =>
                toast.success(wTrans('ATC slot cancelled successfully.')),
        },
    );
}

function confirmPilotSlot(slot: PilotSlotWithEvent) {
    slotForm.patch(
        dashboardPilotSlot.update.url({
            event: slot.event.slug,
            slot: slot.id,
        }),
        {
            onSuccess: () =>
                toast.success(wTrans('Pilot slot confirmed successfully.')),
        },
    );
}

function cancelPilotSlot(slot: PilotSlotWithEvent) {
    slotForm.delete(
        dashboardPilotSlot.destroy.url({
            event: slot.event.slug,
            slot: slot.id,
        }),
        {
            onSuccess: () =>
                toast.success(wTrans('Pilot slot cancelled successfully.')),
        },
    );
}
</script>

<template>
    <Head :title="$t('Reservations')" />
    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <!-- Tab bar -->
        <div
            class="flex gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1 dark:border-white/10 dark:bg-white/5"
        >
            <button
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors"
                :class="
                    activeTab === 'atc'
                        ? 'bg-white text-emerald-700 shadow-sm dark:bg-white/10 dark:text-emerald-400'
                        : 'text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/75'
                "
                @click="activeTab = 'atc'"
            >
                <Radio class="h-4 w-4" />
                {{ $t('ATC Reservations') }}
                <span
                    v-if="atcSlots.length > 0"
                    class="rounded-full px-2 py-0.5 text-xs"
                    :class="
                        activeTab === 'atc'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                            : 'bg-slate-200 text-slate-500 dark:bg-white/10 dark:text-white/50'
                    "
                >
                    {{ atcSlots.length }}
                </span>
            </button>
            <button
                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors"
                :class="
                    activeTab === 'pilot'
                        ? 'bg-white text-sky-700 shadow-sm dark:bg-white/10 dark:text-sky-400'
                        : 'text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/75'
                "
                @click="activeTab = 'pilot'"
            >
                <PlaneTakeoff class="h-4 w-4" />
                {{ $t('Pilot Reservations') }}
                <span
                    v-if="pilotSlots.length > 0"
                    class="rounded-full px-2 py-0.5 text-xs"
                    :class="
                        activeTab === 'pilot'
                            ? 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400'
                            : 'bg-slate-200 text-slate-500 dark:bg-white/10 dark:text-white/50'
                    "
                >
                    {{ pilotSlots.length }}
                </span>
            </button>
        </div>

        <!-- ATC Reservations -->
        <div v-if="activeTab === 'atc'">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Radio class="h-5 w-5 text-emerald-600" />
                        {{ $t('ATC Reservations') }}
                    </CardTitle>
                    <CardDescription>
                        {{ $t('Your reserved and confirmed ATC positions.') }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Event') }}</TableHead>
                                <TableHead>{{ $t('Callsign') }}</TableHead>
                                <TableHead>{{ $t('Starts At') }}</TableHead>
                                <TableHead>{{ $t('Ends At') }}</TableHead>
                                <TableHead>{{ $t('Status') }}</TableHead>
                                <TableHead class="w-1" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableEmpty
                                v-if="atcSlots.length === 0"
                                :colspan="6"
                            >
                                {{ $t('No ATC reservations yet.') }}
                            </TableEmpty>
                            <TableRow v-for="slot in atcSlots" :key="slot.id">
                                <TableCell>
                                    <a
                                        :href="
                                            eventShow.url({
                                                event: slot.event.slug,
                                            })
                                        "
                                        target="_blank"
                                        class="font-medium text-slate-900 underline-offset-4 hover:underline dark:text-white"
                                    >
                                        {{ slot.event.name }}
                                    </a>
                                </TableCell>
                                <TableCell class="font-mono font-semibold">
                                    {{ slot.callsign }}
                                </TableCell>
                                <TableCell
                                    class="text-slate-600 dark:text-white/65"
                                >
                                    {{ formatDateTime(slot.starts_at, locale) }}
                                </TableCell>
                                <TableCell
                                    class="text-slate-600 dark:text-white/65"
                                >
                                    {{ formatDateTime(slot.ends_at, locale) }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            SlotsConstants.statusVariants[
                                                slot.status
                                            ]
                                        "
                                    >
                                        {{
                                            SlotsConstants.statusLabels[
                                                slot.status
                                            ]
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button
                                            v-if="
                                                slot.status ===
                                                SlotStatus.RESERVED
                                            "
                                            variant="outline"
                                            size="sm"
                                            class="h-7 border-emerald-500/70 px-2.5 text-xs text-emerald-700 hover:bg-emerald-50 hover:text-emerald-700 dark:border-emerald-500/40 dark:text-emerald-400 dark:hover:bg-emerald-500/10"
                                            :disabled="slotForm.processing"
                                            @click="confirmAtcSlot(slot)"
                                        >
                                            {{ $t('Confirm') }}
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            class="h-7"
                                            :disabled="slotForm.processing"
                                            @click="cancelAtcSlot(slot)"
                                        >
                                            {{ $t('Cancel') }}
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>

        <!-- Pilot Reservations -->
        <div v-if="activeTab === 'pilot'">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <PlaneTakeoff class="h-5 w-5 text-sky-600" />
                        {{ $t('Pilot Reservations') }}
                    </CardTitle>
                    <CardDescription>
                        {{ $t('Your reserved and confirmed pilot slots.') }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Event') }}</TableHead>
                                <TableHead>{{ $t('Flight') }}</TableHead>
                                <TableHead>{{ $t('Route') }}</TableHead>
                                <TableHead>{{ $t('Aircraft') }}</TableHead>
                                <TableHead>{{ $t('Departs At') }}</TableHead>
                                <TableHead>{{ $t('Status') }}</TableHead>
                                <TableHead class="w-1" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableEmpty
                                v-if="pilotSlots.length === 0"
                                :colspan="7"
                            >
                                {{ $t('No pilot reservations yet.') }}
                            </TableEmpty>
                            <TableRow v-for="slot in pilotSlots" :key="slot.id">
                                <TableCell>
                                    <a
                                        :href="
                                            eventShow.url({
                                                event: slot.event.slug,
                                            })
                                        "
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="font-medium text-slate-900 underline-offset-4 hover:underline dark:text-white"
                                    >
                                        {{ slot.event.name }}
                                    </a>
                                </TableCell>
                                <TableCell class="font-mono font-semibold">
                                    {{ slot.airline_icao
                                    }}{{ slot.flight_number }}
                                </TableCell>
                                <TableCell>
                                    <span
                                        class="inline-flex items-center gap-1 font-mono text-slate-700 dark:text-white/75"
                                    >
                                        {{ slot.origin }}
                                        <span
                                            class="text-slate-400 dark:text-white/35"
                                            >→</span
                                        >
                                        {{ slot.destination }}
                                    </span>
                                </TableCell>
                                <TableCell
                                    class="text-slate-600 dark:text-white/65"
                                >
                                    {{ slot.aircraft }}
                                </TableCell>
                                <TableCell
                                    class="text-slate-600 dark:text-white/65"
                                >
                                    {{
                                        formatDateTime(slot.departs_at, locale)
                                    }}
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            SlotsConstants.statusVariants[
                                                slot.status
                                            ]
                                        "
                                    >
                                        {{
                                            SlotsConstants.statusLabels[
                                                slot.status
                                            ]
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button
                                            v-if="
                                                slot.status ===
                                                SlotStatus.RESERVED
                                            "
                                            variant="outline"
                                            size="sm"
                                            class="h-7 border-sky-500/70 px-2.5 text-xs text-sky-700 hover:bg-sky-50 hover:text-sky-700 dark:border-sky-500/40 dark:text-sky-400 dark:hover:bg-sky-500/10"
                                            :disabled="slotForm.processing"
                                            @click="confirmPilotSlot(slot)"
                                        >
                                            {{ $t('Confirm') }}
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            class="h-7"
                                            :disabled="slotForm.processing"
                                            @click="cancelPilotSlot(slot)"
                                        >
                                            {{ $t('Cancel') }}
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

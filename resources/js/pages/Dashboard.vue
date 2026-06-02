<script setup lang="ts">
import { Head, Link, setLayoutProps } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { ArrowRight, PlaneTakeoff, Radio, Clock } from 'lucide-vue-next';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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
import { ATCRatings, PilotRatings } from '@/types';
import type { ATCRating, PilotRating } from '@/types';
import { dashboard } from '@/routes';
import { index as reservationsIndex } from '@/routes/dashboard/reservations';
import { index as trainingsIndex } from '@/routes/dashboard/trainings';

interface TrackerSession {
    id: number;
    callsign: string;
    connectionType: string;
    time: number;
    createdAt: string;
    completedAt: string | null;
}

const props = defineProps<{
    hours: { pilot: number | null; atc: number | null; staff: number | null };
    trackerSessions: TrackerSession[];
    activeTrainingRequestsCount: number;
    reservationsCount: { atc: number; pilot: number };
    atcRating: ATCRating | null;
    pilotRating: PilotRating | null;
}>();

setLayoutProps({
    breadcrumbs: [{ title: wTrans('Dashboard'), href: dashboard() }],
});

const { locale } = useLocale();

function formatHours(seconds: number): string {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);

    return `${h}h ${m}min`;
}

const pilotRating = computed(() =>
    props.pilotRating ? PilotRatings[props.pilotRating] : null,
);
const atcRating = computed(() =>
    props.atcRating ? ATCRatings[props.atcRating] : null,
);
</script>

<template>
    <Head :title="$t('Dashboard')" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <!-- Hours cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <Card v-if="props.hours.pilot !== null">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('Pilot Hours') }}
                    </CardTitle>
                    <PlaneTakeoff class="size-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-3xl font-bold">
                        {{ formatHours(props.hours.pilot) }}
                    </div>
                </CardContent>
            </Card>

            <Card v-if="props.hours.atc !== null">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('ATC Hours') }}
                    </CardTitle>
                    <Radio class="size-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-3xl font-bold">
                        {{ formatHours(props.hours.atc) }}
                    </div>
                </CardContent>
            </Card>

            <Card v-if="props.hours.staff !== null">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('Staff Hours') }}
                    </CardTitle>
                    <Clock class="size-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-3xl font-bold">
                        {{ formatHours(props.hours.staff) }}
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Ratings -->
        <div
            v-if="atcRating || pilotRating"
            class="grid auto-rows-min gap-4 md:grid-cols-2"
        >
            <Card v-if="atcRating">
                <CardHeader class="pb-2">
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('ATC Rating') }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        :src="atcRating.imageUrl"
                        :alt="atcRating.label"
                        class="h-10 w-auto"
                    />
                    <div>
                        <div class="text-lg font-bold">
                            {{ atcRating.key }}
                        </div>
                        <div class="text-sm text-muted-foreground">
                            {{ atcRating.label }}
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card v-if="pilotRating">
                <CardHeader class="pb-2">
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('Pilot Rating') }}
                    </CardTitle>
                </CardHeader>
                <CardContent class="flex items-center gap-4">
                    <img
                        :src="pilotRating.imageUrl"
                        :alt="pilotRating.label"
                        class="h-10 w-auto"
                    />
                    <div>
                        <div class="text-lg font-bold">
                            {{ pilotRating.key }}
                        </div>
                        <div class="text-sm text-muted-foreground">
                            {{ pilotRating.label }}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Training requests & Reservations -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('Active Training Requests') }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-3xl font-bold">
                        {{ props.activeTrainingRequestsCount }}
                    </div>
                    <Link
                        :href="trainingsIndex().url"
                        class="mt-2 inline-flex items-center gap-1 text-sm text-primary hover:underline"
                    >
                        {{ $t('View trainings') }}
                        <ArrowRight class="size-3" />
                    </Link>
                </CardContent>
            </Card>

            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle
                        class="text-sm font-medium tracking-wide text-muted-foreground uppercase"
                    >
                        {{ $t('Event Reservations') }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-bold">
                            {{
                                props.reservationsCount.atc +
                                props.reservationsCount.pilot
                            }}
                        </span>
                        <span class="text-sm text-muted-foreground">
                            ({{ props.reservationsCount.atc }} ATC ·
                            {{ props.reservationsCount.pilot }}
                            {{ $t('Pilot') }})
                        </span>
                    </div>
                    <Link
                        :href="reservationsIndex().url"
                        class="mt-2 inline-flex items-center gap-1 text-sm text-primary hover:underline"
                    >
                        {{ $t('View reservations') }}
                        <ArrowRight class="size-3" />
                    </Link>
                </CardContent>
            </Card>
        </div>

        <!-- Recent tracker sessions -->
        <Card>
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle class="text-base">{{
                    $t('Recent Connections')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ $t('Callsign') }}</TableHead>
                            <TableHead>{{ $t('Type') }}</TableHead>
                            <TableHead>{{ $t('Start') }}</TableHead>
                            <TableHead>{{ $t('End') }}</TableHead>
                            <TableHead>{{ $t('Duration') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableEmpty
                            v-if="props.trackerSessions.length === 0"
                            :colspan="5"
                        >
                            {{ $t('No recent connections found.') }}
                        </TableEmpty>
                        <TableRow
                            v-for="session in props.trackerSessions"
                            :key="session.id"
                        >
                            <TableCell class="font-mono font-semibold">{{
                                session.callsign
                            }}</TableCell>
                            <TableCell class="capitalize">{{
                                session.connectionType
                            }}</TableCell>
                            <TableCell>{{
                                formatDateTime(session.createdAt, locale)
                            }}</TableCell>
                            <TableCell>
                                <span v-if="session.completedAt">{{
                                    formatDateTime(session.completedAt, locale)
                                }}</span>
                                <span v-else class="text-muted-foreground"
                                    >—</span
                                >
                            </TableCell>
                            <TableCell>{{
                                formatHours(session.time)
                            }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>

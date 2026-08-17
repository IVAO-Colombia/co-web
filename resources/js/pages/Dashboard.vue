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
import { dashboard } from '@/routes';
import { index as reservationsIndex } from '@/routes/dashboard/reservations';
import { index as trainingsIndex } from '@/routes/dashboard/trainings';
import { ATCRatings, PilotRatings } from '@/types';
import type { ATCRating, PilotRating } from '@/types';

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

    <div class="flex flex-col gap-6 p-6">
        <!-- Hero -->
        <div
            class="relative overflow-hidden rounded-3xl border bg-gradient-to-br from-primary/15 via-background to-background p-8"
        >
            <div class="absolute inset-0 opacity-30 blur-3xl">
                <div
                    class="absolute top-0 right-0 h-48 w-48 rounded-full bg-primary/20"
                />
            </div>

            <div
                class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">
                        {{ $t('Dashboard') }}
                    </h1>

                    <p class="mt-2 text-muted-foreground">
                        {{ $t('Track your activity, ratings and progress.') }}
                    </p>
                </div>

                <div v-if="atcRating || pilotRating" class="flex gap-3">
                    <div
                        v-if="atcRating"
                        class="flex items-center gap-3 rounded-2xl bg-background/60 px-4 py-3 backdrop-blur"
                    >
                        <img
                            :src="atcRating.imageUrl"
                            :alt="atcRating.label"
                            class="h-8 w-auto object-contain"
                        />
                        <div>
                            <p
                                class="text-xs tracking-wide text-muted-foreground uppercase"
                            >
                                {{ $t('ATC') }}
                            </p>
                            <p class="leading-none font-bold">
                                {{ atcRating.key }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="pilotRating"
                        class="flex items-center gap-3 rounded-2xl bg-background/60 px-4 py-3 backdrop-blur"
                    >
                        <img
                            :src="pilotRating.imageUrl"
                            :alt="pilotRating.label"
                            class="h-8 w-auto object-contain"
                        />
                        <div>
                            <p
                                class="text-xs tracking-wide text-muted-foreground uppercase"
                            >
                                {{ $t('Pilot') }}
                            </p>
                            <p class="leading-none font-bold">
                                {{ pilotRating.key }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 md:grid-cols-4">
            <Card
                v-if="props.hours.pilot !== null"
                class="rounded-3xl border-border/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
                <CardContent class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                            >
                                {{ $t('Pilot Hours') }}
                            </p>

                            <div class="mt-3 text-4xl font-bold">
                                {{ formatHours(props.hours.pilot) }}
                            </div>
                        </div>

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10"
                        >
                            <PlaneTakeoff class="size-6 text-primary" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card
                v-if="props.hours.atc !== null"
                class="rounded-3xl border-border/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
                <CardContent class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                            >
                                {{ $t('ATC Hours') }}
                            </p>

                            <div class="mt-3 text-4xl font-bold">
                                {{ formatHours(props.hours.atc) }}
                            </div>
                        </div>

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10"
                        >
                            <Radio class="size-6 text-primary" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card
                v-if="props.hours.staff !== null"
                class="rounded-3xl border-border/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
                <CardContent class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p
                                class="text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                            >
                                {{ $t('Staff Hours') }}
                            </p>

                            <div class="mt-3 text-4xl font-bold">
                                {{ formatHours(props.hours.staff) }}
                            </div>
                        </div>

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10"
                        >
                            <Clock class="size-6 text-primary" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card
                class="rounded-3xl border-border/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
            >
                <CardContent class="p-6">
                    <p
                        class="text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                    >
                        {{ $t('Active Trainings') }}
                    </p>

                    <div class="mt-3 text-4xl font-bold">
                        {{ props.activeTrainingRequestsCount }}
                    </div>

                    <Link
                        :href="trainingsIndex().url"
                        class="mt-3 inline-flex items-center gap-1 text-sm text-primary"
                    >
                        {{ $t('View') }}
                        <ArrowRight class="size-3" />
                    </Link>
                </CardContent>
            </Card>
        </div>

        <!-- Reservations -->
        <Card class="rounded-3xl border-border/50">
            <CardContent class="p-6">
                <div
                    class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <p
                            class="text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ $t('Event Reservations') }}
                        </p>

                        <div class="mt-2 text-4xl font-bold">
                            {{
                                props.reservationsCount.atc +
                                props.reservationsCount.pilot
                            }}
                        </div>

                        <div class="mt-1 text-sm text-muted-foreground">
                            {{ props.reservationsCount.atc }} ATC ·
                            {{ props.reservationsCount.pilot }}
                            {{ $t('Pilot') }}
                        </div>
                    </div>

                    <Link
                        :href="reservationsIndex().url"
                        class="inline-flex items-center gap-2 text-primary"
                    >
                        {{ $t('View reservations') }}
                        <ArrowRight class="size-4" />
                    </Link>
                </div>
            </CardContent>
        </Card>

        <!-- Connections -->
        <Card class="rounded-3xl border-border/50">
            <CardHeader>
                <CardTitle>{{ $t('Recent Connections') }}</CardTitle>
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
                            class="transition-colors hover:bg-muted/50"
                        >
                            <TableCell>
                                <span
                                    class="rounded-lg bg-primary/10 px-3 py-1 font-mono text-primary"
                                >
                                    {{ session.callsign }}
                                </span>
                            </TableCell>

                            <TableCell class="capitalize">
                                {{ session.connectionType }}
                            </TableCell>

                            <TableCell>
                                {{ formatDateTime(session.createdAt, locale) }}
                            </TableCell>

                            <TableCell>
                                <span v-if="session.completedAt">
                                    {{
                                        formatDateTime(
                                            session.completedAt,
                                            locale,
                                        )
                                    }}
                                </span>

                                <span v-else class="text-muted-foreground">
                                    —
                                </span>
                            </TableCell>

                            <TableCell>
                                {{ formatHours(session.time) }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>

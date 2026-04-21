<script setup lang="ts">
import { Head, Link, router, setLayoutProps } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';
import {
    CalendarDays,
    ChevronLeft,
    MapPin,
    PlaneTakeoff,
    Radio,
    Trash2,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import { usePermissions } from '@/composables/usePermissions';
import { formatDateTime } from '@/lib/utils';
import type { EventDetail } from '@/types';
import {
    EventConstants,
    Permission,
    SlotsConstants,
    SlotStatus,
} from '@/types';
import { destroy, index, show as showRoute } from '@/routes/events';

const props = defineProps<{
    event: EventDetail;
}>();

setLayoutProps({
    breadcrumbs: [
        { title: wTrans('Events'), href: index() },
        {
            title: props.event.name,
            href: showRoute.url({ event: props.event.slug }),
        },
    ],
});

const { hasPermission } = usePermissions();

const deleteDescription = computed(() =>
    trans(
        'Are you sure you want to delete ":name"? This will also delete all unreserved slots and cannot be undone.',
        { name: props.event.name },
    ),
);

const showDeleteDialog = ref(false);
const deleting = ref(false);

const canDelete = computed(() => {
    const hasReservedPilot = props.event.pilot_slots.some(
        (s) => s.status === SlotStatus.UNAVAILABLE,
    );
    const hasReservedAtc = props.event.atc_slots.some(
        (s) => s.status === SlotStatus.UNAVAILABLE,
    );

    return !hasReservedPilot && !hasReservedAtc;
});

function confirmDelete(): void {
    deleting.value = true;
    router.delete(destroy.url({ event: props.event.slug }), {
        onSuccess: () => {
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            showDeleteDialog.value = false;
            toast.error(errors.event ?? wTrans('Something went wrong.'));
        },
        onFinish: () => {
            deleting.value = false;
        },
    });
}
</script>

<template>
    <Head :title="event.name" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <!-- Page header -->
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <img
                    v-if="event.image_url"
                    :src="event.image_url"
                    :alt="event.name"
                    class="size-16 shrink-0 rounded-lg border object-cover"
                />
                <div
                    v-else
                    class="flex size-16 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground"
                >
                    <CalendarDays class="size-7" />
                </div>
                <div class="min-w-0">
                    <h1
                        class="text-2xl leading-tight font-semibold tracking-tight"
                    >
                        {{ event.name }}
                    </h1>
                    <p
                        v-if="event.name_en"
                        class="text-sm text-muted-foreground"
                    >
                        {{ event.name_en }}
                    </p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <Badge
                            :variant="
                                EventConstants.statusVariants[event.status]
                            "
                        >
                            {{ EventConstants.statusLabels[event.status] }}
                        </Badge>
                        <Badge variant="outline">
                            {{ EventConstants.typeLabels[event.type] }}
                        </Badge>
                        <Badge
                            v-for="tag in event.tags"
                            :key="tag"
                            variant="secondary"
                            class="text-xs"
                        >
                            {{ EventConstants.tagLabels[tag] }}
                        </Badge>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="index()">
                        <ChevronLeft class="size-4" />
                        {{ $t('Back to Events') }}
                    </Link>
                </Button>
                <Button
                    v-if="hasPermission(Permission.UPDATE_EVENTS)"
                    size="sm"
                    as-child
                >
                    <Link :href="`/dashboard/events/${event.slug}/edit`">
                        {{ $t('Edit') }}
                    </Link>
                </Button>
                <TooltipProvider v-if="hasPermission(Permission.DELETE_EVENTS)">
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <span>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    :disabled="!canDelete"
                                    @click="showDeleteDialog = true"
                                >
                                    <Trash2 class="size-4" />
                                    {{ $t('Delete') }}
                                </Button>
                            </span>
                        </TooltipTrigger>
                        <TooltipContent v-if="!canDelete">
                            {{ $t('Cannot delete: event has reserved slots.') }}
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            </div>
        </div>

        <!-- Details Card -->
        <Card>
            <CardHeader>
                <CardTitle>{{ $t('Event Details') }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-6">
                <!-- Descriptions -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-medium">
                            {{ $t('Description') }}
                            <span class="ml-1 text-xs text-muted-foreground"
                                >(ES)</span
                            >
                        </p>
                        <p
                            class="text-sm whitespace-pre-wrap text-muted-foreground"
                        >
                            {{ event.description }}
                        </p>
                    </div>
                    <div
                        v-if="event.description_en"
                        class="flex flex-col gap-1"
                    >
                        <p class="text-sm font-medium">
                            {{ $t('Description') }}
                            <span class="ml-1 text-xs text-muted-foreground"
                                >(EN)</span
                            >
                        </p>
                        <p
                            class="text-sm whitespace-pre-wrap text-muted-foreground"
                        >
                            {{ event.description_en }}
                        </p>
                    </div>
                </div>

                <Separator />

                <!-- Meta grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-medium">{{ $t('Location') }}</p>
                        <div
                            class="flex items-center gap-1.5 text-sm text-muted-foreground"
                        >
                            <MapPin class="size-3.5 shrink-0" />
                            {{ event.locations }}
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-medium">{{ $t('Type') }}</p>
                        <p class="text-sm text-muted-foreground">
                            {{ EventConstants.typeLabels[event.type] }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-medium">{{ $t('Starts At') }}</p>
                        <div
                            class="flex items-center gap-1.5 text-sm text-muted-foreground"
                        >
                            <CalendarDays class="size-3.5 shrink-0" />
                            {{ formatDateTime(event.starts_at) }}
                        </div>
                    </div>
                    <div v-if="event.ends_at" class="flex flex-col gap-1">
                        <p class="text-sm font-medium">{{ $t('Ends At') }}</p>
                        <div
                            class="flex items-center gap-1.5 text-sm text-muted-foreground"
                        >
                            <CalendarDays class="size-3.5 shrink-0" />
                            {{ formatDateTime(event.ends_at) }}
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Pilot Slots Card -->
        <Card v-if="event.pilot_slots_enabled">
            <CardHeader>
                <div class="flex items-center justify-between gap-4">
                    <CardTitle class="flex items-center gap-2">
                        <PlaneTakeoff
                            class="size-4 text-sky-600 dark:text-sky-400"
                        />
                        {{ $t('Pilot Slots') }}
                    </CardTitle>
                    <Badge variant="outline">
                        {{ event.pilot_slots.length }}
                    </Badge>
                </div>
                <CardDescription>
                    {{ $t('Pilot slot assignments for this event.') }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="overflow-auto rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Callsign') }}</TableHead>
                                <TableHead>{{ $t('Flight #') }}</TableHead>
                                <TableHead>{{ $t('Aircraft') }}</TableHead>
                                <TableHead>{{ $t('Origin') }}</TableHead>
                                <TableHead>{{ $t('Destination') }}</TableHead>
                                <TableHead>{{ $t('Departs At') }}</TableHead>
                                <TableHead>{{ $t('Gate') }}</TableHead>
                                <TableHead>{{ $t('Status') }}</TableHead>
                                <TableHead>{{ $t('Reserved By') }}</TableHead>
                                <TableHead class="w-10" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableEmpty
                                v-if="event.pilot_slots.length === 0"
                                :colspan="10"
                            >
                                <p class="py-6 text-sm text-muted-foreground">
                                    {{ $t('No pilot slots added yet.') }}
                                </p>
                            </TableEmpty>
                            <TableRow
                                v-for="slot in event.pilot_slots"
                                :key="slot.id"
                            >
                                <TableCell class="font-mono">
                                    {{ slot.callsign }}
                                </TableCell>
                                <TableCell>
                                    {{ slot.flight_number ?? '—' }}
                                </TableCell>
                                <TableCell>{{ slot.aircraft }}</TableCell>
                                <TableCell class="font-mono">
                                    {{ slot.origin }}
                                </TableCell>
                                <TableCell class="font-mono">
                                    {{ slot.destination }}
                                </TableCell>
                                <TableCell>{{ slot.departs_at }}</TableCell>
                                <TableCell>{{ slot.gate ?? '—' }}</TableCell>
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
                                <TableCell
                                    class="text-sm text-muted-foreground"
                                >
                                    {{
                                        slot.pilot
                                            ? `${slot.pilot.name} (${slot.pilot.vid})`
                                            : '—'
                                    }}
                                </TableCell>
                                <TableCell>
                                    <TooltipProvider
                                        v-if="
                                            slot.status ===
                                            SlotStatus.UNAVAILABLE
                                        "
                                    >
                                        <Tooltip>
                                            <TooltipTrigger as-child>
                                                <span>
                                                    <Button
                                                        variant="ghost"
                                                        size="sm"
                                                        disabled
                                                    >
                                                        {{ $t('Cancel') }}
                                                    </Button>
                                                </span>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                {{ $t('Coming soon') }}
                                            </TooltipContent>
                                        </Tooltip>
                                    </TooltipProvider>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </CardContent>
        </Card>

        <!-- ATC Slots Card -->
        <Card v-if="event.atc_slots_enabled">
            <CardHeader>
                <div class="flex items-center justify-between gap-4">
                    <CardTitle class="flex items-center gap-2">
                        <Radio
                            class="size-4 text-emerald-600 dark:text-emerald-400"
                        />
                        {{ $t('ATC Slots') }}
                    </CardTitle>
                    <Badge variant="outline">
                        {{ event.atc_slots.length }}
                    </Badge>
                </div>
                <CardDescription>
                    {{ $t('ATC slot assignments for this event.') }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="overflow-auto rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Callsign') }}</TableHead>
                                <TableHead>{{ $t('Starts At') }}</TableHead>
                                <TableHead>{{ $t('Ends At') }}</TableHead>
                                <TableHead>{{ $t('Status') }}</TableHead>
                                <TableHead>{{ $t('Reserved By') }}</TableHead>
                                <TableHead class="w-10" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableEmpty
                                v-if="event.atc_slots.length === 0"
                                :colspan="6"
                            >
                                <p class="py-6 text-sm text-muted-foreground">
                                    {{ $t('No ATC slots added yet.') }}
                                </p>
                            </TableEmpty>
                            <TableRow
                                v-for="slot in event.atc_slots"
                                :key="slot.id"
                            >
                                <TableCell class="font-mono">
                                    {{ slot.callsign }}
                                </TableCell>
                                <TableCell>{{ slot.starts_at }}</TableCell>
                                <TableCell>{{ slot.ends_at }}</TableCell>
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
                                <TableCell
                                    class="text-sm text-muted-foreground"
                                >
                                    {{
                                        slot.atc
                                            ? `${slot.atc.name} (${slot.atc.vid})`
                                            : '—'
                                    }}
                                </TableCell>
                                <TableCell>
                                    <TooltipProvider
                                        v-if="
                                            slot.status ===
                                            SlotStatus.UNAVAILABLE
                                        "
                                    >
                                        <Tooltip>
                                            <TooltipTrigger as-child>
                                                <span>
                                                    <Button
                                                        variant="ghost"
                                                        size="sm"
                                                        disabled
                                                    >
                                                        {{ $t('Cancel') }}
                                                    </Button>
                                                </span>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                {{ $t('Coming soon') }}
                                            </TooltipContent>
                                        </Tooltip>
                                    </TooltipProvider>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </CardContent>
        </Card>

        <!-- Delete confirmation dialog -->
        <DeleteDialog
            :open="showDeleteDialog"
            :title="$t('Delete Event')"
            :description="deleteDescription"
            :processing="deleting"
            @update:open="showDeleteDialog = $event"
            @confirm="confirmDelete"
        />
    </div>
</template>

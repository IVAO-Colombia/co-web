<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import {
    CalendarDays,
    ChevronLeft,
    ChevronRight,
    MapPin,
    PlaneTakeoff,
    Radio,
    X,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
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
import { useDebounce } from '@/composables/useDebounce';
import { index } from '@/routes/events';
import type { LengthAwarePaginator, Event } from '@/types';
import { EventStatus, EventType } from '@/types';

const props = defineProps<{
    events: LengthAwarePaginator<number, Event>;
    filters: {
        query?: string;
        status?: EventStatus;
        type?: EventType;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Events', href: index() }],
    },
});

const query = ref(props.filters.query ?? '');
const status = ref<EventStatus | ''>(props.filters.status ?? '');
const type = ref<EventType | ''>(props.filters.type ?? '');
const debouncedApplyFilters = useDebounce(applyFilters, 350);
const links = computed(() =>
    props.events.links.filter(
        (link) =>
            !link.label.includes('&laquo;') && !link.label.includes('&raquo;'),
    ),
);

function applyFilters(): void {
    router.get(
        index(),
        {
            query: query.value || undefined,
            status: status.value || undefined,
            type: type.value || undefined,
        },
        { preserveState: true, replace: true },
    );
}

watch(query, () => debouncedApplyFilters());
watch([status, type], applyFilters);

function clearFilters(): void {
    query.value = '';
    status.value = '';
    type.value = '';
}

const hasActiveFilters = () =>
    query.value !== '' || status.value !== '' || type.value !== '';

const statusVariant: Record<
    EventStatus,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    [EventStatus.ACTIVE]: 'default',
    [EventStatus.DRAFT]: 'secondary',
    [EventStatus.CANCELLED]: 'destructive',
    [EventStatus.FINALIZED]: 'outline',
};

const statusLabel: Record<EventStatus, string> = {
    [EventStatus.ACTIVE]: 'Active',
    [EventStatus.DRAFT]: 'Draft',
    [EventStatus.CANCELLED]: 'Cancelled',
    [EventStatus.FINALIZED]: 'Finalized',
};

const typeLabel: Record<EventType, string> = {
    [EventType.ONLINE_DAY]: 'Online Day',
    [EventType.EXAM]: 'Exam',
    [EventType.TRAINING]: 'Training',
    [EventType.RFO]: 'RFO',
    [EventType.RFE]: 'RFE',
};

function formatDate(dateStr: string): string {
    return new Intl.DateTimeFormat('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(dateStr));
}
</script>

<template>
    <Head title="Events" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Events</h1>
                <p class="text-sm text-muted-foreground">
                    {{ events.total.toLocaleString() }}
                    event{{ events.total !== 1 ? 's' : '' }}
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative min-w-56 flex-1">
                <Input
                    v-model="query"
                    placeholder="Search by name or location…"
                    class="pr-8"
                />
                <button
                    v-if="query"
                    class="absolute top-1/2 right-2.5 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    @click="query = ''"
                >
                    <X class="size-3.5" />
                </button>
            </div>

            <Select v-model="status">
                <SelectTrigger class="w-36">
                    <SelectValue placeholder="All statuses" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="active">Active</SelectItem>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="cancelled">Cancelled</SelectItem>
                    <SelectItem value="finalized">Finalized</SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="type">
                <SelectTrigger class="w-36">
                    <SelectValue placeholder="All types" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="online_day">Online Day</SelectItem>
                    <SelectItem value="exam">Exam</SelectItem>
                    <SelectItem value="training">Training</SelectItem>
                    <SelectItem value="rfo">RFO</SelectItem>
                    <SelectItem value="rfe">RFE</SelectItem>
                </SelectContent>
            </Select>

            <Button
                v-if="hasActiveFilters()"
                variant="ghost"
                size="sm"
                @click="clearFilters"
            >
                <X class="size-3.5" />
                Clear filters
            </Button>
        </div>

        <!-- Table -->
        <div class="rounded-lg border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[280px]">Event</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Location</TableHead>
                        <TableHead>Starts</TableHead>
                        <TableHead>Slots</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="w-16" />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableEmpty v-if="events.data.length === 0" :colspan="7">
                        <div class="flex flex-col items-center gap-2 py-8">
                            <CalendarDays
                                class="size-8 text-muted-foreground"
                            />
                            <p class="text-sm text-muted-foreground">
                                No events match your filters.
                            </p>
                            <Button
                                v-if="hasActiveFilters()"
                                variant="outline"
                                size="sm"
                                @click="clearFilters"
                            >
                                Clear filters
                            </Button>
                        </div>
                    </TableEmpty>

                    <TableRow
                        v-for="event in events.data"
                        :key="event.id"
                        class="group"
                    >
                        <!-- Name -->
                        <TableCell>
                            <div class="flex items-center gap-3">
                                <img
                                    v-if="event.image_url"
                                    :src="event.image_url"
                                    :alt="event.name"
                                    class="size-9 shrink-0 rounded-md object-cover"
                                />
                                <div
                                    v-else
                                    class="flex size-9 shrink-0 items-center justify-center rounded-md bg-muted text-muted-foreground"
                                >
                                    <CalendarDays class="size-4" />
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="truncate leading-tight font-medium"
                                    >
                                        {{ event.name_en ?? event.name }}
                                    </p>
                                    <div class="mt-0.5 flex flex-wrap gap-1">
                                        <Badge
                                            v-for="tag in event.tags"
                                            :key="tag"
                                            variant="secondary"
                                            class="text-xs"
                                        >
                                            {{ tag }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </TableCell>

                        <!-- Type -->
                        <TableCell>
                            <Badge variant="outline">
                                {{ typeLabel[event.type] }}
                            </Badge>
                        </TableCell>

                        <!-- Location -->
                        <TableCell>
                            <div class="flex items-center gap-1.5">
                                <MapPin
                                    class="size-3.5 shrink-0 text-muted-foreground"
                                />
                                <span class="text-sm">{{
                                    event.locations
                                }}</span>
                            </div>
                        </TableCell>

                        <!-- Starts at -->
                        <TableCell class="text-sm whitespace-nowrap">
                            {{ formatDate(event.starts_at) }}
                        </TableCell>

                        <!-- Slot indicators -->
                        <TableCell>
                            <div class="flex gap-1.5">
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger
                                            ><span
                                                :class="
                                                    event.pilot_slots_enabled
                                                        ? 'text-sky-600 dark:text-sky-400'
                                                        : 'text-muted-foreground/30'
                                                "
                                            >
                                                <PlaneTakeoff
                                                    class="size-4"
                                                /> </span
                                        ></TooltipTrigger>
                                        <TooltipContent>
                                            <p>
                                                {{
                                                    event.pilot_slots_enabled
                                                        ? 'Pilot slots enabled'
                                                        : 'Pilot slots disabled'
                                                }}
                                            </p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>

                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <span
                                                :class="
                                                    event.atc_slots_enabled
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : 'text-muted-foreground/30'
                                                "
                                            >
                                                <Radio class="size-4" />
                                            </span>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>
                                                {{
                                                    event.atc_slots_enabled
                                                        ? 'ATC slots enabled'
                                                        : 'ATC slots disabled'
                                                }}
                                            </p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </div>
                        </TableCell>

                        <!-- Status -->
                        <TableCell>
                            <Badge :variant="statusVariant[event.status]">
                                {{ statusLabel[event.status] }}
                            </Badge>
                        </TableCell>

                        <!-- Actions -->
                        <TableCell>
                            <Button variant="ghost" size="sm" as-child>
                                <Link :href="`/events/${event.slug}`">
                                    View
                                </Link>
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div
            v-if="events.last_page > 1"
            class="flex items-center justify-between"
        >
            <p class="text-sm text-muted-foreground">
                Showing {{ events.from }}–{{ events.to }} of
                {{ events.total.toLocaleString() }}
            </p>

            <div class="flex items-center gap-1">
                <Button
                    variant="outline"
                    size="icon-sm"
                    :disabled="events.current_page === 1"
                    as-child
                >
                    <Link
                        v-if="events.prev_page_url"
                        :href="events.prev_page_url"
                        preserve-state
                    >
                        <ChevronLeft class="size-4" />
                    </Link>
                    <span v-else>
                        <ChevronLeft class="size-4" />
                    </span>
                </Button>

                <template v-for="link in links" :key="link.label">
                    <Button
                        v-if="link.page !== null && link.url"
                        :variant="link.active ? 'default' : 'outline'"
                        size="icon-sm"
                        as-child
                    >
                        <Link :href="link.url" preserve-state>
                            {{ link.label }}
                        </Link>
                    </Button>
                    <span
                        v-else-if="link.label === '...'"
                        class="px-1 text-sm text-muted-foreground"
                    >
                        …
                    </span>
                </template>

                <Button
                    variant="outline"
                    size="icon-sm"
                    :disabled="events.current_page === events.last_page"
                    as-child
                >
                    <Link
                        v-if="events.next_page_url"
                        :href="events.next_page_url"
                        preserve-state
                    >
                        <ChevronRight class="size-4" />
                    </Link>
                    <span v-else>
                        <ChevronRight class="size-4" />
                    </span>
                </Button>
            </div>
        </div>
    </div>
</template>

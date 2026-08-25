<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { trans, transChoice, wTrans } from 'laravel-vue-i18n';
import { ChevronLeft, ChevronRight, Eye, Users, X } from 'lucide-vue-next';
import { computed, ref, unref, watch } from 'vue';
import { toast } from 'vue-sonner';
import AssignTrainerDialog from '@/components/AssignTrainerDialog.vue';
import DeleteDialog from '@/components/DeleteDialog.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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
import { useLocale } from '@/composables/useLocale';
import { usePermissions } from '@/composables/usePermissions';
import { formatDateTime, getTrainingCategoryLabel } from '@/lib/utils';
import {
    index,
    show,
    destroy,
} from '@/routes/dashboard/staff/training-requests';
import type {
    AssignableTrainer,
    LengthAwarePaginator,
    TrainingRequest,
    TrainingRequestType,
} from '@/types';
import {
    Permission,
    TrainingRequestConstants,
    TrainingRequestStatus,
} from '@/types';

const DEFAULT_STATUSES = [
    TrainingRequestStatus.PENDING,
    TrainingRequestStatus.SCHEDULED,
];

const props = defineProps<{
    trainingRequests: LengthAwarePaginator<number, TrainingRequest>;
    assignableTrainers: AssignableTrainer[];
    unassignedPendingCount: number;
    filters: {
        statuses: TrainingRequestStatus[];
        type?: TrainingRequestType;
        trainer_id?: number | 'unassigned' | null;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: wTrans('Training Requests'), href: index() }],
    },
});

const { locale } = useLocale();
const { hasPermission } = usePermissions();

const canUpdate = computed(() =>
    hasPermission(Permission.UPDATE_TRAINING_REQUESTS),
);
const canAssign = computed(() =>
    hasPermission(Permission.ASSIGN_TRAINING_REQUESTS),
);

function isFinalStatus(status: TrainingRequestStatus): boolean {
    return (
        status === TrainingRequestStatus.CANCELLED ||
        status === TrainingRequestStatus.COMPLETED
    );
}

const statuses = ref<TrainingRequestStatus[]>([...props.filters.statuses]);
const typeFilter = ref<TrainingRequestType | 'all'>(
    props.filters.type ?? 'all',
);
const trainerFilter = ref<string>(String(props.filters.trainer_id ?? 'all'));

function applyFilters() {
    router.get(
        index(),
        {
            statuses: statuses.value,
            type: typeFilter.value !== 'all' ? typeFilter.value : undefined,
            trainer_id:
                trainerFilter.value !== 'all' ? trainerFilter.value : undefined,
        },
        { preserveState: true, replace: true },
    );
}

watch(statuses, (value) => {
    if (value.length === 0) {
        // Never let the list fall back to an ambiguous "no statuses" state.
        statuses.value = [...DEFAULT_STATUSES];

        return;
    }

    applyFilters();
});
watch([typeFilter, trainerFilter], applyFilters);

const statusLabels = TrainingRequestConstants.statusLabels;
const statusVariants = TrainingRequestConstants.statusVariants;
const typeLabels = TrainingRequestConstants.typeLabels;

const statusSummary = computed(() => {
    if (statuses.value.length <= 2) {
        return statuses.value
            .map((status) => unref(statusLabels[status]))
            .join(', ');
    }

    return trans(':count statuses', { count: String(statuses.value.length) });
});

function hasActiveFilters(): boolean {
    const isDefaultStatuses =
        statuses.value.length === DEFAULT_STATUSES.length &&
        DEFAULT_STATUSES.every((status) => statuses.value.includes(status));

    return (
        !isDefaultStatuses ||
        typeFilter.value !== 'all' ||
        trainerFilter.value !== 'all'
    );
}

function clearFilters() {
    statuses.value = [...DEFAULT_STATUSES];
    typeFilter.value = 'all';
    trainerFilter.value = 'all';
}

function filterByTrainer(trainerId: number) {
    trainerFilter.value = String(trainerId);
}

const links = computed(() =>
    props.trainingRequests.links.filter(
        (l) => !l.label.includes('&laquo;') && !l.label.includes('&raquo;'),
    ),
);

const pendingCancel = ref<TrainingRequest | null>(null);
const cancelling = ref(false);

const cancelDescription = computed(() =>
    trans('Are you sure you want to cancel the request for ":name"?', {
        name: pendingCancel.value?.category ?? '',
    }),
);

function handleCancel() {
    if (!pendingCancel.value) {
        return;
    }

    cancelling.value = true;
    router.delete(destroy.url({ trainingRequest: pendingCancel.value.id }), {
        onSuccess: () => {
            pendingCancel.value = null;
            toast.success(wTrans('Training request cancelled.'));
        },
        onFinish: () => {
            cancelling.value = false;
        },
    });
}

const pendingAssign = ref<TrainingRequest | null>(null);
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    {{ $t('Training Requests') }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{ trainingRequests.total.toLocaleString() }}
                    {{
                        transChoice('request|requests', trainingRequests.total)
                    }}
                </p>
            </div>
        </div>

        <!-- Trainer workload -->
        <Card>
            <CardHeader
                class="flex flex-row items-center justify-between space-y-0"
            >
                <CardTitle class="flex items-center gap-2 text-sm font-medium">
                    <Users class="size-4 text-muted-foreground" />
                    {{ $t('Trainer workload') }}
                </CardTitle>
                <Badge v-if="unassignedPendingCount > 0" variant="secondary">
                    {{
                        trans(':count pending without a trainer', {
                            count: String(unassignedPendingCount),
                        })
                    }}
                </Badge>
            </CardHeader>
            <CardContent>
                <div class="max-h-64 overflow-y-auto rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Trainer') }}</TableHead>
                                <TableHead class="text-right">{{
                                    $t('ATC')
                                }}</TableHead>
                                <TableHead class="text-right">{{
                                    $t('Pilot')
                                }}</TableHead>
                                <TableHead class="text-right">{{
                                    $t('Total')
                                }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableEmpty
                                v-if="assignableTrainers.length === 0"
                                :colspan="4"
                            >
                                {{ $t('No trainers available.') }}
                            </TableEmpty>
                            <TableRow
                                v-for="trainer in assignableTrainers"
                                :key="trainer.id"
                                class="cursor-pointer"
                                :class="{
                                    'bg-muted':
                                        trainerFilter === String(trainer.id),
                                }"
                                @click="filterByTrainer(trainer.id)"
                            >
                                <TableCell class="text-sm font-medium">
                                    {{ trainer.name }}
                                </TableCell>
                                <TableCell class="text-right text-sm">
                                    {{ trainer.atc_trainings_count }}
                                </TableCell>
                                <TableCell class="text-right text-sm">
                                    {{ trainer.pilot_trainings_count }}
                                </TableCell>
                                <TableCell
                                    class="text-right text-sm font-medium"
                                >
                                    {{
                                        trainer.atc_trainings_count +
                                        trainer.pilot_trainings_count
                                    }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </CardContent>
        </Card>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
            <Select v-model="statuses" multiple>
                <SelectTrigger>
                    <SelectValue :placeholder="$t('Statuses')">
                        {{ statusSummary }}
                    </SelectValue>
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="(label, key) in statusLabels"
                        :key="key"
                        :value="key"
                    >
                        {{ label }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="typeFilter">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('Type')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">{{ $t('All types') }}</SelectItem>
                    <SelectItem
                        v-for="(label, key) in typeLabels"
                        :key="key"
                        :value="key"
                    >
                        {{ label }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="trainerFilter">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('Trainer')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">{{
                        $t('All trainers')
                    }}</SelectItem>
                    <SelectItem value="unassigned">{{
                        $t('Unassigned')
                    }}</SelectItem>
                    <SelectItem
                        v-for="trainer in assignableTrainers"
                        :key="trainer.id"
                        :value="String(trainer.id)"
                    >
                        {{ trainer.name }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Button
                v-if="hasActiveFilters()"
                variant="ghost"
                size="sm"
                @click="clearFilters"
            >
                <X class="size-3.5" />
                {{ $t('Clear filters') }}
            </Button>
        </div>

        <!-- Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>{{ $t('Trainee') }}</TableHead>
                        <TableHead>{{ $t('Type') }}</TableHead>
                        <TableHead>{{ $t('Training') }}</TableHead>
                        <TableHead>{{ $t('Status') }}</TableHead>
                        <TableHead>{{ $t('Scheduled') }}</TableHead>
                        <TableHead>{{ $t('Trainer') }}</TableHead>
                        <TableHead class="w-32">{{ $t('Actions') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableEmpty
                        v-if="trainingRequests.data.length === 0"
                        :colspan="7"
                    >
                        {{ $t('No training requests found.') }}
                    </TableEmpty>
                    <TableRow
                        v-for="request in trainingRequests.data"
                        :key="request.id"
                    >
                        <TableCell>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium">{{
                                    request.trainee?.name
                                }}</span>
                                <span class="text-xs text-muted-foreground"
                                    >VID {{ request.trainee?.vid }}</span
                                >
                            </div>
                        </TableCell>
                        <TableCell>
                            <Badge variant="outline">
                                {{ typeLabels[request.type] }}
                            </Badge>
                        </TableCell>
                        <TableCell class="max-w-50">
                            <span class="line-clamp-2 text-sm">
                                {{ getTrainingCategoryLabel(request) }}
                            </span>
                        </TableCell>
                        <TableCell>
                            <Badge :variant="statusVariants[request.status]">
                                {{ statusLabels[request.status] }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            {{
                                request.occurs_at
                                    ? formatDateTime(request.occurs_at, locale)
                                    : '—'
                            }}
                        </TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            {{ request.trainer?.name ?? '—' }}
                        </TableCell>
                        <TableCell>
                            <div class="flex items-center gap-1">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    :title="$t('View details')"
                                    @click="
                                        router.get(
                                            show.url({
                                                trainingRequest: request.id,
                                            }),
                                        )
                                    "
                                >
                                    <Eye class="size-4" />
                                </Button>
                                <Button
                                    v-if="
                                        canAssign &&
                                        !isFinalStatus(request.status)
                                    "
                                    variant="outline"
                                    size="sm"
                                    @click="pendingAssign = request"
                                >
                                    {{
                                        request.trainer
                                            ? $t('Reassign')
                                            : $t('Assign')
                                    }}
                                </Button>
                                <button
                                    v-if="
                                        canUpdate &&
                                        request.status !==
                                            TrainingRequestStatus.CANCELLED
                                    "
                                    class="rounded-md p-1 text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                    :title="$t('Cancel request')"
                                    @click="pendingCancel = request"
                                >
                                    <X class="size-4" />
                                </button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div
            v-if="trainingRequests.last_page > 1"
            class="flex items-center justify-center gap-1"
        >
            <Button
                variant="outline"
                size="icon"
                :disabled="!trainingRequests.prev_page_url"
                @click="
                    trainingRequests.prev_page_url &&
                    router.get(trainingRequests.prev_page_url)
                "
            >
                <ChevronLeft class="size-4" />
            </Button>
            <Button
                v-for="link in links"
                :key="link.label"
                :variant="link.active ? 'default' : 'outline'"
                size="sm"
                :disabled="!link.url"
                @click="link.url && router.get(link.url)"
            >
                {{ link.label }}
            </Button>
            <Button
                variant="outline"
                size="icon"
                :disabled="!trainingRequests.next_page_url"
                @click="
                    trainingRequests.next_page_url &&
                    router.get(trainingRequests.next_page_url)
                "
            >
                <ChevronRight class="size-4" />
            </Button>
        </div>

        <!-- Cancel dialog -->
        <DeleteDialog
            :open="pendingCancel !== null"
            :title="$t('Cancel Training Request')"
            :description="cancelDescription"
            :processing="cancelling"
            @update:open="(v) => !v && (pendingCancel = null)"
            @confirm="handleCancel"
        />

        <!-- Assign trainer dialog -->
        <AssignTrainerDialog
            :training-request="pendingAssign"
            :assignable-trainers="assignableTrainers"
            @update:open="(v) => !v && (pendingAssign = null)"
        />
    </div>
</template>

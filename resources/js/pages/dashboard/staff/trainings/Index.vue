<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { trans, transChoice, wTrans } from 'laravel-vue-i18n';
import {
    CalendarClock,
    ChevronLeft,
    ChevronRight,
    Clock,
    Eye,
    X,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
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
    LengthAwarePaginator,
    TrainingRequest,
    TrainingRequestStatus,
    TrainingRequestType,
} from '@/types';
import { Permission, TrainingRequestConstants } from '@/types';

type Counts = { pending: number; scheduled: number };

const props = defineProps<{
    trainingRequests: LengthAwarePaginator<number, TrainingRequest>;
    counts: Counts;
    filters: {
        status?: TrainingRequestStatus;
        type?: TrainingRequestType;
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

const statusFilter = ref<TrainingRequestStatus | 'all'>(
    props.filters.status ?? 'all',
);
const typeFilter = ref<TrainingRequestType | 'all'>(
    props.filters.type ?? 'all',
);

function applyFilters() {
    router.get(
        index(),
        {
            status:
                statusFilter.value !== 'all' ? statusFilter.value : undefined,
            type: typeFilter.value !== 'all' ? typeFilter.value : undefined,
        },
        { preserveState: true, replace: true },
    );
}

watch([statusFilter, typeFilter], applyFilters);

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

const statusLabels = TrainingRequestConstants.statusLabels;
const statusVariants = TrainingRequestConstants.statusVariants;
const typeLabels = TrainingRequestConstants.typeLabels;
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

        <!-- Stat cards -->
        <div class="grid gap-4 sm:grid-cols-2">
            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium">
                        {{ $t('Pending') }}
                    </CardTitle>
                    <Clock class="size-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ counts.pending }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ $t('Awaiting assignment') }}
                    </p>
                </CardContent>
            </Card>
            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium">
                        {{ $t('Scheduled') }}
                    </CardTitle>
                    <CalendarClock class="size-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ counts.scheduled }}</div>
                    <p class="text-xs text-muted-foreground">
                        {{ $t('Sessions planned') }}
                    </p>
                </CardContent>
            </Card>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
            <Select v-model="statusFilter">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('Status')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">{{
                        $t('All statuses')
                    }}</SelectItem>
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
                        <TableHead class="w-24">{{ $t('Actions') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableEmpty
                        v-if="trainingRequests.data.length === 0"
                        :col-span="7"
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
                                <button
                                    v-if="canUpdate"
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
    </div>
</template>

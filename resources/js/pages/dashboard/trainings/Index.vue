<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';
import {
    BookOpen,
    ChevronLeft,
    ChevronRight,
    ExternalLink,
    PlaneTakeoff,
    Radio,
    TriangleAlert,
    X,
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
import { Label } from '@/components/ui/label';
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
import { Textarea } from '@/components/ui/textarea';
import { useLocale } from '@/composables/useLocale';
import { formatDateTime, getTrainingCategoryLabel } from '@/lib/utils';
import { index, store, destroy } from '@/routes/dashboard/trainings';
import type { LengthAwarePaginator, TrainingRequest } from '@/types';
import {
    IVAO_TRAINING_REQUEST_URL,
    TrainingRequestConstants,
    TrainingRequestStatus,
    TrainingRequestType,
} from '@/types';

type TrainingOption = { value: string; label: string };

const props = defineProps<{
    trainingRequests: LengthAwarePaginator<number, TrainingRequest>;
    availableAtcTrainings: TrainingOption[];
    availablePilotTrainings: TrainingOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: wTrans('My Trainings'), href: index() }],
    },
});

const { locale } = useLocale();
const page = usePage();

const isFromDivision = computed(
    () => page.props.auth.user.division_id === 'CO',
);
const hasAtcTrainings = computed(() => props.availableAtcTrainings.length > 0);
const hasPilotTrainings = computed(
    () => props.availablePilotTrainings.length > 0,
);

const form = useForm({
    type: TrainingRequestType.ATC as TrainingRequestType,
    category: '',
    request_observations: '',
});

function selectTab(tab: TrainingRequestType) {
    form.type = tab;
    form.category = '';
}

function submitRequest() {
    if (!canRequestTrainings.value) {
        toast.error(
            wTrans(
                'You already have an active request. Please cancel it before submitting a new one.',
            ),
        );

        return;
    }

    form.post(store.url(), {
        onSuccess: () => {
            toast.success(wTrans('Training request submitted successfully.'));
            form.reset();
        },
        onError: (errors) => {
            if (errors.category || errors.request_observations) {
                return;
            }

            toast.error(errors.type ?? wTrans('Something went wrong.'));
        },
    });
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
        name: getTrainingCategoryLabel(pendingCancel.value),
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

const currentTrainings = computed(() =>
    form.type === TrainingRequestType.ATC
        ? props.availableAtcTrainings
        : props.availablePilotTrainings,
);

const canRequestTrainings = computed(() => {
    const hasPendingATCRequest = props.trainingRequests.data.some(
        (request) =>
            (request.status === TrainingRequestStatus.PENDING ||
                request.status === TrainingRequestStatus.SCHEDULED) &&
            request.type === TrainingRequestType.ATC,
    );

    const hasPendingPilotRequest = props.trainingRequests.data.some(
        (request) =>
            (request.status === TrainingRequestStatus.PENDING ||
                request.status === TrainingRequestStatus.SCHEDULED) &&
            request.type === TrainingRequestType.Pilot,
    );

    if (form.type === TrainingRequestType.ATC) {
        return !hasPendingATCRequest;
    } else {
        return !hasPendingPilotRequest;
    }
});
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ $t('My Trainings') }}
            </h1>
            <p class="text-sm text-muted-foreground">
                {{ $t('Request and track your training sessions.') }}
            </p>
        </div>

        <template v-if="isFromDivision">
            <!-- IVAO website reminder -->
            <div
                class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-900 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200"
            >
                <TriangleAlert class="mt-0.5 size-5 shrink-0" />
                <p class="text-sm">
                    {{ $t('You must also request your training on the') }}
                    <a
                        :href="IVAO_TRAINING_REQUEST_URL"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 font-semibold underline underline-offset-2 hover:opacity-80"
                    >
                        {{ $t('IVAO website') }}
                        <ExternalLink class="size-3.5" />
                    </a>
                    {{ $t('in addition to submitting this form.') }}
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Request form -->
                <Card class="relative lg:col-span-1">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <BookOpen class="size-5" />
                            {{ $t('Request a Training') }}
                        </CardTitle>
                        <CardDescription>
                            {{
                                $t(
                                    'Select a training session available for your current rating.',
                                )
                            }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-8">
                        <!-- Type tabs -->
                        <div
                            class="flex gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1 dark:border-white/10 dark:bg-white/5"
                        >
                            <button
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors"
                                :class="
                                    form.type === TrainingRequestType.ATC
                                        ? 'bg-white text-emerald-700 shadow-sm dark:bg-white/10 dark:text-emerald-400'
                                        : 'text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/75'
                                "
                                :disabled="!hasAtcTrainings"
                                @click="selectTab(TrainingRequestType.ATC)"
                            >
                                <Radio class="h-4 w-4" />
                                {{ $t('ATC') }}
                            </button>
                            <button
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors"
                                :class="
                                    form.type === TrainingRequestType.Pilot
                                        ? 'bg-white text-sky-700 shadow-sm dark:bg-white/10 dark:text-sky-400'
                                        : 'text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/75'
                                "
                                :disabled="!hasPilotTrainings"
                                @click="selectTab(TrainingRequestType.Pilot)"
                            >
                                <PlaneTakeoff class="h-4 w-4" />
                                {{ $t('Pilot') }}
                            </button>
                        </div>

                        <div class="relative flex flex-col gap-3">
                            <!-- Overlay: already has a pending request -->
                            <div
                                v-if="!canRequestTrainings"
                                class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 bg-background/80 p-6 text-center backdrop-blur-sm"
                            >
                                <div
                                    class="rounded-full bg-amber-100 p-3 dark:bg-amber-900/40"
                                >
                                    <TriangleAlert
                                        class="size-6 text-amber-600 dark:text-amber-400"
                                    />
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        {{
                                            $t(
                                                'You already have an active request',
                                            )
                                        }}
                                    </p>
                                    <p
                                        class="mt-1 text-sm text-muted-foreground"
                                    >
                                        {{
                                            $t(
                                                'You can only have one pending training request at a time. Cancel your current request to submit a new one.',
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <Label for="category">{{
                                    $t('Training')
                                }}</Label>
                                <Select v-model="form.category">
                                    <SelectTrigger id="category">
                                        <SelectValue
                                            :placeholder="
                                                $t('Select a training...')
                                            "
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="opt in currentTrainings"
                                            :key="opt.value"
                                            :value="opt.value"
                                        >
                                            {{ opt.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <p
                                    v-if="form.errors.category"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.category }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <Label for="request_observations">
                                    {{ $t('Availability & Comments') }}
                                </Label>
                                <Textarea
                                    id="request_observations"
                                    v-model="form.request_observations"
                                    :placeholder="
                                        $t(
                                            'Describe your availability and any relevant comments...',
                                        )
                                    "
                                    rows="4"
                                />
                                <p
                                    v-if="form.errors.request_observations"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.request_observations }}
                                </p>
                            </div>

                            <Button
                                :disabled="
                                    form.processing ||
                                    !form.category ||
                                    !form.request_observations
                                "
                                class="w-full"
                                @click="submitRequest"
                            >
                                {{
                                    form.processing
                                        ? $t('Submitting...')
                                        : $t('Submit Request')
                                }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- My requests -->
                <div class="flex flex-col gap-4 lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">
                            {{ $t('My Requests') }}
                        </h2>
                        <span class="text-sm text-muted-foreground">
                            {{ trainingRequests.total }}
                            {{ $t('requests') }}
                        </span>
                    </div>

                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>{{ $t('Training') }}</TableHead>
                                    <TableHead>{{ $t('Type') }}</TableHead>
                                    <TableHead>{{ $t('Status') }}</TableHead>
                                    <TableHead>
                                        {{ $t('Scheduled Date') }}
                                    </TableHead>
                                    <TableHead>{{ $t('Notes') }}</TableHead>
                                    <TableHead class="w-12" />
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableEmpty
                                    v-if="trainingRequests.data.length === 0"
                                    :colspan="6"
                                >
                                    {{
                                        $t(
                                            'No training requests yet. Submit one to get started!',
                                        )
                                    }}
                                </TableEmpty>
                                <TableRow
                                    v-for="request in trainingRequests.data"
                                    :key="request.id"
                                >
                                    <TableCell class="max-w-55">
                                        <span
                                            class="line-clamp-2 text-sm font-medium"
                                        >
                                            {{
                                                getTrainingCategoryLabel(
                                                    request,
                                                )
                                            }}
                                        </span>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="outline">
                                            {{
                                                TrainingRequestConstants
                                                    .typeLabels[request.type]
                                            }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Badge
                                            :variant="
                                                TrainingRequestConstants
                                                    .statusVariants[
                                                    request.status
                                                ]
                                            "
                                        >
                                            {{
                                                TrainingRequestConstants
                                                    .statusLabels[
                                                    request.status
                                                ]
                                            }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell
                                        class="text-sm text-muted-foreground"
                                    >
                                        {{
                                            request.occurs_at
                                                ? formatDateTime(
                                                      request.occurs_at,
                                                      locale,
                                                  )
                                                : '—'
                                        }}
                                    </TableCell>
                                    <TableCell
                                        class="max-w-50 text-sm text-muted-foreground"
                                    >
                                        <span
                                            v-if="request.public_observations"
                                            class="line-clamp-2"
                                        >
                                            {{ request.public_observations }}
                                        </span>
                                        <span v-else>—</span>
                                    </TableCell>
                                    <TableCell>
                                        <button
                                            v-if="
                                                request.status ===
                                                TrainingRequestStatus.PENDING
                                            "
                                            class="rounded-md p-1 text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                            :title="$t('Cancel request')"
                                            @click="pendingCancel = request"
                                        >
                                            <X class="size-4" />
                                        </button>
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
                </div>
            </div>
        </template>
        <template v-else>
            <div
                class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-900 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200"
            >
                <TriangleAlert class="mt-0.5 size-5 shrink-0" />
                <p class="text-sm">
                    {{
                        $t(
                            'You are from another division. Please request your training on the',
                        )
                    }}
                    <a
                        :href="IVAO_TRAINING_REQUEST_URL"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 font-semibold underline underline-offset-2 hover:opacity-80"
                    >
                        {{ $t('IVAO website') }}
                        <ExternalLink class="size-3.5" />
                    </a>
                </p>
            </div>
        </template>

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

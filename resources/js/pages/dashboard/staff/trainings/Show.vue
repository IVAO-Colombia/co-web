<script setup lang="ts">
import { router, setLayoutProps, useForm } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';
import {
    CalendarClock,
    ExternalLink,
    Mail,
    PlaneTakeoff,
    Radio,
    User,
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
import { DateTimePicker } from '@/components/ui/date-time-picker';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import { useLocale } from '@/composables/useLocale';
import { formatDateTime, getTrainingCategoryLabel } from '@/lib/utils';
import type {
    TrainingRequest,
    TrainingRequestUser,
    ATCRating,
    PilotRating,
} from '@/types';
import {
    ATCRatings,
    PilotRatings,
    TrainingRequestConstants,
    TrainingRequestStatus,
    TrainingRequestType,
} from '@/types';
import {
    create as eventCreate,
    show as eventShow,
} from '@/routes/dashboard/events';
import {
    index,
    update,
    destroy,
} from '@/routes/dashboard/staff/training-requests';

type AssignableStaff = { id: number; name: string; vid: number };

const props = defineProps<{
    trainingRequest: TrainingRequest & {
        trainee: TrainingRequestUser;
        trainer: TrainingRequestUser | null;
        event: { id: number; name: string; slug: string } | null;
    };
    assignableStaff: AssignableStaff[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: wTrans('Training Requests'), href: index() }],
    },
});

setLayoutProps({
    breadcrumbs: [
        { title: wTrans('Training Requests'), href: index() },
        {
            title: getTrainingCategoryLabel(props.trainingRequest),
            href: '',
        },
    ],
});

const { locale } = useLocale();

const UNASSIGNED = 'none';

const form = useForm({
    trainer_id: props.trainingRequest.trainer_id
        ? String(props.trainingRequest.trainer_id)
        : UNASSIGNED,
    occurs_at: props.trainingRequest.occurs_at
        ? props.trainingRequest.occurs_at.replace('T', ' ').substring(0, 16)
        : '',
    public_observations: props.trainingRequest.public_observations ?? '',
    internal_observations: props.trainingRequest.internal_observations ?? '',
    status: props.trainingRequest.status,
});

function save() {
    const trainerIdBeforeSave = form.trainer_id;

    if (form.trainer_id === UNASSIGNED) {
        form.trainer_id = '';
    }

    form.patch(update.url({ trainingRequest: props.trainingRequest.id }), {
        onError: () => {
            form.trainer_id = trainerIdBeforeSave;
        },
        onSuccess: () => {
            if (form.trainer_id === '') {
                form.trainer_id = UNASSIGNED;
            }

            toast.success(wTrans('Training request updated.'));
        },
    });
}

const showCancelDialog = ref(false);
const cancelling = ref(false);

const cancelDescription = computed(() =>
    trans(
        'Are you sure you want to cancel the request for ":name"? This cannot be undone.',
        {
            name: getTrainingCategoryLabel(props.trainingRequest),
        },
    ),
);

function handleCancel() {
    cancelling.value = true;
    router.delete(destroy.url({ trainingRequest: props.trainingRequest.id }), {
        onSuccess: () => {
            showCancelDialog.value = false;
            toast.success(wTrans('Training request cancelled.'));
        },
        onFinish: () => {
            cancelling.value = false;
        },
    });
}

function generateEvent() {
    const params = new URLSearchParams({
        training_request_id: String(props.trainingRequest.id),
        type: 'training',
        name: getTrainingCategoryLabel(props.trainingRequest),
    });
    router.get(`${eventCreate.url()}?${params.toString()}`);
}

const traineeAtcRatingLabel = computed(() => {
    const r = props.trainingRequest.trainee.atc_rating as ATCRating;

    return ATCRatings[r]?.label ?? `Rating ${r}`;
});

const traineePilotRatingLabel = computed(() => {
    const r = props.trainingRequest.trainee.pilot_rating as PilotRating;

    return PilotRatings[r]?.label ?? `Rating ${r}`;
});

const statusVariants = TrainingRequestConstants.statusVariants;
const statusLabels = TrainingRequestConstants.statusLabels;
const typeLabels = TrainingRequestConstants.typeLabels;
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="mb-1 flex items-center gap-2">
                    <Badge variant="outline" class="flex items-center gap-1.5">
                        <Radio
                            v-if="
                                trainingRequest.type === TrainingRequestType.ATC
                            "
                            class="size-3"
                        />
                        <PlaneTakeoff v-else class="size-3" />
                        {{ typeLabels[trainingRequest.type] }}
                    </Badge>
                    <Badge :variant="statusVariants[trainingRequest.status]">
                        {{ statusLabels[trainingRequest.status] }}
                    </Badge>
                </div>
                <h1 class="text-xl font-semibold tracking-tight">
                    {{ getTrainingCategoryLabel(trainingRequest) }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{
                        $t('Requested on :date', {
                            date: formatDateTime(
                                trainingRequest.created_at,
                                locale,
                            ),
                        })
                    }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Button
                    v-if="
                        trainingRequest.status !==
                            TrainingRequestStatus.Cancelled &&
                        trainingRequest.status !==
                            TrainingRequestStatus.Completed
                    "
                    variant="destructive"
                    size="sm"
                    @click="showCancelDialog = true"
                >
                    <X class="mr-1.5 size-4" />
                    {{ $t('Cancel Request') }}
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-5">
            <!-- Left: trainee info + request details -->
            <div class="flex flex-col gap-4 lg:col-span-2">
                <!-- Trainee card -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="flex items-center gap-2 text-base">
                            <User class="size-4" />
                            {{ $t('Trainee') }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-3">
                        <div>
                            <p class="font-medium">
                                {{ trainingRequest.trainee.name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                VID
                                <a
                                    :href="`https://ivao.aero/Member.aspx?ID=${trainingRequest.trainee.vid}`"
                                    target="_blank"
                                    class="text-primary underline"
                                    rel="noopener noreferrer"
                                >
                                    {{ trainingRequest.trainee.vid }}
                                </a>
                            </p>
                        </div>
                        <a
                            :href="`mailto:${trainingRequest.trainee.email}`"
                            class="flex items-center gap-1.5 text-sm text-primary hover:underline"
                        >
                            <Mail class="size-3.5" />
                            {{ trainingRequest.trainee.email }}
                        </a>
                        <Separator />
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    {{ $t('ATC Rating') }}
                                </p>
                                <p class="font-medium">
                                    {{ traineeAtcRatingLabel }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    {{ $t('Pilot Rating') }}
                                </p>
                                <p class="font-medium">
                                    {{ traineePilotRatingLabel }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Request details -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">
                            {{ $t('Request Details') }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-3">
                        <div>
                            <p
                                class="mb-1 text-xs font-medium tracking-wide text-muted-foreground uppercase"
                            >
                                {{ $t('Availability & Comments') }}
                            </p>
                            <p
                                class="text-sm leading-relaxed whitespace-pre-wrap"
                            >
                                {{ trainingRequest.request_observations }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Linked event -->
                <Card v-if="trainingRequest.event">
                    <CardHeader class="pb-3">
                        <CardTitle class="flex items-center gap-2 text-base">
                            <CalendarClock class="size-4" />
                            {{ $t('Linked Event') }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <a
                            :href="
                                eventShow.url({
                                    event: trainingRequest.event.slug,
                                })
                            "
                            class="flex items-center gap-1.5 text-sm font-medium text-primary hover:underline"
                        >
                            {{ trainingRequest.event.name }}
                            <ExternalLink class="size-3.5" />
                        </a>
                    </CardContent>
                </Card>
            </div>

            <!-- Right: management form -->
            <Card class="lg:col-span-3">
                <CardHeader>
                    <CardTitle>{{ $t('Manage Request') }}</CardTitle>
                    <CardDescription>
                        {{
                            $t(
                                'Assign a trainer, schedule the session, and update the status.',
                            )
                        }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1.5">
                        <Label for="trainer">{{ $t('Assign Trainer') }}</Label>
                        <Select v-model="form.trainer_id">
                            <SelectTrigger id="trainer">
                                <SelectValue
                                    :placeholder="$t('Select a trainer...')"
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem :value="UNASSIGNED">
                                    {{ $t('Unassigned') }}
                                </SelectItem>
                                <SelectItem
                                    v-for="staff in assignableStaff"
                                    :key="staff.id"
                                    :value="String(staff.id)"
                                >
                                    {{ staff.name }}
                                    <span class="text-muted-foreground">
                                        (VID {{ staff.vid }})
                                    </span>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <p
                            v-if="form.errors.trainer_id"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.trainer_id }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="occurs_at">{{
                            $t('Session Date & Time')
                        }}</Label>
                        <DateTimePicker
                            id="occurs_at"
                            v-model="form.occurs_at"
                            :placeholder="$t('Pick a date and time')"
                        />
                        <p
                            v-if="form.errors.occurs_at"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.occurs_at }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="status">{{ $t('Status') }}</Label>
                        <Select v-model="form.status">
                            <SelectTrigger id="status">
                                <SelectValue />
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
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="public_observations">
                            {{ $t('Public Notes') }}
                        </Label>
                        <p class="text-xs text-muted-foreground">
                            {{ $t('Visible to the trainee.') }}
                        </p>
                        <Textarea
                            id="public_observations"
                            v-model="form.public_observations"
                            :placeholder="$t('Notes visible to the trainee...')"
                            rows="3"
                        />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="internal_observations">
                            {{ $t('Internal Notes') }}
                        </Label>
                        <p class="text-xs text-muted-foreground">
                            {{ $t('Only visible to staff.') }}
                        </p>
                        <Textarea
                            id="internal_observations"
                            v-model="form.internal_observations"
                            :placeholder="$t('Internal staff notes...')"
                            rows="3"
                        />
                    </div>

                    <Separator />

                    <div
                        class="flex flex-wrap items-center justify-between gap-3"
                    >
                        <Button
                            v-if="!trainingRequest.event"
                            variant="outline"
                            @click="generateEvent"
                        >
                            <CalendarClock class="mr-1.5 size-4" />
                            {{ $t('Generate Event') }}
                        </Button>
                        <span v-else />

                        <Button :disabled="form.processing" @click="save">
                            {{
                                form.processing
                                    ? $t('Saving...')
                                    : $t('Save Changes')
                            }}
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Cancel dialog -->
        <DeleteDialog
            :open="showCancelDialog"
            :title="$t('Cancel Training Request')"
            :description="cancelDescription"
            :processing="cancelling"
            @update:open="(v) => !v && (showCancelDialog = false)"
            @confirm="handleCancel"
        />
    </div>
</template>

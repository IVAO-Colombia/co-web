<script setup lang="ts">
import { router, setLayoutProps, useForm, usePage } from '@inertiajs/vue3';
import { trans, wTrans } from 'laravel-vue-i18n';
import {
    CalendarClock,
    ExternalLink,
    History,
    Mail,
    PlaneTakeoff,
    Radio,
    User,
    X,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import DeleteDialog from '@/components/DeleteDialog.vue';
import TrainingNoteSection from '@/components/TrainingNoteSection.vue';
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
import { useLocale } from '@/composables/useLocale';
import { usePermissions } from '@/composables/usePermissions';
import { formatDateTime, getTrainingCategoryLabel } from '@/lib/utils';
import {
    create as eventCreate,
    show as eventShow,
} from '@/routes/dashboard/events';
import {
    index,
    update,
    destroy,
} from '@/routes/dashboard/staff/training-requests';
import { update as trainerUpdate } from '@/routes/dashboard/staff/training-requests/trainer';
import type {
    Auth,
    TrainingRequest,
    TrainingRequestUser,
    ATCRating,
    PilotRating,
} from '@/types';
import {
    ATCRatings,
    PilotRatings,
    Permission,
    TrainingNoteVisibility,
    TrainingRequestConstants,
    TrainingRequestStatus,
    TrainingRequestType,
} from '@/types';

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
const { hasPermission } = usePermissions();

const UNASSIGNED = 'none';

const canUpdate = computed(() =>
    hasPermission(Permission.UPDATE_TRAINING_REQUESTS),
);

/** Cancelled and completed requests freeze the trainer and the schedule. */
const isFinal = computed(
    () =>
        props.trainingRequest.status === TrainingRequestStatus.CANCELLED ||
        props.trainingRequest.status === TrainingRequestStatus.COMPLETED,
);

const canAssignTrainer = computed(
    () => hasPermission(Permission.ASSIGN_TRAINING_REQUESTS) && !isFinal.value,
);

const trainerCardDescription = computed(() => {
    if (isFinal.value) {
        return trans(
            'The trainer cannot be changed once the request is completed or cancelled.',
        );
    }

    return canAssignTrainer.value
        ? trans('Assign the staff member who will run this session.')
        : trans('Only training coordinators can change the assignment.');
});
const canEditNotes = computed(() =>
    hasPermission(Permission.EDIT_TRAINING_NOTES),
);

const auth = usePage().props.auth as Auth;

const canAddNotes = computed(
    () =>
        canEditNotes.value || auth.user.id === props.trainingRequest.trainer_id,
);

const form = useForm({
    occurs_at: props.trainingRequest.occurs_at
        ? props.trainingRequest.occurs_at.replace('T', ' ').substring(0, 16)
        : '',
    status: props.trainingRequest.status,
});

function save() {
    form.patch(update.url({ trainingRequest: props.trainingRequest.id }), {
        onSuccess: () => {
            toast.success(wTrans('Training request updated.'));
        },
    });
}

const trainerForm = useForm({
    trainer_id: props.trainingRequest.trainer_id
        ? String(props.trainingRequest.trainer_id)
        : UNASSIGNED,
});

function saveTrainer() {
    trainerForm
        .transform((data) => ({
            trainer_id:
                data.trainer_id === UNASSIGNED ? null : Number(data.trainer_id),
        }))
        .patch(
            trainerUpdate.url({ trainingRequest: props.trainingRequest.id }),
            {
                onSuccess: () => {
                    toast.success(wTrans('Trainer updated.'));
                },
            },
        );
}

const assignmentHistory = computed(
    () => props.trainingRequest.assignment_history ?? [],
);

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
                        canUpdate &&
                        trainingRequest.status !==
                            TrainingRequestStatus.CANCELLED &&
                        trainingRequest.status !==
                            TrainingRequestStatus.COMPLETED
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

                <!-- Assignment history -->
                <Card v-if="assignmentHistory.length > 0">
                    <CardHeader class="pb-3">
                        <CardTitle class="flex items-center gap-2 text-base">
                            <History class="size-4" />
                            {{ $t('Assignment History') }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul class="flex flex-col gap-2">
                            <li
                                v-for="(entry, i) in assignmentHistory"
                                :key="i"
                                class="text-sm"
                            >
                                <p>
                                    <span class="font-medium">
                                        {{ entry.by_name }}
                                    </span>
                                    {{
                                        entry.trainer_name
                                            ? $t('assigned :trainer', {
                                                  trainer: entry.trainer_name,
                                              })
                                            : $t('removed the assignment')
                                    }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ formatDateTime(entry.at, locale) }}
                                </p>
                            </li>
                        </ul>
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

            <!-- Right: management -->
            <div class="flex flex-col gap-4 lg:col-span-3">
                <!-- Trainer -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">
                            {{ $t('Trainer') }}
                        </CardTitle>
                        <CardDescription>
                            {{ trainerCardDescription }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="canAssignTrainer"
                            class="flex flex-col gap-1.5"
                        >
                            <div class="flex items-center gap-2">
                                <Select v-model="trainerForm.trainer_id">
                                    <SelectTrigger id="trainer" class="flex-1">
                                        <SelectValue
                                            :placeholder="
                                                $t('Select a trainer...')
                                            "
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
                                <Button
                                    variant="outline"
                                    :disabled="trainerForm.processing"
                                    @click="saveTrainer"
                                >
                                    {{ $t('Assign') }}
                                </Button>
                            </div>
                            <p
                                v-if="trainerForm.errors.trainer_id"
                                class="text-sm text-destructive"
                            >
                                {{ trainerForm.errors.trainer_id }}
                            </p>
                        </div>
                        <p v-else class="text-sm">
                            {{
                                trainingRequest.trainer?.name ??
                                $t('Unassigned')
                            }}
                        </p>
                    </CardContent>
                </Card>

                <!-- Scheduling -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">
                            {{ $t('Scheduling') }}
                        </CardTitle>
                        <CardDescription>
                            {{
                                isFinal
                                    ? $t(
                                          'This request is closed. Only the status can still be changed.',
                                      )
                                    : $t(
                                          'Set the session date and the request status.',
                                      )
                            }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-5">
                        <div class="flex flex-col gap-1.5">
                            <Label for="occurs_at">{{
                                $t('Session Date & Time')
                            }}</Label>
                            <DateTimePicker
                                v-if="canUpdate && !isFinal"
                                id="occurs_at"
                                v-model="form.occurs_at"
                                :placeholder="$t('Pick a date and time')"
                            />
                            <p v-else class="text-sm">
                                {{
                                    trainingRequest.occurs_at
                                        ? formatDateTime(
                                              trainingRequest.occurs_at,
                                              locale,
                                          )
                                        : $t('Not scheduled yet.')
                                }}
                            </p>
                            <p
                                v-if="form.errors.occurs_at"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.occurs_at }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <Label for="status">{{ $t('Status') }}</Label>
                            <Select v-if="canUpdate" v-model="form.status">
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
                            <p v-else class="text-sm">
                                {{ statusLabels[trainingRequest.status] }}
                            </p>
                        </div>

                        <template v-if="canUpdate">
                            <Separator />

                            <div
                                class="flex flex-wrap items-center justify-between gap-3"
                            >
                                <Button
                                    v-if="!trainingRequest.event && !isFinal"
                                    variant="outline"
                                    @click="generateEvent"
                                >
                                    <CalendarClock class="mr-1.5 size-4" />
                                    {{ $t('Generate Event') }}
                                </Button>
                                <span v-else />

                                <Button
                                    :disabled="form.processing"
                                    @click="save"
                                >
                                    {{
                                        form.processing
                                            ? $t('Saving...')
                                            : $t('Save Changes')
                                    }}
                                </Button>
                            </div>
                        </template>
                    </CardContent>
                </Card>

                <!-- Notes -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-base">
                            {{ $t('Notes') }}
                        </CardTitle>
                        <CardDescription>
                            {{
                                $t(
                                    'Notes are appended with your name and the date.',
                                )
                            }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-5">
                        <TrainingNoteSection
                            :training-request-id="trainingRequest.id"
                            :title="$t('Public Notes')"
                            :description="$t('Visible to the trainee.')"
                            :content="trainingRequest.public_observations"
                            field="public_observations"
                            :visibility="TrainingNoteVisibility.PublicNote"
                            :add-placeholder="
                                $t('Notes visible to the trainee...')
                            "
                            :can-edit="canEditNotes"
                            :can-add="canAddNotes"
                        />

                        <Separator />

                        <TrainingNoteSection
                            :training-request-id="trainingRequest.id"
                            :title="$t('Internal Notes')"
                            :description="$t('Only visible to staff.')"
                            :content="trainingRequest.internal_observations"
                            field="internal_observations"
                            :visibility="TrainingNoteVisibility.InternalNote"
                            :add-placeholder="$t('Internal staff notes...')"
                            :can-edit="canEditNotes"
                            :can-add="canAddNotes"
                        />
                    </CardContent>
                </Card>
            </div>
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

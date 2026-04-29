<script setup lang="ts">
import { Head, Link, setLayoutProps, useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { AlertCircle, ChevronLeft, ImagePlus, X } from 'lucide-vue-next';
import { computed, onUnmounted, ref } from 'vue';
import {
    edit,
    update,
} from '@/actions/App/Http/Controllers/Dashboard/EventsController';
import AtcSlotsCard from '@/components/dashboard/events/AtcSlotsCard.vue';
import PilotSlotsCard from '@/components/dashboard/events/PilotSlotsCard.vue';
import InputError from '@/components/InputError.vue';
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
import { Input } from '@/components/ui/input';
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
import { toUTCDateTime } from '@/lib/utils';
import { show as showRoute, index } from '@/routes/dashboard/events';
import {
    EventConstants,
    EventTag,
    EventStatus as EventStatusEnum,
} from '@/types';
import type {
    AtcSlotRow,
    EventDetail,
    EventStatus,
    EventType,
    PilotSlotRow,
} from '@/types';

type EventForm = {
    name: string;
    name_en: string;
    description: string;
    description_en: string;
    type: EventType | '';
    locations: string;
    starts_at: string;
    ends_at: string;
    image: File | null;
    tags: EventTag[];
    status: EventStatus;
    pilot_slots_enabled: boolean;
    pilot_slots: PilotSlotRow[];
    atc_slots_enabled: boolean;
    atc_slots: AtcSlotRow[];
};

const props = defineProps<{
    event: EventDetail;
    hasReservedPilotSlots: boolean;
    hasReservedAtcSlots: boolean;
}>();

setLayoutProps({
    breadcrumbs: [
        { title: wTrans('Events'), href: index() },
        {
            title: props.event.name,
            href: showRoute.url({ event: props.event.slug }),
        },
        {
            title: wTrans('Edit Event'),
            href: edit.url({ event: props.event.slug }),
        },
    ],
});

const form = useForm<EventForm>({
    name: props.event.name,
    name_en: props.event.name_en ?? '',
    description: props.event.description,
    description_en: props.event.description_en ?? '',
    type: props.event.type,
    locations: props.event.locations,
    starts_at: toUTCDateTime(props.event.starts_at),
    ends_at: toUTCDateTime(props.event.ends_at),
    image: null,
    tags: [...props.event.tags],
    status: props.event.status,
    pilot_slots_enabled: props.event.pilot_slots_enabled,
    pilot_slots: props.event.pilot_slots.map(
        (slot): PilotSlotRow => ({
            airline_icao: slot.airline_icao,
            flight_number: slot.flight_number,
            aircraft: slot.aircraft,
            origin: slot.origin,
            destination: slot.destination,
            departs_at: toUTCDateTime(slot.departs_at),
            gate: slot.gate ?? '',
        }),
    ),
    atc_slots_enabled: props.event.atc_slots_enabled,
    atc_slots: props.event.atc_slots.map(
        (slot): AtcSlotRow => ({
            callsign: slot.callsign,
            starts_at: toUTCDateTime(slot.starts_at),
            ends_at: toUTCDateTime(slot.ends_at),
        }),
    ),
});

const editableStatuses = [
    EventStatusEnum.DRAFT,
    EventStatusEnum.ACTIVE,
    EventStatusEnum.CANCELLED,
] as const;

function toggleTag(tag: EventTag): void {
    if (form.tags.includes(tag)) {
        form.tags = form.tags.filter((t) => t !== tag);
    } else {
        form.tags.push(tag);
    }
}

// --- Image ---
const imagePreview = ref<string | null>(props.event.image_url ?? null);

function onImageChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (!file) {
        return;
    }

    form.image = file;

    if (imagePreview.value?.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }

    imagePreview.value = URL.createObjectURL(file);
}

function removeImage(): void {
    if (imagePreview.value?.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }

    form.image = null;
    imagePreview.value = props.event.image_url ?? null;
}

onUnmounted(() => {
    if (imagePreview.value?.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
});

// --- Submit ---
const pilotSlotErrors = computed(() => {
    const msgs = Object.entries(form.errors)
        .filter(([key]) => key.startsWith('pilot_slots.'))
        .map(([, msg]) => msg);

    return [...new Set(msgs)].join(' ');
});

const atcSlotErrors = computed(() => {
    const msgs = Object.entries(form.errors)
        .filter(([key]) => key.startsWith('atc_slots.'))
        .map(([, msg]) => msg);

    return [...new Set(msgs)].join(' ');
});

function submit(): void {
    form.put(update.url(props.event.slug));
}
</script>

<template>
    <Head :title="$t('Edit Event')" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ $t('Edit Event') }}
            </h1>
            <p class="text-sm text-muted-foreground">
                {{ event.name }}
            </p>
        </div>

        <form class="flex flex-col gap-6" @submit.prevent="submit">
            <!-- Card 1: Event Details -->
            <Card>
                <CardHeader>
                    <CardTitle>{{ $t('Event Details') }}</CardTitle>
                    <CardDescription>
                        {{
                            $t(
                                'Basic information about the event in Spanish and English.',
                            )
                        }}
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex flex-col gap-6">
                    <!-- Name: ES + EN -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="name">
                                {{ $t('Name') }}
                                <span class="ml-1 text-xs text-muted-foreground"
                                    >(ES)</span
                                >
                                <span class="ml-0.5 text-destructive">*</span>
                            </Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                :placeholder="$t('Event name in Spanish')"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="name_en">
                                {{ $t('Name') }}
                                <span class="ml-1 text-xs text-muted-foreground"
                                    >(EN)</span
                                >
                            </Label>
                            <Input
                                id="name_en"
                                v-model="form.name_en"
                                :placeholder="$t('Event name in English')"
                            />
                            <InputError :message="form.errors.name_en" />
                        </div>
                    </div>

                    <!-- Description: ES + EN -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="description">
                                {{ $t('Description') }}
                                <span class="ml-1 text-xs text-muted-foreground"
                                    >(ES)</span
                                >
                                <span class="ml-0.5 text-destructive">*</span>
                            </Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                :placeholder="
                                    $t('Event description in Spanish')
                                "
                                class="min-h-28 resize-y"
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="description_en">
                                {{ $t('Description') }}
                                <span class="ml-1 text-xs text-muted-foreground"
                                    >(EN)</span
                                >
                            </Label>
                            <Textarea
                                id="description_en"
                                v-model="form.description_en"
                                :placeholder="
                                    $t('Event description in English')
                                "
                                class="min-h-28 resize-y"
                            />
                            <InputError :message="form.errors.description_en" />
                        </div>
                    </div>

                    <Separator />

                    <!-- Type + Locations + Status -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex gap-4">
                            <div class="flex flex-col gap-1.5">
                                <Label for="status">
                                    {{ $t('Status') }}
                                    <span class="ml-0.5 text-destructive"
                                        >*</span
                                    >
                                </Label>
                                <Select v-model="form.status">
                                    <SelectTrigger id="status">
                                        <SelectValue
                                            :placeholder="$t('Select status')"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="status in editableStatuses"
                                            :key="status"
                                            :value="status"
                                        >
                                            {{
                                                EventConstants.statusLabels[
                                                    status
                                                ]
                                            }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.status" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <Label for="type">
                                    {{ $t('Type') }}
                                    <span class="ml-0.5 text-destructive"
                                        >*</span
                                    >
                                </Label>
                                <Select v-model="form.type">
                                    <SelectTrigger id="type">
                                        <SelectValue
                                            :placeholder="
                                                $t('Select event type')
                                            "
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="(
                                                label, value
                                            ) in EventConstants.typeLabels"
                                            :key="value"
                                            :value="value"
                                        >
                                            {{ label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="locations">
                                {{ $t('Location') }}
                                <span class="ml-0.5 text-destructive">*</span>
                            </Label>
                            <Input
                                id="locations"
                                v-model="form.locations"
                                :placeholder="$t('e.g. SEQM, SEGU')"
                            />
                            <InputError :message="form.errors.locations" />
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="starts_at">
                                {{ $t('Starts At') }}
                                <span class="ml-0.5 text-destructive">*</span>
                            </Label>
                            <DateTimePicker
                                v-model="form.starts_at"
                                :placeholder="$t('Pick start date & time')"
                            />
                            <InputError :message="form.errors.starts_at" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="ends_at">{{ $t('Ends At') }}</Label>
                            <DateTimePicker
                                v-model="form.ends_at"
                                :min-value="form.starts_at || undefined"
                                :placeholder="$t('Pick end date & time')"
                            />
                            <InputError :message="form.errors.ends_at" />
                        </div>
                    </div>

                    <Separator />

                    <!-- Tags -->
                    <div class="flex flex-col gap-1.5">
                        <Label>{{ $t('Tags') }}</Label>
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                v-for="tag in Object.values(EventTag)"
                                :key="tag"
                                as="button"
                                type="button"
                                :variant="
                                    form.tags.includes(tag)
                                        ? 'default'
                                        : 'outline'
                                "
                                class="cursor-pointer transition-colors"
                                @click="toggleTag(tag)"
                            >
                                {{ EventConstants.tagLabels[tag] }}
                            </Badge>
                        </div>
                        <InputError :message="form.errors['tags']" />
                    </div>

                    <Separator />

                    <!-- Image -->
                    <div class="flex flex-col gap-1.5">
                        <Label>{{ $t('Event Image') }}</Label>
                        <div v-if="imagePreview" class="relative w-fit">
                            <img
                                :src="imagePreview"
                                alt="Event image preview"
                                class="h-40 rounded-lg border object-cover"
                            />
                            <button
                                v-if="form.image !== null"
                                type="button"
                                class="absolute top-1.5 right-1.5 rounded-full bg-background/80 p-0.5 text-muted-foreground shadow hover:text-foreground"
                                @click="removeImage"
                            >
                                <X class="size-4" />
                            </button>
                        </div>
                        <label
                            :class="[
                                'flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-input text-muted-foreground transition-colors hover:border-ring hover:text-foreground',
                                imagePreview ? 'mt-2 h-20' : 'h-32',
                            ]"
                        >
                            <ImagePlus class="size-6" />
                            <span class="text-sm">{{
                                imagePreview
                                    ? $t('Upload a new image to replace')
                                    : $t('Click to upload an image')
                            }}</span>
                            <span v-if="!imagePreview" class="text-xs"
                                >JPEG, PNG, WebP — max 2 MB</span
                            >
                            <input
                                type="file"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                class="sr-only"
                                @change="onImageChange"
                            />
                        </label>
                        <InputError :message="form.errors.image" />
                    </div>
                </CardContent>
            </Card>

            <!-- ATC Slots -->
            <AtcSlotsCard
                v-model:slots="form.atc_slots"
                v-model:enabled="form.atc_slots_enabled"
                :event-starts-at="form.starts_at"
                :event-ends-at="form.ends_at"
                :error="form.errors.atc_slots ?? atcSlotErrors"
                :locked="hasReservedAtcSlots"
            />

            <!-- Pilot Slots -->
            <PilotSlotsCard
                v-model:slots="form.pilot_slots"
                v-model:enabled="form.pilot_slots_enabled"
                :error="form.errors.pilot_slots ?? pilotSlotErrors"
                :locked="hasReservedPilotSlots"
            />

            <!-- Global error -->
            <div
                v-if="form.hasErrors"
                class="flex items-center gap-2 rounded-md border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive"
            >
                <AlertCircle class="size-4 shrink-0" />
                {{ $t('Please fix the errors above before submitting.') }}
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <Button variant="ghost" as-child>
                    <Link :href="index()">
                        <ChevronLeft class="size-4" />
                        {{ $t('Back to Events') }}
                    </Link>
                </Button>
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? $t('Saving...') : $t('Save Changes') }}
                </Button>
            </div>
        </form>
    </div>
</template>

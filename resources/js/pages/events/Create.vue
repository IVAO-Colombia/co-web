<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import {
    AlertCircle,
    ChevronLeft,
    FileText,
    ImagePlus,
    PlaneTakeoff,
    Radio,
    Upload,
    X,
} from 'lucide-vue-next';
import { computed, onUnmounted, ref } from 'vue';
import { store } from '@/actions/App/Http/Controllers/EventsController';
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
import { Switch } from '@/components/ui/switch';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import {
    csvDataUri,
    normalizeDatetime,
    normalizeTime,
    parseCsv,
} from '@/lib/utils';
import { index, create } from '@/routes/events';
import type { EventType } from '@/types';
import { EventConstants } from '@/types';
import { EventTag } from '@/types';

type PilotSlotCSV = {
    callsign: string;
    flight_number: string;
    aircraft: string;
    origin: string;
    destination: string;
    departure_date_time: string;
    gate: string;
};

type PilotSlotRow = {
    callsign: string;
    flight_number: string;
    aircraft: string;
    origin: string;
    destination: string;
    departs_at: string;
    gate: string;
};

type AtcSlotCSV = {
    callsign: string;
    start_time: string;
    end_time: string;
};
type AtcSlotRow = {
    callsign: string;
    starts_at: string;
    ends_at: string;
};

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
    pilot_slots_enabled: boolean;
    pilot_slots: PilotSlotRow[];
    atc_slots_enabled: boolean;
    atc_slots: AtcSlotRow[];
};

defineOptions({
    layout: {
        breadcrumbs: [
            { title: wTrans('Events'), href: index() },
            { title: wTrans('Create Event'), href: create() },
        ],
    },
});

const form = useForm<EventForm>({
    name: '',
    name_en: '',
    description: '',
    description_en: '',
    type: '',
    locations: '',
    starts_at: '',
    ends_at: '',
    image: null,
    tags: [],
    pilot_slots_enabled: false,
    pilot_slots: [],
    atc_slots_enabled: false,
    atc_slots: [],
});

function toggleTag(tag: EventTag): void {
    if (form.tags.includes(tag)) {
        form.tags = form.tags.filter((t) => t !== tag);
    } else {
        form.tags.push(tag);
    }
}

// --- Image ---
const imagePreview = ref<string | null>(null);

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

    imagePreview.value = null;
    form.image = null;
}

onUnmounted(() => {
    if (imagePreview.value?.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
});

// --- Pilot slots ---
const pilotSlotFileinput = ref<HTMLInputElement | null>(null);

const pilotTemplateCsvUrl = computed(() =>
    csvDataUri(
        'callsign,flight_number,aircraft,origin,destination,departs_at,gate',
    ),
);

function onPilotCsvChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const rows = parseCsv(e.target?.result as string) as PilotSlotCSV[];
        form.pilot_slots = rows.map(
            (row): PilotSlotRow => ({
                ...row,
                departs_at: row.departure_date_time
                    ? normalizeDatetime(row.departure_date_time)
                    : row.departure_date_time,
            }),
        );
    };
    reader.readAsText(file);
}

function clearPilotSlots(): void {
    form.pilot_slots = [];

    if (pilotSlotFileinput.value) {
        pilotSlotFileinput.value.value = '';
    }
}

// --- ATC slots ---
const atcSlotFileinput = ref<HTMLInputElement | null>(null);

const atcTemplateCsvUrl = computed(() =>
    csvDataUri('callsign,start_time,end_time'),
);

function onAtcCsvChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const rows = parseCsv(e.target?.result as string) as AtcSlotCSV[];
        form.atc_slots = rows.map(
            (row): AtcSlotRow => ({
                callsign: row.callsign,
                starts_at: row.start_time
                    ? normalizeTime(row.start_time)
                    : row.start_time,
                ends_at: row.end_time
                    ? normalizeTime(row.end_time)
                    : row.end_time,
            }),
        );
    };
    reader.readAsText(file);
}

function clearAtcSlots(): void {
    form.atc_slots = [];

    if (atcSlotFileinput.value) {
        atcSlotFileinput.value.value = '';
    }
}

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
    form.post(store.url());
}
</script>

<template>
    <Head :title="$t('Create Event')" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ $t('Create Event') }}
            </h1>
            <p class="text-sm text-muted-foreground">
                {{
                    $t(
                        'Fill in the details below to create a new event as a draft.',
                    )
                }}
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

                    <!-- Type + Locations -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="type">
                                {{ $t('Type') }}
                                <span class="ml-0.5 text-destructive">*</span>
                            </Label>
                            <Select v-model="form.type">
                                <SelectTrigger id="type">
                                    <SelectValue
                                        :placeholder="$t('Select event type')"
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
                            <Input
                                id="starts_at"
                                v-model="form.starts_at"
                                type="datetime-local"
                                step="1800"
                            />
                            <InputError :message="form.errors.starts_at" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="ends_at">{{ $t('Ends At') }}</Label>
                            <Input
                                id="ends_at"
                                v-model="form.ends_at"
                                type="datetime-local"
                                step="1800"
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
                                type="button"
                                class="absolute top-1.5 right-1.5 rounded-full bg-background/80 p-0.5 text-muted-foreground shadow hover:text-foreground"
                                @click="removeImage"
                            >
                                <X class="size-4" />
                            </button>
                        </div>
                        <label
                            v-else
                            class="flex h-32 cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-input text-muted-foreground transition-colors hover:border-ring hover:text-foreground"
                        >
                            <ImagePlus class="size-6" />
                            <span class="text-sm">{{
                                $t('Click to upload an image')
                            }}</span>
                            <span class="text-xs"
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

            <!-- Card 2: Pilot Slots -->
            <Card>
                <CardHeader>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex flex-col gap-1">
                            <CardTitle class="flex items-center gap-2">
                                <PlaneTakeoff
                                    class="size-4 text-sky-600 dark:text-sky-400"
                                />
                                {{ $t('Pilot Slots') }}
                            </CardTitle>
                            <CardDescription>
                                {{
                                    $t(
                                        'Upload a CSV file with pilot slot assignments.',
                                    )
                                }}
                            </CardDescription>
                        </div>
                        <Switch v-model="form.pilot_slots_enabled" />
                    </div>
                </CardHeader>
                <template v-if="form.pilot_slots_enabled">
                    <CardContent class="flex flex-col gap-4">
                        <!-- Template download -->
                        <div
                            class="flex items-center gap-2 rounded-md bg-muted/50 px-3 py-2"
                        >
                            <FileText
                                class="size-4 shrink-0 text-muted-foreground"
                            />
                            <span class="flex-1 text-sm text-muted-foreground">
                                {{
                                    $t(
                                        'Download the template and fill it with your slots.',
                                    )
                                }}
                            </span>
                            <a
                                :href="pilotTemplateCsvUrl"
                                download="pilot-slots-template.csv"
                                class="text-sm font-medium text-primary underline-offset-4 hover:underline"
                            >
                                {{ $t('Download template') }}
                            </a>
                        </div>

                        <!-- Upload -->
                        <div class="flex flex-wrap items-center gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-md border px-3 py-2 text-sm transition-colors hover:bg-muted"
                            >
                                <Upload class="size-4" />
                                {{ $t('Upload CSV') }}
                                <input
                                    ref="pilotSlotFileinput"
                                    type="file"
                                    accept=".csv"
                                    class="sr-only"
                                    @change="onPilotCsvChange"
                                />
                            </label>
                            <Button
                                v-if="form.pilot_slots.length > 0"
                                type="button"
                                variant="ghost"
                                size="sm"
                                class="text-muted-foreground"
                                @click="clearPilotSlots"
                            >
                                <X class="size-4" />
                                {{ $t('Clear') }}
                            </Button>
                        </div>

                        <!-- Inline preview -->
                        <div
                            v-if="form.pilot_slots.length > 0"
                            class="overflow-auto rounded-md border"
                        >
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>{{
                                            $t('Callsign')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Flight #')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Aircraft')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Origin')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Destination')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Departs At')
                                        }}</TableHead>
                                        <TableHead>{{ $t('Gate') }}</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="(slot, i) in form.pilot_slots"
                                        :key="i"
                                    >
                                        <TableCell class="font-mono">{{
                                            slot.callsign
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.flight_number || '—'
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.aircraft
                                        }}</TableCell>
                                        <TableCell class="font-mono">{{
                                            slot.origin
                                        }}</TableCell>
                                        <TableCell class="font-mono">{{
                                            slot.destination
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.departs_at
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.gate || '—'
                                        }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <InputError
                            :message="
                                form.errors.pilot_slots ?? pilotSlotErrors
                            "
                        />
                    </CardContent>
                </template>
            </Card>

            <!-- Card 3: ATC Slots -->
            <Card>
                <CardHeader>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex flex-col gap-1">
                            <CardTitle class="flex items-center gap-2">
                                <Radio
                                    class="size-4 text-emerald-600 dark:text-emerald-400"
                                />
                                {{ $t('ATC Slots') }}
                            </CardTitle>
                            <CardDescription>
                                {{
                                    $t(
                                        'Upload a CSV file with ATC slot assignments.',
                                    )
                                }}
                            </CardDescription>
                        </div>
                        <Switch v-model="form.atc_slots_enabled" />
                    </div>
                </CardHeader>
                <template v-if="form.atc_slots_enabled">
                    <CardContent class="flex flex-col gap-4">
                        <!-- Template download -->
                        <div
                            class="flex items-center gap-2 rounded-md bg-muted/50 px-3 py-2"
                        >
                            <FileText
                                class="size-4 shrink-0 text-muted-foreground"
                            />
                            <span class="flex-1 text-sm text-muted-foreground">
                                {{
                                    $t(
                                        'Download the template and fill it with your slots.',
                                    )
                                }}
                            </span>
                            <a
                                :href="atcTemplateCsvUrl"
                                download="atc-slots-template.csv"
                                class="text-sm font-medium text-primary underline-offset-4 hover:underline"
                            >
                                {{ $t('Download template') }}
                            </a>
                        </div>

                        <!-- Upload -->
                        <div class="flex flex-wrap items-center gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-md border px-3 py-2 text-sm transition-colors hover:bg-muted"
                            >
                                <Upload class="size-4" />
                                {{ $t('Upload CSV') }}
                                <input
                                    ref="atcSlotFileinput"
                                    type="file"
                                    accept=".csv"
                                    class="sr-only"
                                    @change="onAtcCsvChange"
                                />
                            </label>
                            <Button
                                v-if="form.atc_slots.length > 0"
                                type="button"
                                variant="ghost"
                                size="sm"
                                class="text-muted-foreground"
                                @click="clearAtcSlots"
                            >
                                <X class="size-4" />
                                {{ $t('Clear') }}
                            </Button>
                        </div>

                        <!-- Inline preview -->
                        <div
                            v-if="form.atc_slots.length > 0"
                            class="overflow-auto rounded-md border"
                        >
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>{{
                                            $t('Callsign')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Starts At')
                                        }}</TableHead>
                                        <TableHead>{{
                                            $t('Ends At')
                                        }}</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="(slot, i) in form.atc_slots"
                                        :key="i"
                                    >
                                        <TableCell class="font-mono">{{
                                            slot.callsign
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.starts_at
                                        }}</TableCell>
                                        <TableCell>{{
                                            slot.ends_at
                                        }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <InputError
                            :message="form.errors.atc_slots ?? atcSlotErrors"
                        />
                    </CardContent>
                </template>
            </Card>

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
                    {{
                        form.processing ? $t('Saving...') : $t('Save as Draft')
                    }}
                </Button>
            </div>
        </form>
    </div>
</template>

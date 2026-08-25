<script setup lang="ts">
import { Plus, Pencil, X } from 'lucide-vue-next';
import type { Component } from 'vue';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DateTimePicker } from '@/components/ui/date-time-picker';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { PilotSlotCategory, PilotSlotRow } from '@/types';

const props = defineProps<{
    category: PilotSlotCategory;
    icon: Component;
    title: string;
    addLabel: string;
    emptyLabel: string;
    slots: PilotSlotRow[];
    fieldErrors?: Record<string, string>;
}>();

const emit = defineEmits<{
    'update:slots': [slots: PilotSlotRow[]];
}>();

type IndexedSlot = { slot: PilotSlotRow; index: number };

const sectionSlots = computed<IndexedSlot[]>(() =>
    props.slots
        .map((slot, index) => ({ slot, index }))
        .filter(({ slot }) => slot.category === props.category),
);

function blankSlot(): PilotSlotRow {
    return {
        airline_icao: '',
        flight_number: '',
        aircraft: '',
        origin: '',
        destination: '',
        category: props.category,
        departs_at: '',
        arrives_at: null,
        gate: '',
    };
}

type SlotForm = { index: number | null; draft: PilotSlotRow };

/** At most one open form at a time: null means the form is closed. */
const form = ref<SlotForm | null>(null);

function openAddForm(): void {
    form.value = { index: null, draft: blankSlot() };
}

function openEditForm(index: number): void {
    form.value = { index, draft: { ...props.slots[index] } };
}

function closeForm(): void {
    form.value = null;
}

function saveForm(): void {
    if (!form.value) {
        return;
    }

    const { index, draft } = form.value;

    const updated =
        index === null
            ? [...props.slots, draft]
            : props.slots.map((slot, i) => (i === index ? draft : slot));

    emit('update:slots', updated);
    closeForm();
}

function removeSlot(index: number): void {
    emit(
        'update:slots',
        props.slots.filter((_, i) => i !== index),
    );

    // Removing a slot shifts every later index, which would make an open
    // form's `index` point at the wrong row - close it to stay safe.
    closeForm();
}

function setDraftUppercase(
    field: 'airline_icao' | 'aircraft' | 'origin' | 'destination',
    value: string | number,
): void {
    if (!form.value) {
        return;
    }

    form.value.draft[field] = String(value).toUpperCase();
}

/** DateTimePicker emits `yyyy-MM-ddTHH:mm`; the backend expects `Y-m-d H:i`. */
function setDraftDateTime(
    field: 'departs_at' | 'arrives_at',
    value: string,
): void {
    if (!form.value) {
        return;
    }

    const formatted = value ? value.replace('T', ' ') : '';

    if (field === 'arrives_at') {
        form.value.draft.arrives_at = formatted || null;
    } else {
        form.value.draft.departs_at = formatted;
    }
}

function fieldError(
    index: number,
    field: keyof PilotSlotRow,
): string | undefined {
    return props.fieldErrors?.[`pilot_slots.${index}.${field}`];
}

function hasFieldError(index: number): boolean {
    return Object.keys(props.fieldErrors ?? {}).some((key) =>
        key.startsWith(`pilot_slots.${index}.`),
    );
}
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between gap-3">
            <h3 class="flex items-center gap-2 text-sm font-semibold">
                <component :is="icon" class="size-4 text-muted-foreground" />
                {{ title }}
                <span
                    class="rounded-full bg-muted px-2 py-0.5 text-xs font-normal text-muted-foreground"
                >
                    {{ sectionSlots.length }}
                </span>
            </h3>
            <Button
                type="button"
                variant="outline"
                size="sm"
                :disabled="!!form"
                @click="openAddForm"
            >
                <Plus class="size-4" />
                {{ addLabel }}
            </Button>
        </div>

        <p
            v-if="sectionSlots.length === 0"
            class="rounded-md border border-dashed px-3 py-4 text-center text-sm text-muted-foreground"
        >
            {{ emptyLabel }}
        </p>

        <div v-else class="overflow-auto rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>{{ $t('Airline ICAO') }}</TableHead>
                        <TableHead>{{ $t('Flight #') }}</TableHead>
                        <TableHead>{{ $t('Aircraft') }}</TableHead>
                        <TableHead>{{ $t('Origin') }}</TableHead>
                        <TableHead>{{ $t('Destination') }}</TableHead>
                        <TableHead>{{ $t('Departs At') }}</TableHead>
                        <TableHead>{{ $t('Arrives At') }}</TableHead>
                        <TableHead>{{ $t('Gate') }}</TableHead>
                        <TableHead class="w-16" />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="{ slot, index } in sectionSlots"
                        :key="index"
                    >
                        <TableCell class="font-mono">
                            <span
                                v-if="hasFieldError(index)"
                                class="mr-1 inline-block size-1.5 rounded-full bg-destructive"
                            />{{ slot.airline_icao }}
                        </TableCell>
                        <TableCell>{{ slot.flight_number || '—' }}</TableCell>
                        <TableCell>{{ slot.aircraft }}</TableCell>
                        <TableCell class="font-mono">{{
                            slot.origin
                        }}</TableCell>
                        <TableCell class="font-mono">{{
                            slot.destination
                        }}</TableCell>
                        <TableCell>{{ slot.departs_at }}</TableCell>
                        <TableCell>{{ slot.arrives_at || '—' }}</TableCell>
                        <TableCell>{{ slot.gate || '—' }}</TableCell>
                        <TableCell>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="text-muted-foreground hover:text-foreground"
                                    @click="openEditForm(index)"
                                >
                                    <Pencil class="size-4" />
                                </button>
                                <button
                                    type="button"
                                    class="text-muted-foreground hover:text-destructive"
                                    @click="removeSlot(index)"
                                >
                                    <X class="size-4" />
                                </button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Add/edit form -->
        <div v-if="form" class="flex flex-col gap-3 rounded-md border p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Airline ICAO') }}</Label>
                    <Input
                        :model-value="form.draft.airline_icao"
                        class="uppercase"
                        maxlength="10"
                        @update:model-value="
                            (v) => setDraftUppercase('airline_icao', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'airline_icao')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Flight #') }}</Label>
                    <Input v-model="form.draft.flight_number" maxlength="20" />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'flight_number')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Aircraft') }}</Label>
                    <Input
                        :model-value="form.draft.aircraft"
                        class="uppercase"
                        maxlength="10"
                        @update:model-value="
                            (v) => setDraftUppercase('aircraft', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'aircraft')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Origin') }}</Label>
                    <Input
                        :model-value="form.draft.origin"
                        class="uppercase"
                        maxlength="4"
                        @update:model-value="
                            (v) => setDraftUppercase('origin', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'origin')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Destination') }}</Label>
                    <Input
                        :model-value="form.draft.destination"
                        class="uppercase"
                        maxlength="4"
                        @update:model-value="
                            (v) => setDraftUppercase('destination', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'destination')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Gate') }}</Label>
                    <Input v-model="form.draft.gate" maxlength="10" />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'gate')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Departs At') }}</Label>
                    <DateTimePicker
                        :model-value="form.draft.departs_at"
                        :placeholder="$t('Pick departure date & time')"
                        @update:model-value="
                            (v) => setDraftDateTime('departs_at', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'departs_at')"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label>{{ $t('Arrives At') }}</Label>
                    <DateTimePicker
                        :model-value="form.draft.arrives_at ?? ''"
                        :min-value="form.draft.departs_at || undefined"
                        :placeholder="$t('Pick arrival date & time')"
                        @update:model-value="
                            (v) => setDraftDateTime('arrives_at', v)
                        "
                    />
                    <InputError
                        v-if="form.index !== null"
                        :message="fieldError(form.index, 'arrives_at')"
                    />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Button type="button" size="sm" @click="saveForm">
                    {{
                        form.index === null
                            ? $t('Add slot')
                            : $t('Save Changes')
                    }}
                </Button>
                <Button
                    type="button"
                    variant="ghost"
                    size="sm"
                    @click="closeForm"
                >
                    {{ $t('Cancel') }}
                </Button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { Loader2, Lock, Plus, Radio, Search, X } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { AtcPosition, AtcSlotRow } from '@/types';
import ivao from '@/routes/ivao';

const props = defineProps<{
    slots: AtcSlotRow[];
    enabled: boolean;
    eventStartsAt: string;
    eventEndsAt: string;
    error?: string;
    locked?: boolean;
}>();

const emit = defineEmits<{
    'update:slots': [slots: AtcSlotRow[]];
    'update:enabled': [value: boolean];
}>();

const atcPositionsHttp = useHttp({ icao: '' });
const atcPositionsList = ref<AtcPosition[]>([]);
const atcPositionsFetchError = ref('');
const selectedCallsigns = ref<Set<string>>(new Set());
const atcGenerationError = ref('');

async function fetchAtcPositions(): Promise<void> {
    const icao = atcPositionsHttp.icao.trim().toUpperCase();

    if (!icao) {
        return;
    }

    atcPositionsFetchError.value = '';
    atcPositionsList.value = [];
    selectedCallsigns.value.clear();

    try {
        const response = (await atcPositionsHttp.get(
            ivao.airports.atcPositions.url(icao),
        )) as AtcPosition[];

        if (!atcPositionsHttp.wasSuccessful) {
            atcPositionsFetchError.value = wTrans(
                'Failed to fetch positions for :icao.',
                { icao },
            ).value;

            return;
        }

        atcPositionsList.value = response;

        if (atcPositionsList.value.length === 0) {
            atcPositionsFetchError.value = `No ATC positions found for ${icao}.`;
        }
    } catch {
        atcPositionsFetchError.value = `An error occurred while fetching positions for ${icao}.`;
    }
}

function toggleCallsign(callsign: string): void {
    if (selectedCallsigns.value.has(callsign)) {
        selectedCallsigns.value.delete(callsign);
    } else {
        selectedCallsigns.value.add(callsign);
    }
}

function generateAtcSlots(): void {
    atcGenerationError.value = '';

    if (!props.eventStartsAt || !props.eventEndsAt) {
        atcGenerationError.value = wTrans(
            'Please set the event start and end date/time before generating slots.',
        ).value;

        return;
    }

    if (selectedCallsigns.value.size === 0) {
        atcGenerationError.value = wTrans(
            'Please select at least one ATC position.',
        ).value;

        return;
    }

    const [startHour, startMin] = props.eventStartsAt
        .split('T')[1]
        .split(':')
        .map(Number);
    const [endHour, endMin] = props.eventEndsAt
        .split('T')[1]
        .split(':')
        .map(Number);

    const startMinutes = startHour * 60 + startMin;
    const endMinutes = endHour * 60 + endMin;

    const newSlots: AtcSlotRow[] = [];

    for (
        let slotStart = startMinutes;
        slotStart < endMinutes;
        slotStart += 60
    ) {
        const slotEnd = Math.min(slotStart + 60, endMinutes);
        const startsAt = `${String(Math.floor(slotStart / 60)).padStart(2, '0')}:${String(slotStart % 60).padStart(2, '0')}`;
        const endsAt = `${String(Math.floor(slotEnd / 60)).padStart(2, '0')}:${String(slotEnd % 60).padStart(2, '0')}`;

        for (const callsign of selectedCallsigns.value) {
            newSlots.push({ callsign, starts_at: startsAt, ends_at: endsAt });
        }
    }

    emit('update:slots', [...props.slots, ...newSlots]);
    selectedCallsigns.value.clear();
}

function removeSlot(i: number): void {
    emit(
        'update:slots',
        props.slots.filter((_, idx) => idx !== i),
    );
}

function clearSlots(): void {
    emit('update:slots', []);
}
</script>

<template>
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
                        <template v-if="locked">
                            {{
                                $t(
                                    'Slots cannot be edited because there are active reservations.',
                                )
                            }}
                        </template>
                        <template v-else>
                            {{
                                $t(
                                    "Search for an airport's ATC positions and generate slots automatically.",
                                )
                            }}
                        </template>
                    </CardDescription>
                </div>
                <Switch
                    :model-value="enabled"
                    :disabled="locked"
                    @update:model-value="(val) => emit('update:enabled', val)"
                />
            </div>
        </CardHeader>
        <template v-if="enabled">
            <CardContent class="flex flex-col gap-4">
                <!-- Locked banner -->
                <div
                    v-if="locked"
                    class="flex items-center gap-2 rounded-md border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-400"
                >
                    <Lock class="size-4 shrink-0" />
                    {{
                        $t(
                            'ATC slots are locked because one or more slots have been reserved.',
                        )
                    }}
                </div>

                <template v-else>
                    <!-- ICAO search -->
                    <div class="flex gap-2">
                        <Input
                            v-model="atcPositionsHttp.icao"
                            class="w-40 font-mono uppercase"
                            maxlength="4"
                            :placeholder="$t('ICAO')"
                            @keydown.enter.prevent="fetchAtcPositions"
                        />
                        <Button
                            type="button"
                            variant="secondary"
                            :disabled="
                                atcPositionsHttp.processing ||
                                !atcPositionsHttp.icao.trim()
                            "
                            @click="fetchAtcPositions"
                        >
                            <Loader2
                                v-if="atcPositionsHttp.processing"
                                class="size-4 animate-spin"
                            />
                            <Search v-else class="size-4" />
                            {{ $t('Search') }}
                        </Button>
                    </div>

                    <!-- Fetch error -->
                    <p
                        v-if="atcPositionsFetchError"
                        class="text-sm text-destructive"
                    >
                        {{ atcPositionsFetchError }}
                    </p>

                    <!-- Position list -->
                    <div
                        v-if="atcPositionsList.length > 0"
                        class="flex flex-col gap-3 rounded-md border p-3"
                    >
                        <p class="text-sm font-medium">
                            {{ $t('Select positions to generate slots for:') }}
                        </p>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label
                                v-for="position in atcPositionsList"
                                :key="position.compose_position"
                                class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 hover:bg-muted"
                            >
                                <Checkbox
                                    :model-value="
                                        selectedCallsigns.has(
                                            position.compose_position,
                                        )
                                    "
                                    @update:model-value="
                                        toggleCallsign(
                                            position.compose_position,
                                        )
                                    "
                                />
                                <span class="flex flex-col">
                                    <span class="font-mono text-sm font-medium">
                                        {{ position.compose_position }}
                                    </span>
                                    <span class="text-xs text-muted-foreground">
                                        {{ position.atc_callsign }} ·
                                        {{ position.frequency }} MHz
                                    </span>
                                </span>
                            </label>
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <Button
                                type="button"
                                size="sm"
                                :disabled="selectedCallsigns.size === 0"
                                @click="generateAtcSlots"
                            >
                                <Plus class="size-4" />
                                {{ $t('Generate Slots') }}
                            </Button>
                            <InputError :message="atcGenerationError" />
                        </div>
                    </div>
                </template>

                <!-- Slots table (always visible when slots exist) -->
                <div
                    v-if="slots.length > 0"
                    class="overflow-auto rounded-md border"
                >
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Callsign') }}</TableHead>
                                <TableHead>{{ $t('Starts At') }}</TableHead>
                                <TableHead>{{ $t('Ends At') }}</TableHead>
                                <TableHead v-if="!locked" class="w-10" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(slot, i) in slots" :key="i">
                                <TableCell class="font-mono">{{
                                    slot.callsign
                                }}</TableCell>
                                <TableCell>{{ slot.starts_at }}</TableCell>
                                <TableCell>{{ slot.ends_at }}</TableCell>
                                <TableCell v-if="!locked">
                                    <button
                                        type="button"
                                        class="text-muted-foreground hover:text-destructive"
                                        @click="removeSlot(i)"
                                    >
                                        <X class="size-4" />
                                    </button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Clear all -->
                <div v-if="slots.length > 0 && !locked">
                    <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        class="text-muted-foreground"
                        @click="clearSlots"
                    >
                        <X class="size-4" />
                        {{ $t('Clear all') }}
                    </Button>
                </div>

                <InputError :message="error" />
            </CardContent>
        </template>
    </Card>
</template>

<script setup lang="ts">
import {
    FileText,
    Lock,
    PlaneTakeoff,
    TriangleAlert,
    Upload,
    X,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { csvDataUri, normalizeDatetime, parseCsv } from '@/lib/utils';
import type { PilotSlotRow } from '@/types';

type PilotSlotCSV = {
    airline_icao: string;
    flight_number: string;
    aircraft: string;
    origin: string;
    destination: string;
    departure_date: string;
    departure_time: string;
    gate: string;
};

defineProps<{
    slots: PilotSlotRow[];
    enabled: boolean;
    error?: string;
    locked?: boolean;
}>();

const emit = defineEmits<{
    'update:slots': [slots: PilotSlotRow[]];
    'update:enabled': [value: boolean];
}>();

const fileInput = ref<HTMLInputElement | null>(null);

const templateCsvUrl = computed(() =>
    csvDataUri(
        'airline_icao,flight_number,aircraft,origin,destination,departure_date,departure_time,gate',
    ),
);

function onCsvChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const rows = parseCsv(e.target?.result as string) as PilotSlotCSV[];
        emit(
            'update:slots',
            rows.map((row): PilotSlotRow => ({
                ...row,
                departs_at:
                    row.departure_date && row.departure_time
                        ? normalizeDatetime(
                              `${row.departure_date} ${row.departure_time}`,
                          )
                        : `${row.departure_date} ${row.departure_time}`,
            })),
        );
    };
    reader.readAsText(file);
}

function clearSlots(): void {
    emit('update:slots', []);

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}
</script>

<template>
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
                                    'Upload a CSV file with pilot slot assignments.',
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
                            'Pilot slots are locked because one or more slots have been reserved.',
                        )
                    }}
                </div>

                <template v-else>
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
                            :href="templateCsvUrl"
                            download="pilot-slots-template.csv"
                            class="text-sm font-medium text-primary underline-offset-4 hover:underline"
                        >
                            {{ $t('Download template') }}
                        </a>
                    </div>

                    <div
                        class="mb-4 flex items-center gap-4 rounded-xl border border-yellow-500/20 bg-yellow-500/10 px-4 py-3"
                    >
                        <TriangleAlert
                            class="size-4 text-yellow-700 dark:text-yellow-200/75"
                        />
                        <p
                            class="text-sm text-yellow-700 dark:text-yellow-200/75"
                        >
                            {{
                                $t(
                                    'The departure_date must be in YYYY-MM-DD format, and departure_time in HH:MM (24h) format.',
                                )
                            }}
                        </p>
                    </div>

                    <!-- Upload -->
                    <div class="flex flex-wrap items-center gap-3">
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-md border px-3 py-2 text-sm transition-colors hover:bg-muted"
                        >
                            <Upload class="size-4" />
                            {{ $t('Upload CSV') }}
                            <input
                                ref="fileInput"
                                type="file"
                                accept=".csv"
                                class="sr-only"
                                @change="onCsvChange"
                            />
                        </label>
                        <Button
                            v-if="slots.length > 0"
                            type="button"
                            variant="ghost"
                            size="sm"
                            class="text-muted-foreground"
                            @click="clearSlots"
                        >
                            <X class="size-4" />
                            {{ $t('Clear') }}
                        </Button>
                    </div>
                </template>

                <!-- Inline preview (always visible) -->
                <div
                    v-if="slots.length > 0"
                    class="overflow-auto rounded-md border"
                >
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ $t('Airline ICAO') }}</TableHead>
                                <TableHead>{{ $t('Flight #') }}</TableHead>
                                <TableHead>{{ $t('Aircraft') }}</TableHead>
                                <TableHead>{{ $t('Origin') }}</TableHead>
                                <TableHead>{{ $t('Destination') }}</TableHead>
                                <TableHead>{{ $t('Departs At') }}</TableHead>
                                <TableHead>{{ $t('Gate') }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(slot, i) in slots" :key="i">
                                <TableCell class="font-mono">{{
                                    slot.airline_icao
                                }}</TableCell>
                                <TableCell>{{
                                    slot.flight_number || '—'
                                }}</TableCell>
                                <TableCell>{{ slot.aircraft }}</TableCell>
                                <TableCell class="font-mono">{{
                                    slot.origin
                                }}</TableCell>
                                <TableCell class="font-mono">{{
                                    slot.destination
                                }}</TableCell>
                                <TableCell>{{ slot.departs_at }}</TableCell>
                                <TableCell>{{ slot.gate || '—' }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <InputError :message="error" />
            </CardContent>
        </template>
    </Card>
</template>

<script setup lang="ts">
import { Lock, PlaneLanding, PlaneTakeoff } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
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
import { PilotSlotCategory, SlotsConstants } from '@/types';
import type { PilotSlotRow } from '@/types';
import PilotSlotSection from './PilotSlotSection.vue';

defineProps<{
    slots: PilotSlotRow[];
    enabled: boolean;
    error?: string;
    fieldErrors?: Record<string, string>;
    locked?: boolean;
}>();

const emit = defineEmits<{
    'update:slots': [slots: PilotSlotRow[]];
    'update:enabled': [value: boolean];
}>();
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
                                    'Add the departure and arrival flights available for this event.',
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
                <!-- Locked banner + read-only table -->
                <template v-if="locked">
                    <div
                        class="flex items-center gap-2 rounded-md border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-400"
                    >
                        <Lock class="size-4 shrink-0" />
                        {{
                            $t(
                                'Pilot slots are locked because one or more slots have been reserved.',
                            )
                        }}
                    </div>

                    <div
                        v-if="slots.length > 0"
                        class="overflow-auto rounded-md border"
                    >
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>{{
                                        $t('Airline ICAO')
                                    }}</TableHead>
                                    <TableHead>{{ $t('Flight #') }}</TableHead>
                                    <TableHead>{{ $t('Aircraft') }}</TableHead>
                                    <TableHead>{{ $t('Origin') }}</TableHead>
                                    <TableHead>{{
                                        $t('Destination')
                                    }}</TableHead>
                                    <TableHead>{{ $t('Category') }}</TableHead>
                                    <TableHead>{{ $t('EOBT') }}</TableHead>
                                    <TableHead>{{ $t('ETA') }}</TableHead>
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
                                    <TableCell>{{
                                        SlotsConstants.pilotCategoryLabels[
                                            slot.category
                                        ]
                                    }}</TableCell>
                                    <TableCell>{{ slot.departs_at }}</TableCell>
                                    <TableCell>{{
                                        slot.arrives_at || '—'
                                    }}</TableCell>
                                    <TableCell>{{
                                        slot.gate || '—'
                                    }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </template>

                <template v-else>
                    <PilotSlotSection
                        :category="PilotSlotCategory.DEPARTURE"
                        :icon="PlaneTakeoff"
                        :title="$t('Departures')"
                        :add-label="$t('Add departure slot')"
                        :empty-label="$t('No departure slots added yet.')"
                        :slots="slots"
                        :field-errors="fieldErrors"
                        @update:slots="(v) => emit('update:slots', v)"
                    />

                    <PilotSlotSection
                        :category="PilotSlotCategory.ARRIVAL"
                        :icon="PlaneLanding"
                        :title="$t('Arrivals')"
                        :add-label="$t('Add arrival slot')"
                        :empty-label="$t('No arrival slots added yet.')"
                        :slots="slots"
                        :field-errors="fieldErrors"
                        @update:slots="(v) => emit('update:slots', v)"
                    />
                </template>

                <InputError :message="error" />
            </CardContent>
        </template>
    </Card>
</template>

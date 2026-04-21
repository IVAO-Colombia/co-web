<script setup lang="ts">
import { CalendarDate, today, getLocalTimeZone, type DateValue } from '@internationalized/date';
import { format, parse } from 'date-fns';
import { CalendarIcon, Clock } from 'lucide-vue-next';
import { computed, nextTick, ref, watch } from 'vue';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        placeholder?: string;
        disabled?: boolean;
        /** ISO datetime string (yyyy-MM-ddTHH:mm) — only dates/times at or after this are selectable */
        minValue?: string;
    }>(),
    {
        modelValue: '',
        placeholder: 'Pick a date and time',
        disabled: false,
        minValue: undefined,
    },
);

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

// 30-minute interval options: 00:00, 00:30, 01:00, ..., 23:30
const allTimeOptions = Array.from({ length: 48 }, (_, i) => {
    const h = Math.floor(i / 2);
    const m = i % 2 === 0 ? '00' : '30';
    return `${String(h).padStart(2, '0')}:${m}`;
});

const minCalendarDate = computed<CalendarDate>(() => {
    if (props.minValue) {
        const normalized = props.minValue.replace('T', ' ');
        const parsed = parse(normalized, 'yyyy-MM-dd HH:mm', new Date());
        if (!isNaN(parsed.getTime())) {
            return new CalendarDate(parsed.getFullYear(), parsed.getMonth() + 1, parsed.getDate());
        }
    }
    return today(getLocalTimeZone());
});

const minTime = computed<string | undefined>(() => {
    if (!props.minValue || !selectedDate.value) return undefined;
    const normalized = props.minValue.replace('T', ' ');
    const parsed = parse(normalized, 'yyyy-MM-dd HH:mm', new Date());
    if (isNaN(parsed.getTime())) return undefined;
    const minDate = new CalendarDate(parsed.getFullYear(), parsed.getMonth() + 1, parsed.getDate());
    // Only restrict times when the selected date equals the min date
    if (selectedDate.value.compare(minDate) === 0) {
        return format(parsed, 'HH:mm');
    }
    return undefined;
});

const timeOptions = computed<string[]>(() => {
    if (!minTime.value) return allTimeOptions;
    return allTimeOptions.filter((t) => t >= minTime.value!);
});

function parseModelValue(value: string): { date: CalendarDate | undefined; time: string } {
    if (!value) return { date: undefined, time: '' };

    const normalized = value.replace('T', ' ');
    const parsed = parse(normalized, 'yyyy-MM-dd HH:mm', new Date());

    if (isNaN(parsed.getTime())) return { date: undefined, time: '' };

    return {
        date: new CalendarDate(parsed.getFullYear(), parsed.getMonth() + 1, parsed.getDate()),
        time: format(parsed, 'HH:mm'),
    };
}

const { date: initialDate, time: initialTime } = parseModelValue(props.modelValue);

const selectedDate = ref<CalendarDate | undefined>(initialDate);
const selectedTime = ref<string>(initialTime || '');
const open = ref(false);

watch(
    () => props.modelValue,
    (val) => {
        const { date, time } = parseModelValue(val ?? '');
        selectedDate.value = date;
        selectedTime.value = time;
    },
);

function buildIsoValue(): string {
    if (!selectedDate.value || !selectedTime.value) return '';
    const d = selectedDate.value;
    const dateStr = `${d.year}-${String(d.month).padStart(2, '0')}-${String(d.day).padStart(2, '0')}`;
    return `${dateStr}T${selectedTime.value}`;
}

function onDateSelect(date: DateValue | undefined): void {
    selectedDate.value = date ? new CalendarDate(date.year, date.month, date.day) : undefined;
    const value = buildIsoValue();
    if (value) emit('update:modelValue', value);
}

function onTimeSelect(time: string): void {
    selectedTime.value = time;
    const value = buildIsoValue();
    if (value) emit('update:modelValue', value);
    if (selectedDate.value !== undefined) open.value = false;
}

// CalendarDate is part of the DateValue union but Volar can't narrow it structurally
// due to ZonedDateTime's private fields — use an explicit cast via computed.
const calendarModelValue = computed<DateValue | undefined>(
    () => selectedDate.value as DateValue | undefined,
);

const displayValue = computed<string>(() => {
    if (!selectedDate.value || !selectedTime.value) return '';
    const d = selectedDate.value;
    const dateStr = `${d.year}-${String(d.month).padStart(2, '0')}-${String(d.day).padStart(2, '0')}`;
    const parsed = parse(`${dateStr} ${selectedTime.value}`, 'yyyy-MM-dd HH:mm', new Date());
    return format(parsed, 'd MMM yyyy, HH:mm') + ' ZULU';
});

const timeListRef = ref<HTMLElement | null>(null);

function scrollToSelected(): void {
    if (!timeListRef.value || !selectedTime.value) return;
    const idx = timeOptions.value.indexOf(selectedTime.value);
    if (idx !== -1) {
        const item = timeListRef.value.children[idx] as HTMLElement;
        item?.scrollIntoView({ block: 'center' });
    }
}

// Clear the selected time if it's no longer valid when minValue changes
watch(
    () => props.minValue,
    () => {
        if (selectedTime.value && minTime.value && selectedTime.value < minTime.value) {
            selectedTime.value = '';
            emit('update:modelValue', '');
        }
    },
);

watch(open, (isOpen) => {
    if (isOpen) nextTick(scrollToSelected);
});
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <button
                type="button"
                :disabled="disabled"
                :class="cn(
                    'border-input bg-background focus-visible:ring-ring flex h-9 w-full items-center gap-2 rounded-md border px-3 py-2 text-sm focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50',
                    !displayValue && 'text-muted-foreground',
                )"
            >
                <CalendarIcon class="size-4 shrink-0 opacity-50" />
                <span class="flex-1 text-left">{{ displayValue || placeholder }}</span>
            </button>
        </PopoverTrigger>

        <PopoverContent class="flex w-auto p-0" align="start">
            <!-- Calendar -->
            <Calendar
                :model-value="calendarModelValue"
                :min-value="minCalendarDate"
                layout="month-and-year"
                initial-focus
                @update:model-value="onDateSelect"
            />

            <!-- Time column -->
            <div class="flex flex-col border-l">
                <div class="flex items-center gap-1.5 border-b px-3 py-2">
                    <Clock class="text-muted-foreground size-3.5" />
                    <span class="text-xs font-semibold tracking-wide uppercase">ZULU</span>
                </div>
                <div
                    ref="timeListRef"
                    class="h-64 w-28 overflow-y-auto py-1"
                >
                    <button
                        v-for="time in timeOptions"
                        :key="time"
                        type="button"
                        :class="cn(
                            'mx-1 flex w-[calc(100%-8px)] cursor-pointer items-center justify-center rounded-md px-2 py-1.5 text-sm transition-colors',
                            selectedTime === time
                                ? 'bg-primary text-primary-foreground font-medium'
                                : 'hover:bg-accent hover:text-accent-foreground',
                        )"
                        @click="onTimeSelect(time)"
                    >
                        {{ time }}
                    </button>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>


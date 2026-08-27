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
        /** ISO datetime string (yyyy-MM-ddTHH:mm) — only dates/times strictly after this are selectable */
        minValue?: string;
        /** Show separate Hour/Minute lists for per-minute precision instead of the 30-minute list */
        preciseTime?: boolean;
    }>(),
    {
        modelValue: '',
        placeholder: 'Pick a date and time',
        disabled: false,
        minValue: undefined,
        preciseTime: false,
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
    return allTimeOptions.filter((t) => t > minTime.value!);
});

// Precise mode: an Hour list (00-23) and a Minute list (00-59) instead of the
// single 30-minute list, for fields that need per-minute precision.
const allHourOptions = Array.from({ length: 24 }, (_, h) => String(h).padStart(2, '0'));
const allMinuteOptions = Array.from({ length: 60 }, (_, m) => String(m).padStart(2, '0'));

const selectedHour = computed<string>(() => selectedTime.value ? selectedTime.value.slice(0, 2) : '');
const selectedMinute = computed<string>(() => selectedTime.value ? selectedTime.value.slice(3, 5) : '');

const hourOptions = computed<string[]>(() => {
    if (!minTime.value) return allHourOptions;
    const minHour = minTime.value.slice(0, 2);
    return allHourOptions.filter((h) => h >= minHour);
});

const minuteOptions = computed<string[]>(() => {
    if (!minTime.value) return allMinuteOptions;
    const minHour = minTime.value.slice(0, 2);
    // Only restrict minutes once an hour is picked and it equals the min hour.
    if (selectedHour.value && selectedHour.value === minHour) {
        const minMinute = minTime.value.slice(3, 5);
        return allMinuteOptions.filter((m) => m > minMinute);
    }
    return allMinuteOptions;
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

// Precise mode: whether the hour/minute were explicitly clicked this session,
// as opposed to `selectedTime` merely holding a defaulted '00' for the half
// not yet picked. Drives when the popover is allowed to auto-close.
const hourChosen = ref(false);
const minuteChosen = ref(false);

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

    if (selectedDate.value === undefined) return;

    // The plain 30-minute list is a single click that fully determines the
    // time. Precise mode is two separate clicks, so only close once both the
    // hour and the minute have actually been picked this session.
    if (!props.preciseTime || (hourChosen.value && minuteChosen.value)) {
        open.value = false;
    }
}

function selectPreciseHour(hour: string): void {
    hourChosen.value = true;
    onTimeSelect(`${hour}:${selectedMinute.value || '00'}`);
}

function selectPreciseMinute(minute: string): void {
    minuteChosen.value = true;
    onTimeSelect(`${selectedHour.value || '00'}:${minute}`);
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
    return format(parsed, 'd MMM yyyy, HH:mm') + ' UTC';
});

const timeListRef = ref<HTMLElement | null>(null);
const hourListRef = ref<HTMLElement | null>(null);
const minuteListRef = ref<HTMLElement | null>(null);

function scrollListToValue(el: HTMLElement | null, options: string[], value: string): void {
    if (!el || !value) return;
    const idx = options.indexOf(value);
    if (idx !== -1) {
        const item = el.children[idx] as HTMLElement;
        item?.scrollIntoView({ block: 'center' });
    }
}

function scrollToSelected(): void {
    if (props.preciseTime) {
        scrollListToValue(hourListRef.value, hourOptions.value, selectedHour.value);
        scrollListToValue(minuteListRef.value, minuteOptions.value, selectedMinute.value);
        return;
    }
    scrollListToValue(timeListRef.value, timeOptions.value, selectedTime.value);
}

// Clear the selected time if it's no longer valid when minValue changes
watch(
    () => props.minValue,
    () => {
        if (selectedTime.value && minTime.value && selectedTime.value <= minTime.value) {
            selectedTime.value = '';
            hourChosen.value = false;
            minuteChosen.value = false;
            emit('update:modelValue', '');
        }
    },
);

watch(open, (isOpen) => {
    if (isOpen) {
        // A value already set when opening only needs one more click to
        // finish (the other half keeps its valid value); a fresh/empty
        // picker needs a deliberate click on each list before it can close.
        hourChosen.value = !!selectedTime.value;
        minuteChosen.value = !!selectedTime.value;
        nextTick(scrollToSelected);
    }
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
                    <span class="text-xs font-semibold tracking-wide uppercase">UTC</span>
                </div>

                <!-- Precise mode: separate Hour / Minute lists -->
                <div v-if="preciseTime" class="flex">
                    <div class="flex flex-col border-r">
                        <div class="border-b px-2 py-1.5 text-center text-[10px] font-semibold tracking-wide text-muted-foreground uppercase">
                            {{ $t('Hour') }}
                        </div>
                        <div
                            ref="hourListRef"
                            class="h-64 w-14 overflow-y-auto py-1"
                        >
                            <button
                                v-for="hour in hourOptions"
                                :key="hour"
                                type="button"
                                :class="cn(
                                    'mx-1 flex w-[calc(100%-8px)] cursor-pointer items-center justify-center rounded-md px-1 py-1.5 text-sm transition-colors',
                                    selectedHour === hour
                                        ? 'bg-primary text-primary-foreground font-medium'
                                        : 'hover:bg-accent hover:text-accent-foreground',
                                )"
                                @click="selectPreciseHour(hour)"
                            >
                                {{ hour }}
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="border-b px-2 py-1.5 text-center text-[10px] font-semibold tracking-wide text-muted-foreground uppercase">
                            {{ $t('Minute') }}
                        </div>
                        <div
                            ref="minuteListRef"
                            class="h-64 w-14 overflow-y-auto py-1"
                        >
                            <button
                                v-for="minute in minuteOptions"
                                :key="minute"
                                type="button"
                                :class="cn(
                                    'mx-1 flex w-[calc(100%-8px)] cursor-pointer items-center justify-center rounded-md px-1 py-1.5 text-sm transition-colors',
                                    selectedMinute === minute
                                        ? 'bg-primary text-primary-foreground font-medium'
                                        : 'hover:bg-accent hover:text-accent-foreground',
                                )"
                                @click="selectPreciseMinute(minute)"
                            >
                                {{ minute }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Default mode: single 30-minute list -->
                <div
                    v-else
                    ref="timeListRef"
                    class="h-64 w-28 overflow-y-auto py-1"
                >
                    <p
                        v-if="timeOptions.length === 0"
                        class="px-2 py-1.5 text-center text-xs text-muted-foreground"
                    >
                        {{ $t('No times available for this date.') }}
                    </p>
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


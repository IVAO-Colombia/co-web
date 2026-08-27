import { utc } from '@date-fns/utc';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { format, isValid, parse, parseISO } from 'date-fns';
import { enUS, es } from 'date-fns/locale';
import { twMerge } from 'tailwind-merge';
import { TrainingRequestType } from '@/types';
import type { AtcTraining, PilotTraining } from '@/types';
import type { Locale, TrainingRequest } from '@/types';
import { AtcTrainings, PilotTrainings } from '@/types/trainings';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

const TIME_FORMATS = [
    'HH:mm:ss',
    'H:mm:ss',
    'HH:mm',
    'H:mm',
    'hh:mm:ss a',
    'h:mm:ss a',
    'hh:mm a',
    'h:mm a',
    'hh a',
    'h a',
];

const REFERENCE_DATE = new Date(2000, 0, 1);

/**
 * Normalizes a time string of unknown locale format to HH:mm (24-hour).
 * Returns the original string if no known format matches.
 */
export function normalizeTime(value: string): string {
    const trimmed = value.trim();

    for (const fmt of TIME_FORMATS) {
        const parsed = parse(trimmed, fmt, REFERENCE_DATE);

        if (isValid(parsed)) {
            return format(parsed, 'HH:mm');
        }
    }

    return trimmed;
}

/**
 * Formats a datetime string for display in UTC.
 * Output example: "1 Jun 2026, 18:00 UTC"
 */
export function formatDateTime(dateStr: string, locale: Locale): string {
    const date = parseISO(dateStr);
    // Shift the timestamp so that format() (which uses local time) outputs UTC values
    const utcDate = new Date(
        date.getTime() + date.getTimezoneOffset() * 60 * 1000,
    );
    const dfLocale = locale === 'en' ? enUS : es;

    return format(utcDate, 'd MMM yyyy, HH:mm', { locale: dfLocale }) + ' UTC';
}

export function getDateParts(
    startsAt: string,
    locale: Locale,
): {
    day: string;
    month: string;
    year: string;
    time: string;
} {
    const utcDate = parseISO(startsAt, { in: utc });
    const dfLocale = locale === 'en' ? enUS : es;

    const day = format(utcDate, 'dd');
    const month = format(utcDate, 'MMM', { locale: dfLocale })
        .replace('.', '')
        .toUpperCase();
    const year = format(utcDate, 'yyyy');
    const time = `${format(utcDate, 'HH:mm')} UTC`;

    return { day, month, year, time };
}

export function toUTCDateTime(value: string | null | undefined): string {
    if (!value) {
        return '';
    }

    try {
        return format(parseISO(value, { in: utc }), 'yyyy-MM-dd HH:mm', {
            in: utc,
        });
    } catch {
        return '';
    }
}

export function formatAtcTime(time: string): string {
    return format(parseISO(time, { in: utc }), 'HH:mm') + ' UTC';
}

export function getTrainingCategoryLabel(request: TrainingRequest | null) {
    if (!request) {
        return '';
    }

    return request.type === TrainingRequestType.ATC
        ? AtcTrainings[request.category as AtcTraining].label
        : PilotTrainings[request.category as PilotTraining].label;
}

/**
 * Formats a duration in seconds as "Hh Mmin".
 */
export function formatHours(seconds: number): string {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);

    return `${h}h ${m}min`;
}

/**
 * Resolves an ISO 3166-1 alpha-2 code (e.g. "CO") to a localized country
 * name (e.g. "Colombia" / "Colombia"). Falls back to the raw code for
 * anything Intl does not recognize (some IVAO divisions are not countries).
 */
export function countryName(
    code: string | null | undefined,
    locale: Locale,
): string | null {
    if (!code) {
        return null;
    }

    try {
        const displayNames = new Intl.DisplayNames([locale], {
            type: 'region',
        });

        return displayNames.of(code) ?? code;
    } catch {
        return code;
    }
}

import { utc } from '@date-fns/utc';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { format, isValid, parse, parseISO } from 'date-fns';
import { enUS, es } from 'date-fns/locale';
import { twMerge } from 'tailwind-merge';
import type { Locale } from '@/types';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function parseCsv(text: string): Record<string, string>[] {
    const lines = text.trim().split(/\r?\n/);

    if (lines.length < 2) {
        return [];
    }

    const headers = lines[0]
        .split(',')
        .map((h) => h.trim().replace(/^"|"$/g, ''));

    return lines
        .slice(1)
        .filter((line) => line.trim() !== '')
        .map((line) => {
            const values = line
                .split(',')
                .map((v) => v.trim().replace(/^"|"$/g, ''));

            return Object.fromEntries(
                headers.map((h, i) => [h, values[i] ?? '']),
            );
        });
}

export function csvDataUri(headers: string): string {
    return `data:text/csv;charset=utf-8,${encodeURIComponent(headers + '\n')}`;
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

const DATETIME_FORMATS = [
    "yyyy-MM-dd'T'HH:mm:ss",
    "yyyy-MM-dd'T'HH:mm",
    'yyyy-MM-dd HH:mm:ss',
    'yyyy-MM-dd HH:mm',
    'dd/MM/yyyy HH:mm:ss',
    'dd/MM/yyyy HH:mm',
    'dd/MM/yyyy',
    'd/M/yyyy HH:mm',
    'd/M/yyyy',
    'yyyy/MM/dd HH:mm',
    'yyyy/MM/dd',
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
 * Normalizes a datetime string of unknown locale format to yyyy-MM-dd HH:mm.
 * Tries DD/MM/YYYY before MM/DD/YYYY to match Latin American locale.
 * Returns the original string if no known format matches.
 */
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

export function normalizeDatetime(value: string): string {
    const trimmed = value.trim();

    for (const fmt of DATETIME_FORMATS) {
        const parsed = parse(trimmed, fmt, REFERENCE_DATE);

        if (isValid(parsed)) {
            return format(parsed, 'yyyy-MM-dd HH:mm');
        }
    }

    return trimmed;
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

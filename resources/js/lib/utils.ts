import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

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
<script setup lang="ts">
import { Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';
import { useLocale } from '@/composables/useLocale';

const { locale, updateLocale } = useLocale();
const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'en' as const, label: 'EN' },
    { value: 'es' as const, label: 'ES' },
];
</script>

<template>
    <div
        class="inline-flex items-center gap-0.5 rounded-lg border border-border/60 bg-muted/40 p-1"
    >
        <!-- Idioma -->
        <button
            v-for="{ value, label } in tabs"
            :key="value"
            type="button"
            @click="updateLocale(value)"
            :class="[
                'min-w-8 cursor-pointer rounded-md px-2 py-1.5 text-xs font-medium transition-colors',
                locale === value
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
        >
            {{ label }}
        </button>

        <div class="mx-1 h-4 w-px bg-border" />

        <!-- Tema -->
        <button
            type="button"
            aria-label="Light mode"
            @click="updateAppearance('light')"
            :class="[
                'flex h-7 w-7 cursor-pointer items-center justify-center rounded-md transition-colors',
                appearance === 'light'
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
        >
            <Sun class="h-3.5 w-3.5" />
        </button>

        <button
            type="button"
            aria-label="Dark mode"
            @click="updateAppearance('dark')"
            :class="[
                'flex h-7 w-7 cursor-pointer items-center justify-center rounded-md transition-colors',
                appearance === 'dark'
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
        >
            <Moon class="h-3.5 w-3.5" />
        </button>
    </div>
</template>

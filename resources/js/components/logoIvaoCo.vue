<script setup lang="ts">
import { computed } from 'vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

defineOptions({
    inheritAttrs: false,
});

type SizeValue = number | string;

const props = withDefaults(
    defineProps<{
        src?: string;
        alt?: string;
        width?: SizeValue;
        height?: SizeValue;
        showText?: boolean;
        titleText?: string;
        countryText?: string;
        isDark?: boolean;
        showExtraText?: boolean;
        extraText?: string;
        class?: HTMLAttributes['class'];
        imgClass?: HTMLAttributes['class'];
    }>(),
    {
        src: '',
        alt: 'IVAO Colombia',
        width: 'auto',
        height: 42,
        isDark: false,
        showText: true,
        titleText: 'IVAO',
        countryText: 'COLOMBIA',
        showExtraText: false,
        extraText: '',
    },
);

function toCssSize(value?: SizeValue): string | undefined {
    if (value === undefined || value === null || value === '') {
        return undefined;
    }

    return typeof value === 'number' ? `${value}px` : value;
}

const containerStyle = computed(() => ({
    width: toCssSize(props.width),
    height: toCssSize(props.height),
}));
</script>

<template>
    <span
        :class="cn('inline-flex items-center gap-2', props.class)"
        :style="containerStyle"
        v-bind="$attrs"
    >
        <img
            :src="props.src"
            :alt="props.alt"
            :class="
                cn(
                    'block h-full w-auto shrink-0 object-contain',
                    props.imgClass,
                )
            "
            decoding="async"
            draggable="false"
        />

        <span
            v-if="props.showText"
            class="flex flex-col leading-none"
            :class="props.isDark ? 'text-white' : 'text-[#0D2C99]'"
        >
            <span
                class="text-[0.88rem] font-black tracking-tight sm:text-[1.75rem]"
            >
                {{ props.titleText }}
            </span>
            <span
                class="text-[0.52rem] font-semibold tracking-[0.22em] uppercase sm:text-[0.65rem]"
                :class="props.isDark ? 'text-white/80' : 'text-[#3C55AC]'"
            >
                {{ props.countryText }}
            </span>
        </span>

        <template v-if="props.showExtraText && props.extraText">
            <span class="hidden text-4xl text-white/80 sm:block">|</span>
            <span
                class="hidden font-heading text-2xl font-bold tracking-tight text-white sm:block"
            >
                {{ props.extraText }}
            </span>
        </template>
    </span>
</template>

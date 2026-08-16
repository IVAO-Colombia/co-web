<script setup lang="ts">
import { toPng } from 'html-to-image';
import { nextTick, ref } from 'vue';
import EventImageCard from '@/components/EventImageCard.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = withDefaults(
    defineProps<{
        initialValues?: {
            title?: string;
            range?: string;
            position?: string;
            localTime?: string;
            date?: string;
        };
        asDialog?: boolean;
    }>(),
    {
        initialValues: () => ({}),
        asDialog: false,
    },
);

const emit = defineEmits<{
    imageGenerated: [file: File];
}>();

const title = ref(props.initialValues.title ?? '');
const range = ref(props.initialValues.range ?? '');
const position = ref(props.initialValues.position ?? '');
const localTime = ref(props.initialValues.localTime ?? '');
const date = ref(props.initialValues.date ?? '');

const cardRef = ref<HTMLDivElement | null>(null);
const isGenerating = ref(false);

async function captureImage(): Promise<string> {
    await nextTick();

    // cardRef points to the wrapper div; the card's root element is its first child.
    const el = cardRef.value?.firstElementChild as HTMLElement | null;

    if (!el) {
        throw new Error('Card element not found');
    }

    return toPng(el, {
        width: 1200,
        height: 675,
        pixelRatio: 1,
        skipAutoScale: true,
        // Prevents SecurityError from cross-origin stylesheets (Google Fonts, Vite dev server).
        // Fonts are already loaded in the browser at capture time so they still render correctly.
        skipFonts: true,
    });
}

async function downloadImage(): Promise<void> {
    isGenerating.value = true;

    try {
        const dataUrl = await captureImage();
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = `ivao-co-${title.value || 'event'}.png`;
        link.click();
    } finally {
        isGenerating.value = false;
    }
}

async function useImage(): Promise<void> {
    isGenerating.value = true;

    try {
        const dataUrl = await captureImage();
        const response = await fetch(dataUrl);
        const blob = await response.blob();
        const file = new File([blob], `ivao-co-${title.value || 'event'}.png`, {
            type: 'image/png',
        });
        emit('imageGenerated', file);
    } finally {
        isGenerating.value = false;
    }
}

// Scale factor for the visible preview (card is 1200×675, preview shown at 600×337.5)
const PREVIEW_SCALE = 0.5;
const previewWidth = 1200 * PREVIEW_SCALE;
const previewHeight = 675 * PREVIEW_SCALE;
</script>

<template>
    <div class="flex flex-col gap-6">
        <!-- Form fields -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex flex-col gap-1.5">
                <Label>{{ $t('Title') }}</Label>
                <Input v-model="title" :placeholder="$t('e.g. ADC')" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label>{{ $t('Range / Category') }}</Label>
                <Input
                    v-model="range"
                    :placeholder="$t('e.g. Entrenamiento')"
                />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label>{{ $t('Position') }}</Label>
                <Input v-model="position" :placeholder="$t('e.g. SKCL_TWR')" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label>{{ $t('Local Time') }}</Label>
                <Input v-model="localTime" :placeholder="$t('e.g. 1900 HLC')" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label>{{ $t('Date') }}</Label>
                <Input v-model="date" :placeholder="$t('e.g. 16.02.25')" />
            </div>
        </div>

        <!-- Live preview (scaled-down) -->
        <div class="flex flex-col gap-2">
            <span class="text-sm text-muted-foreground">{{
                $t('Preview')
            }}</span>
            <div
                class="overflow-hidden rounded-lg border"
                :style="{
                    width: `${previewWidth}px`,
                    height: `${previewHeight}px`,
                    maxWidth: '100%',
                }"
            >
                <div
                    :style="{
                        transform: `scale(${PREVIEW_SCALE})`,
                        transformOrigin: 'top left',
                        width: '1200px',
                        height: '675px',
                    }"
                >
                    <EventImageCard
                        :title="title"
                        :range="range"
                        :position="position"
                        :local-time="localTime"
                        :date="date"
                    />
                </div>
            </div>
        </div>

        <!-- Hidden full-size card for capture -->
        <div
            style="
                position: fixed;
                left: -9999px;
                top: -9999px;
                pointer-events: none;
            "
            aria-hidden="true"
        >
            <div ref="cardRef">
                <EventImageCard
                    :title="title"
                    :range="range"
                    :position="position"
                    :local-time="localTime"
                    :date="date"
                />
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <Button :disabled="isGenerating" @click="downloadImage">
                {{ isGenerating ? $t('Generating…') : $t('Download PNG') }}
            </Button>

            <Button
                v-if="asDialog"
                variant="outline"
                :disabled="isGenerating"
                @click="useImage"
            >
                {{ $t('Use this image') }}
            </Button>
        </div>
    </div>
</template>

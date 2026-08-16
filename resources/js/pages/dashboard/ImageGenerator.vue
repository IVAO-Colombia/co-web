<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import EventImageGenerator from '@/components/EventImageGenerator.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { imageGenerator } from '@/routes/dashboard';

setLayoutProps({
    breadcrumbs: [
        { title: wTrans('Image Generator'), href: imageGenerator().url },
    ],
});
</script>

<template>
    <Head :title="$t('Image Generator')" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>{{ $t('Event Image Generator') }}</CardTitle>
                <CardDescription>
                    {{
                        $t(
                            'Fill in the fields below to generate a branded announcement image for your event.',
                        )
                    }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <EventImageGenerator />
            </CardContent>
        </Card>
    </div>
</template>

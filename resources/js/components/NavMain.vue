<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { isRef } from 'vue';

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>
            {{ $t('Menber') }}
        </SidebarGroupLabel>

        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title.toString()">
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="isRef(item.title) ? item.title.value : item.title"
                    class="group relative h-11 overflow-hidden rounded-xl px-3 transition-all duration-200 group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:px-0 hover:bg-(--color-primary)/8 hover:text-(--color-primary) data-[active=true]:bg-(--color-primary) data-[active=true]:text-white data-[active=true]:shadow-(--color-primary)/25 data-[active=true]:shadow-md dark:hover:bg-(--color-primary)/15 dark:hover:text-blue-300"
                >
                    <Link
                        :href="item.href"
                        class="flex w-full items-center gap-3 group-data-[collapsible=icon]:justify-center"
                    >
                        <span
                            class="absolute left-0 h-6 w-1 rounded-r-full bg-transparent transition-all duration-200 group-data-[active=true]:bg-blue-200 group-data-[collapsible=icon]:hidden dark:group-data-[active=true]:bg-blue-300"
                        />

                        <component
                            :is="item.icon"
                            class="relative z-10 h-[19px] w-[19px] shrink-0 transition-transform duration-200 group-hover:scale-110"
                        />

                        <span
                            class="relative z-10 truncate text-sm font-medium transition-all duration-200 group-data-[collapsible=icon]:hidden"
                        >
                            {{ item.title }}
                        </span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>

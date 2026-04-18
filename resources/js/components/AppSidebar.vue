<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { Calendar1, LayoutGrid } from 'lucide-vue-next';
import type { ComputedRef } from 'vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { usePermissions } from '@/composables/usePermissions';
import { Permission } from '@/types';
import type { NavItem } from '@/types';
import { dashboard } from '@/routes';
import events from '@/routes/events';

const { hasPermission } = usePermissions();

const mainNavItems: ComputedRef<NavItem[]> = computed(() =>
    [
        {
            title: wTrans('Dashboard'),
            href: dashboard(),
            icon: LayoutGrid,
            visible: true,
        },
    ].filter((item) => item.visible),
);

const footerNavItems: ComputedRef<NavItem[]> = computed(() =>
    [
        {
            title: wTrans('Events'),
            href: events.index(),
            icon: Calendar1,
            visible: hasPermission(Permission.VIEW_EVENTS),
        },
    ].filter((item) => item.visible),
);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter
                v-if="hasPermission(Permission.STAFF_ACCESS)"
                :items="footerNavItems"
            />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

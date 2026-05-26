<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import {
    Calendar1,
    GraduationCap,
    LayoutGrid,
    CalendarCheck,
    Image,
} from 'lucide-vue-next';
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
import { imageGenerator } from '@/routes/dashboard';
import events from '@/routes/dashboard/events';
import { index as reservationsIndex } from '@/routes/dashboard/reservations';
import { index as staffTrainingRequestsIndex } from '@/routes/dashboard/staff/training-requests';
import { index as trainingsIndex } from '@/routes/dashboard/trainings';

const { hasPermission } = usePermissions();

const mainNavItems: ComputedRef<NavItem[]> = computed(() =>
    [
        {
            title: wTrans('Dashboard'),
            href: dashboard(),
            icon: LayoutGrid,
            visible: true,
        },
        {
            title: wTrans('My Reservations'),
            href: reservationsIndex(),
            icon: CalendarCheck,
            visible: true,
        },
        {
            title: wTrans('My Trainings'),
            href: trainingsIndex(),
            icon: GraduationCap,
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
        {
            title: wTrans('Event Image Generator'),
            href: imageGenerator(),
            icon: Image,
            visible: hasPermission(Permission.GENERATE_EVENT_IMAGES),
        },
        {
            title: wTrans('Training Requests'),
            href: staffTrainingRequestsIndex(),
            icon: GraduationCap,
            visible: hasPermission(Permission.MANAGE_TRAINING_REQUESTS),
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

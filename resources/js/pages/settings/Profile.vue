<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import { edit } from '@/routes/profile';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Profile settings',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Profile settings" />

    <h1 class="sr-only">Profile settings</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="IVAO Profile information"
            description="Update your name and email address"
        />

        <div class="flex">
            <Table>
                <TableBody>
                    <TableRow>
                        <TableCell class="font-medium"> Name </TableCell>
                        <TableCell>{{ user.name }}</TableCell>
                    </TableRow>
                    <TableRow>
                        <TableCell class="font-medium"> VID </TableCell>
                        <TableCell>{{ user.vid }}</TableCell>
                    </TableRow>
                    <TableRow>
                        <TableCell class="font-medium"> Email </TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                    </TableRow>
                    <TableRow>
                        <TableCell class="font-medium"> Division </TableCell>
                        <TableCell>{{ user.division }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>

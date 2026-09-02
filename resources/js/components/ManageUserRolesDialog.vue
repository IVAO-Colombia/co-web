<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { unref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { update as rolesUpdate } from '@/routes/dashboard/staff/users/roles';
import { RoleConstants } from '@/types';
import type { Role, UserListRow } from '@/types';

const props = defineProps<{
    user: UserListRow | null;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const form = useForm({
    roles: [] as Role[],
});

watch(
    () => props.user,
    (user) => {
        if (user) {
            form.clearErrors();
            form.roles = [...user.roles];
        }
    },
    { immediate: true },
);

const roleLabels = RoleConstants.labels;

function toggleRole(role: Role): void {
    const index = form.roles.indexOf(role);

    if (index === -1) {
        form.roles.push(role);
    } else {
        form.roles.splice(index, 1);
    }
}

function save(): void {
    if (!props.user) {
        return;
    }

    form.patch(rolesUpdate.url({ user: props.user.id }), {
        preserveScroll: true,
        onSuccess: () => {
            emit('update:open', false);
            toast.success(wTrans('Roles updated.'));
        },
    });
}
</script>

<template>
    <Dialog :open="user !== null" @update:open="emit('update:open', $event)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ $t('Manage roles') }}</DialogTitle>
                <DialogDescription v-if="user">
                    {{ user.name }} · VID {{ user.vid }}
                </DialogDescription>
            </DialogHeader>

            <p class="text-sm text-muted-foreground">
                {{
                    $t(
                        "Roles are re-synced from the member's IVAO staff positions on their next login, so manual changes may be overwritten.",
                    )
                }}
            </p>

            <div class="max-h-[45vh] space-y-1 overflow-y-auto">
                <label
                    v-for="(label, role) in roleLabels"
                    :key="role"
                    class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 hover:bg-muted"
                >
                    <Checkbox
                        :model-value="form.roles.includes(role)"
                        @update:model-value="toggleRole(role)"
                    />
                    <span class="text-sm">{{ unref(label) }}</span>
                </label>
            </div>

            <p v-if="form.errors.roles" class="text-sm text-destructive">
                {{ form.errors.roles }}
            </p>

            <DialogFooter>
                <Button
                    variant="outline"
                    :disabled="form.processing"
                    @click="emit('update:open', false)"
                >
                    {{ $t('Cancel') }}
                </Button>
                <Button :disabled="form.processing" @click="save">
                    {{ $t('Save') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

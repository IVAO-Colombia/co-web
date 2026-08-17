<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { Pencil } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { update } from '@/routes/dashboard/staff/training-requests';
import { store as notesStore } from '@/routes/dashboard/staff/training-requests/notes';
import type { TrainingNoteVisibility } from '@/types';

const props = defineProps<{
    trainingRequestId: number;
    title: string;
    description: string;
    content: string | null;
    /** The training request column this section reads and writes. */
    field: 'public_observations' | 'internal_observations';
    visibility: TrainingNoteVisibility;
    addPlaceholder: string;
    canEdit: boolean;
    canAdd: boolean;
}>();

const editing = ref(false);

const editForm = useForm<Record<string, string>>({
    [props.field]: props.content ?? '',
});

function startEditing() {
    editForm[props.field] = props.content ?? '';
    editing.value = true;
}

function cancelEditing() {
    editForm.clearErrors();
    editing.value = false;
}

function saveEdit() {
    editForm.patch(update.url({ trainingRequest: props.trainingRequestId }), {
        preserveScroll: true,
        onSuccess: () => {
            editing.value = false;
            toast.success(wTrans('Notes updated.'));
        },
    });
}

const addForm = useForm({
    body: '',
    visibility: props.visibility,
});

function addNote() {
    addForm.post(notesStore.url({ trainingRequest: props.trainingRequestId }), {
        preserveScroll: true,
        onSuccess: () => {
            addForm.reset('body');
            toast.success(wTrans('Note added.'));
        },
    });
}
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p class="text-sm font-medium">{{ title }}</p>
                <p class="text-xs text-muted-foreground">{{ description }}</p>
            </div>
            <Button
                v-if="canEdit && !editing"
                variant="ghost"
                size="sm"
                :title="$t('Edit notes')"
                @click="startEditing"
            >
                <Pencil class="mr-1.5 size-3.5" />
                {{ $t('Edit') }}
            </Button>
        </div>

        <!-- Existing notes -->
        <template v-if="editing">
            <Textarea v-model="editForm[field]" rows="6" />
            <p v-if="editForm.errors[field]" class="text-sm text-destructive">
                {{ editForm.errors[field] }}
            </p>
            <div class="flex items-center gap-2">
                <Button
                    size="sm"
                    :disabled="editForm.processing"
                    @click="saveEdit"
                >
                    {{ editForm.processing ? $t('Saving...') : $t('Save') }}
                </Button>
                <Button variant="ghost" size="sm" @click="cancelEditing">
                    {{ $t('Cancel') }}
                </Button>
            </div>
        </template>
        <p
            v-else-if="content"
            class="rounded-md border bg-muted/40 p-3 text-sm leading-relaxed whitespace-pre-wrap"
        >
            {{ content }}
        </p>
        <p v-else class="text-sm text-muted-foreground">
            {{ $t('No notes yet.') }}
        </p>

        <!-- Add a new note -->
        <div v-if="canAdd && !editing" class="flex flex-col gap-2">
            <Textarea
                v-model="addForm.body"
                :placeholder="addPlaceholder"
                rows="3"
            />
            <p v-if="addForm.errors.body" class="text-sm text-destructive">
                {{ addForm.errors.body }}
            </p>
            <div class="flex justify-end">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="addForm.processing || addForm.body.trim() === ''"
                    @click="addNote"
                >
                    {{ $t('Add Note') }}
                </Button>
            </div>
        </div>
    </div>
</template>

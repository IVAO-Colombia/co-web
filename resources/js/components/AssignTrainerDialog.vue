<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { wTrans } from 'laravel-vue-i18n';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { getTrainingCategoryLabel } from '@/lib/utils';
import { update as trainerUpdate } from '@/routes/dashboard/staff/training-requests/trainer';
import type { AssignableTrainer, TrainingRequest } from '@/types';

const UNASSIGNED = 'none';

const props = defineProps<{
    trainingRequest: TrainingRequest | null;
    assignableTrainers: AssignableTrainer[];
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const form = useForm({
    trainer_id: UNASSIGNED,
});

watch(
    () => props.trainingRequest,
    (trainingRequest) => {
        if (trainingRequest) {
            form.clearErrors();
            form.trainer_id = trainingRequest.trainer_id
                ? String(trainingRequest.trainer_id)
                : UNASSIGNED;
        }
    },
    { immediate: true },
);

const title = computed(() => getTrainingCategoryLabel(props.trainingRequest));

function save() {
    if (!props.trainingRequest) {
        return;
    }

    form.transform((data) => ({
        trainer_id:
            data.trainer_id === UNASSIGNED ? null : Number(data.trainer_id),
    })).patch(
        trainerUpdate.url({ trainingRequest: props.trainingRequest.id }),
        {
            preserveScroll: true,
            onSuccess: () => {
                emit('update:open', false);
                toast.success(wTrans('Trainer updated.'));
            },
        },
    );
}
</script>

<template>
    <Dialog
        :open="trainingRequest !== null"
        @update:open="emit('update:open', $event)"
    >
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ $t('Assign Trainer') }}</DialogTitle>
                <DialogDescription>{{ title }}</DialogDescription>
            </DialogHeader>

            <div class="flex flex-col gap-1.5">
                <Select v-model="form.trainer_id">
                    <SelectTrigger id="assign-trainer" class="w-full">
                        <SelectValue :placeholder="$t('Select a trainer...')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem :value="UNASSIGNED">
                            {{ $t('Unassigned') }}
                        </SelectItem>
                        <SelectItem
                            v-for="trainer in assignableTrainers"
                            :key="trainer.id"
                            :value="String(trainer.id)"
                        >
                            {{ trainer.name }}
                            <span class="text-muted-foreground">
                                (VID {{ trainer.vid }} · ATC
                                {{ trainer.atc_trainings_count }} · Pilot
                                {{ trainer.pilot_trainings_count }})
                            </span>
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p
                    v-if="form.errors.trainer_id"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.trainer_id }}
                </p>
            </div>

            <DialogFooter>
                <Button
                    variant="outline"
                    :disabled="form.processing"
                    @click="emit('update:open', false)"
                >
                    {{ $t('Cancel') }}
                </Button>
                <Button :disabled="form.processing" @click="save">
                    {{ $t('Assign') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

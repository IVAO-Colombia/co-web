<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { transChoice, wTrans } from 'laravel-vue-i18n';
import { ChevronLeft, ChevronRight, Pencil, X } from 'lucide-vue-next';
import { computed, ref, unref, watch } from 'vue';
import ManageUserRolesDialog from '@/components/ManageUserRolesDialog.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import { useDebounce } from '@/composables/useDebounce';
import { useLocale } from '@/composables/useLocale';
import { usePermissions } from '@/composables/usePermissions';
import { countryName, formatHours } from '@/lib/utils';
import { index } from '@/routes/dashboard/staff/users';
import { ATCRatings, Permission, PilotRatings, RoleConstants } from '@/types';
import type { LengthAwarePaginator, UserListRow } from '@/types';
import type { ATCRating, PilotRating, Role } from '@/types';

const props = defineProps<{
    users: LengthAwarePaginator<number, UserListRow>;
    divisions: string[];
    filters: {
        query?: string;
        role?: Role | 'none';
        division?: string;
        atc_rating?: ATCRating;
        pilot_rating?: PilotRating;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: wTrans('Users'), href: index() }],
    },
});

const { locale } = useLocale();
const { hasPermission } = usePermissions();

const canManageRoles = hasPermission(Permission.MANAGE_USER_ROLES);
const editingUser = ref<UserListRow | null>(null);

const query = ref(props.filters.query ?? '');
const role = ref<Role | 'none' | ''>(props.filters.role ?? '');
const division = ref(props.filters.division ?? '');
const atcRating = ref<string>(
    props.filters.atc_rating != null ? String(props.filters.atc_rating) : '',
);
const pilotRating = ref<string>(
    props.filters.pilot_rating != null
        ? String(props.filters.pilot_rating)
        : '',
);

function applyFilters(): void {
    router.get(
        index(),
        {
            query: query.value || undefined,
            role: role.value || undefined,
            division: division.value || undefined,
            atc_rating: atcRating.value || undefined,
            pilot_rating: pilotRating.value || undefined,
        },
        { preserveState: true, replace: true },
    );
}

const debouncedApplyFilters = useDebounce(applyFilters, 350);

watch(query, () => debouncedApplyFilters());
watch([role, division, atcRating, pilotRating], applyFilters);

function clearFilters(): void {
    query.value = '';
    role.value = '';
    division.value = '';
    atcRating.value = '';
    pilotRating.value = '';
}

const hasActiveFilters = () =>
    query.value !== '' ||
    role.value !== '' ||
    division.value !== '' ||
    atcRating.value !== '' ||
    pilotRating.value !== '';

const roleLabels = RoleConstants.labels;

const links = computed(() =>
    props.users.links.filter(
        (l) => !l.label.includes('&laquo;') && !l.label.includes('&raquo;'),
    ),
);

function divisionLabel(code: string): string {
    return countryName(code, locale.value) ?? code;
}
</script>

<template>
    <Head :title="$t('Users')" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ $t('Users') }}
            </h1>
            <p class="text-sm text-muted-foreground">
                V1.
                {{ users.total.toLocaleString() }}
                {{ transChoice('user|users', users.total) }}
            </p>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative min-w-56 flex-1">
                <Input
                    v-model="query"
                    :placeholder="$t('Search by name, VID or email...')"
                    class="pr-8"
                />
                <button
                    v-if="query"
                    class="absolute top-1/2 right-2.5 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    @click="query = ''"
                >
                    <X class="size-3.5" />
                </button>
            </div>

            <Select v-model="role">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('All roles')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="none">{{ $t('No role') }}</SelectItem>
                    <SelectItem
                        v-for="(label, key) in roleLabels"
                        :key="key"
                        :value="key"
                    >
                        {{ unref(label) }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="division">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('All divisions')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="code in divisions"
                        :key="code"
                        :value="code"
                    >
                        {{ divisionLabel(code) }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="atcRating">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('ATC rating')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="(rating, value) in ATCRatings"
                        :key="value"
                        :value="String(value)"
                    >
                        {{ rating.key }} — {{ rating.label }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="pilotRating">
                <SelectTrigger>
                    <SelectValue :placeholder="$t('Pilot rating')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="(rating, value) in PilotRatings"
                        :key="value"
                        :value="String(value)"
                    >
                        {{ rating.key }} — {{ rating.label }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Button
                v-if="hasActiveFilters()"
                variant="ghost"
                size="sm"
                @click="clearFilters"
            >
                <X class="size-3.5" />
                {{ $t('Clear filters') }}
            </Button>
        </div>

        <!-- Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>{{ $t('Member') }}</TableHead>
                        <TableHead>{{ $t('Roles') }}</TableHead>
                        <TableHead>{{ $t('Division') }}</TableHead>
                        <TableHead>{{ $t('ATC') }}</TableHead>
                        <TableHead>{{ $t('Pilot') }}</TableHead>
                        <TableHead v-if="canManageRoles">
                            <span class="sr-only">{{ $t('Actions') }}</span>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableEmpty
                        v-if="users.data.length === 0"
                        :colspan="canManageRoles ? 6 : 5"
                    >
                        {{ $t('No users found.') }}
                    </TableEmpty>
                    <TableRow v-for="user in users.data" :key="user.id">
                        <TableCell>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium">{{
                                    user.name
                                }}</span>
                                <span class="text-xs text-muted-foreground">
                                    VID {{ user.vid }} · {{ user.email }}
                                </span>
                            </div>
                        </TableCell>
                        <TableCell>
                            <div
                                v-if="user.roles.length > 0"
                                class="flex flex-wrap gap-1"
                            >
                                <Badge
                                    v-for="userRole in user.roles"
                                    :key="userRole"
                                    variant="secondary"
                                >
                                    {{ unref(roleLabels[userRole]) }}
                                </Badge>
                            </div>
                            <span v-else class="text-sm text-muted-foreground"
                                >—</span
                            >
                        </TableCell>
                        <TableCell class="text-sm">
                            <template v-if="user.division_id">
                                {{ divisionLabel(user.division_id) }}
                                <span class="text-muted-foreground"
                                    >({{ user.division_id }})</span
                                >
                            </template>
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell class="text-sm">
                            <div
                                v-if="user.atc_rating !== null"
                                class="flex flex-wrap items-center"
                            >
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger
                                            as="div"
                                            class="flex items-center"
                                        >
                                            <span
                                                class="inline-flex items-center gap-1.5 font-medium"
                                            >
                                                <img
                                                    :src="
                                                        ATCRatings[
                                                            user.atc_rating
                                                        ].imageUrl
                                                    "
                                                    :alt="
                                                        ATCRatings[
                                                            user.atc_rating
                                                        ].label
                                                    "
                                                    class="h-5 w-auto object-contain"
                                                />
                                                {{
                                                    ATCRatings[user.atc_rating]
                                                        .key
                                                }}
                                            </span>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            {{
                                                ATCRatings[user.atc_rating]
                                                    .label
                                            }}
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                <span
                                    v-if="user.atc_hours !== null"
                                    class="ml-1 text-muted-foreground"
                                    >{{ formatHours(user.atc_hours) }}</span
                                >
                            </div>
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell class="text-sm">
                            <div
                                v-if="user.pilot_rating !== null"
                                class="flex flex-wrap items-center"
                            >
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger
                                            as="div"
                                            class="flex items-center"
                                        >
                                            <span
                                                class="inline-flex items-center gap-1.5 font-medium"
                                            >
                                                <img
                                                    :src="
                                                        PilotRatings[
                                                            user.pilot_rating
                                                        ].imageUrl
                                                    "
                                                    :alt="
                                                        PilotRatings[
                                                            user.pilot_rating
                                                        ].label
                                                    "
                                                    class="h-5 w-auto object-contain"
                                                />
                                                {{
                                                    PilotRatings[
                                                        user.pilot_rating
                                                    ].key
                                                }}
                                            </span>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            {{
                                                PilotRatings[user.pilot_rating]
                                                    .label
                                            }}
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                <span
                                    v-if="user.pilot_hours !== null"
                                    class="ml-1 text-muted-foreground"
                                    >{{ formatHours(user.pilot_hours) }}</span
                                >
                            </div>
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell v-if="canManageRoles" class="text-right">
                            <Button
                                variant="ghost"
                                size="icon"
                                :title="$t('Manage roles')"
                                @click="editingUser = user"
                            >
                                <Pencil class="size-4" />
                                <span class="sr-only">{{
                                    $t('Manage roles')
                                }}</span>
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div
            v-if="users.last_page > 1"
            class="flex items-center justify-center gap-1"
        >
            <Button
                variant="outline"
                size="icon"
                :disabled="!users.prev_page_url"
                @click="users.prev_page_url && router.get(users.prev_page_url)"
            >
                <ChevronLeft class="size-4" />
            </Button>
            <Button
                v-for="link in links"
                :key="link.label"
                :variant="link.active ? 'default' : 'outline'"
                size="sm"
                :disabled="!link.url"
                @click="link.url && router.get(link.url)"
            >
                {{ link.label }}
            </Button>
            <Button
                variant="outline"
                size="icon"
                :disabled="!users.next_page_url"
                @click="users.next_page_url && router.get(users.next_page_url)"
            >
                <ChevronRight class="size-4" />
            </Button>
        </div>

        <ManageUserRolesDialog
            v-if="canManageRoles"
            :user="editingUser"
            @update:open="
                (open) => {
                    if (!open) {
                        editingUser = null;
                    }
                }
            "
        />
    </div>
</template>

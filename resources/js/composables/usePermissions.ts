import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { Auth } from '@/types';

export type UsePermissionsReturn = {
    hasPermission: (permission: string | string[], all?: boolean) => boolean;
};

export function usePermissions(): UsePermissionsReturn {
    const pageProps = usePage().props.auth as Auth;

    const permissions = computed<string[]>(() => pageProps.permissions);
    function hasPermission(
        permission: string | string[],
        all: boolean = true,
    ): boolean {
        if (Array.isArray(permission)) {
            return all
                ? permission.every((perm) => permissions.value.includes(perm))
                : permission.some((perm) => permissions.value.includes(perm));
        }

        return permissions.value.includes(permission);
    }

    return { hasPermission };
}

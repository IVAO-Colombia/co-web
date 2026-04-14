import { router, usePage } from '@inertiajs/vue3';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import type { ComputedRef } from 'vue';
import { computed } from 'vue';
import type { Locale } from '@/types';

export type UseLocaleReturn = {
    locale: ComputedRef<Locale>;
    updateLocale: (value: Locale) => void;
};

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function useLocale(): UseLocaleReturn {
    const page = usePage();

    const locale = computed<Locale>(() => page.props.locale as Locale);

    function updateLocale(value: Locale): void {
        setCookie('locale', value);
        router.reload({ only: ['locale'] });
        loadLanguageAsync(value);
    }

    return { locale, updateLocale };
}

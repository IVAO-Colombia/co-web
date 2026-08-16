import { createInertiaApp } from '@inertiajs/vue3';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import { createSSRApp, Fragment, h } from 'vue';
import CookieConsentBanner from '@/components/CookieConsentBanner.vue';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import LandingLayout from './layouts/LandingLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return LandingLayout;
            case name.includes('landing/'):
                return LandingLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
    setup({ el, App, props, plugin }) {
        if (!el) {
            return;
        }

        const app = createSSRApp({
            render: () => h(Fragment, [h(App, props), h(CookieConsentBanner)]),
        })
            .use(plugin)
            .use(i18nVue, {
                lang: props.initialPage.props.locale,
                resolve: async (lang: string) => {
                    const langs = import.meta.glob('../../lang/*.json');

                    return await langs[`../../lang/${lang}.json`]();
                },
            });

        loadLanguageAsync(props.initialPage.props.locale).then(() => {
            app.mount(el);
        });
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();

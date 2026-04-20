import { createInertiaApp } from '@inertiajs/vue3';
import { i18nVue, loadLanguageAsync } from 'laravel-vue-i18n';
import { createApp, createSSRApp, h } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
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
        const hasServerMarkup = !!el?.firstElementChild;
        const app = hasServerMarkup
            ? createSSRApp({ render: () => h(App, props) })
            : createApp({ render: () => h(App, props) });

        app.use(plugin).use(i18nVue, {
            lang: props.initialPage.props.locale,
            resolve: async (lang: string) => {
                const langs = import.meta.glob('../../lang/*.json');
                const loader = langs[`../../lang/${lang}.json`];

                if (!loader) {
                    return {};
                }

                return await loader();
            },
        });

        void loadLanguageAsync(props.initialPage.props.locale)
            .catch(() => undefined)
            .finally(() => {
                app.mount(el!);
            });
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();

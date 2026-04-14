import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { i18nVue } from 'laravel-vue-i18n';
import type { DefineComponent } from 'vue';
import { createSSRApp, h } from 'vue';
import { renderToString } from 'vue/server-renderer';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'IVAO CO';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
            ),
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
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(i18nVue, {
                    /* use correct language server-side */
                    lang: page.props.locale,
                    resolve: (lang: string) => {
                        const langs: Record<string, { default: any }> =
                            import.meta.glob('../../lang/*.json', {
                                eager: true,
                            });

                        return langs[`../../lang/${lang}.json`].default;
                    },
                });
        },
    }),
);

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();

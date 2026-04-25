import '../css/app.css';
import './echo'; // Import file echo.ts saja, jangan buat konfigurasi baru di sini

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Lokak Begawe';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const page = resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        );

        page.then((module) => {
            if (module.default.layout === undefined) {
                const n = name.toLowerCase();
                const isLandingPage =
                    [
                        'welcome',
                        'lowongan',
                        'lowongan/lowongan',
                        'mitra/carimitra',
                    ].includes(n) ||
                    n.startsWith('lowongan/') ||
                    n.startsWith('mitra/');

                if (isLandingPage) {
                    module.default.layout = null;
                } else if (n.startsWith('auth/')) {
                    module.default.layout = AuthLayout;
                } else if (n.startsWith('settings/')) {
                    module.default.layout = [AppLayout, SettingsLayout];
                } else {
                    module.default.layout = AppLayout;
                }
            }
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: { color: '#5B7C88' },
});

if (typeof window !== 'undefined') {
    initializeTheme();
    initializeFlashToast();
}

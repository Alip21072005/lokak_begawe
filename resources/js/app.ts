import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Lokak Begawe';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    // Resolve menggunakan folder 'pages' (huruf kecil) sesuai strukturmu
    resolve: (name) => {
        const page = resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        );
        
        page.then((module) => {
            // Logika Layout Switching kamu
            if (module.default.layout === undefined) {
                if (name === 'Welcome') {
                    module.default.layout = null; // Landing page tanpa layout utama
                } else if (name.startsWith('auth/')) {
                    module.default.layout = AuthLayout;
                } else if (name.startsWith('settings/')) {
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
            .mount(el);
    },
    progress: {
        color: '#5B7C88', // Warna biru sesuai tema landing page
    },
});

// Inisialisasi fitur bawaan proyekmu
initializeTheme();
initializeFlashToast();
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
    resolve: (name) => {
        const page = resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        );
        
page.then((module) => {
    if (module.default.layout === undefined) {
        const n = name.toLowerCase();
        
        //
if (name === 'Welcome' || name === 'Lowongan' || name === 'Lowongan/Lowongan' || name === 'Mitra/Carimitra') {
    console.log('Halaman Aktif:', name);
} ///

        const isLandingPage = 
            n === 'welcome' || 
            n === 'lowongan' || 
            n === 'lowongan/lowongan' ||
            n === 'mitra/carimitra' ||  // Nama file kamu: pages/Mitra/Carimitra.vue
            n.startsWith('lowongan/') || 
            n.startsWith('mitra/');

        if (isLandingPage) {
            module.default.layout = null; 
        } else if (n.startsWith('auth/')) {
            module.default.layout = AuthLayout;
        } else if (n.startsWith('settings/')) {
            module.default.layout = [AppLayout, SettingsLayout];
        } else {
            // Jika masih membandel ada sidebar, pastikan ini null
            module.default.layout = null; 
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
        color: '#5B7C88',
    },
});

// Tetap aman dari error 'window is not defined'
if (typeof window !== 'undefined') {
    initializeTheme();
    initializeFlashToast();
}
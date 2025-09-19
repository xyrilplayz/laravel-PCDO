import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
// Common Imports
import { Head, Link, Form } from '@inertiajs/vue3';
// Common Components
import Button from './components/ui/button/Button.vue';
import Input from './components/ui/input/Input.vue';
// Common Library Components 
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { CheckCircle, XCircle, CircleDashed, Search } from 'lucide-vue-next';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import Toast, { PluginOptions } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Unknown ENV';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                position: 'top-right',
                timeout: 3000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            } as PluginOptions)

            .component('Head', Head)
            .component('Link', Link)
            .component('Toast', Toast)
            .component('Button', Button)
            .component('Input', Input)
            .component('Form', Form)
            .component('Table', Table)
            .component('TableBody', TableBody)
            .component('TableCell', TableCell)
            .component('TableHead', TableHead)
            .component('TableHeader', TableHeader)
            .component('TableRow', TableRow)

            .component('CheckCircle', CheckCircle)
            .component('XCircle', XCircle)
            .component('CircleDashed', CircleDashed)
            .component('Search', Search)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

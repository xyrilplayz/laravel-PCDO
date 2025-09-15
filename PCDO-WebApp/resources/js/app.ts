import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import Toast, { PluginOptions } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import { 
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
    Dialog,
    DialogTitle,
    DialogPanel,
    DialogOverlay,
    DialogDescription, 
    SwitchDescription, 
    RadioGroupDescription,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    RadioGroup,
    RadioGroupOption,
    RadioGroupLabel,
    Switch,
    SwitchGroup,
    TransitionRoot
} from '@headlessui/vue';

import Button from './components/ui/button/Button.vue';
import Input from './components/ui/input/Input.vue';

import { CheckCircle, XCircle, CircleDashed, Search } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from 'reka-ui';

import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Unknown ENV';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .component('Datepicker', VueDatePicker)
            .component('Toast', Toast)
            .component('Transition', TransitionRoot)
            .component('Button', Button)
            .component('Input', Input)
            .component('Combobox', Combobox)
            .component('ComboboxButton', ComboboxButton)
            .component('ComboboxInput', ComboboxInput)
            .component('ComboboxLabel', ComboboxLabel)
            .component('ComboboxOption', ComboboxOption)
            .component('ComboboxOptions', ComboboxOptions)
            .component('Dialog', Dialog)
            .component('DialogTitle', DialogTitle)
            .component('DialogPanel', DialogPanel)
            .component('DialogOverlay', DialogOverlay)
            .component('DialogDescription', DialogDescription)
            .component('SwitchDescription', SwitchDescription)
            .component('RadioGroupDescription', RadioGroupDescription)
            .component('Menu', Menu)
            .component('MenuButton', MenuButton)
            .component('MenuItem', MenuItem)
            .component('MenuItems', MenuItems)
            .component('RadioGroup', RadioGroup)
            .component('RadioGroupOption', RadioGroupOption)
            .component('RadioGroupLabel', RadioGroupLabel)
            .component('Switch', Switch)
            .component('SwitchGroup', SwitchGroup)
            .component('CheckCircle', CheckCircle)
            .component('XCircle', XCircle)
            .component('CircleDashed', CircleDashed)
            .component('Search', Search)
            .component('Table', Table)
            .component('TableBody', TableBody)
            .component('TableCell', TableCell)
            .component('TableHead', TableHead)
            .component('TableHeader', TableHeader)
            .component('TableRow', TableRow)
            .component('DropdownMenuRoot', DropdownMenuRoot)
            .component('DropdownMenuTrigger', DropdownMenuTrigger)
            .component('DropdownMenuContent', DropdownMenuContent)
            .component('DropdownMenuItem', DropdownMenuItem)
            .component('useForm', useForm)
            .component('ref', ref)
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
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

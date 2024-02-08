    import './bootstrap';
    import '../css/app.css';
    import '../css/custom.css';

    import { createApp, h } from 'vue';
    import { createInertiaApp } from '@inertiajs/vue3';
    import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
    import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
    import GlobalSpinnerPlugin from './spinner.js'
    const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
    import VueDatePicker from '@vuepic/vue-datepicker';
    // import DataTable from 'laravel-vue-datatable';
    import countryList  from './countryList.js';
    // import vueSignature from "vue-signature";
    // import VueSignature from "vue-signature-pad";
    import { VueSignaturePad } from 'vue-signature-pad';
    import '@vuepic/vue-datepicker/dist/main.css'
    import VueCreditCardValidation from 'vue-credit-card-validation';
    import moment from 'moment-timezone'
    import VueLoader from "@/Components/VueLoader.vue";
    import 'vue-loaders/dist/vue-loaders.css';
    import VueLoaders from 'vue-loaders';
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ el, App, props, plugin }) {
            const app =  createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, Ziggy)
                .use(GlobalSpinnerPlugin)
                .use(VueCreditCardValidation)
                .use(VueLoaders)
                // .use(DataTable)
                .provide('countryList', countryList)
                // .use(VueSignature)
                // .component('VueSignaturePad', VueSignature)
                .component('VueDatePicker', VueDatePicker)
                .component("VueSignaturePad", VueSignaturePad)
                .component('VueLoader', VueLoader)
                // .mount(el);

            app.mount(el)
        },
        progress: {
            color: '#4B5563',
        },
    });
    moment.tz.setDefault('Asia/Kolkata')

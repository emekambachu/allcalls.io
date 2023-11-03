    import './bootstrap';
    import '../css/app.css';
  
    import { createApp, h } from 'vue';
    import { createInertiaApp } from '@inertiajs/vue3';
    import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
    import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
    import GlobalSpinnerPlugin from './spinner.js'
    const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
    import VueDatePicker from '@vuepic/vue-datepicker';
    import countryList  from './countryList.js';
    // import vueSignature from "vue-signature";
    // import VueSignature from "vue-signature-pad";
    import { VueSignaturePad } from 'vue-signature-pad';
    import '@vuepic/vue-datepicker/dist/main.css'
    import VueCreditCardValidation from 'vue-credit-card-validation';
    import moment from 'moment-timezone'
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ el, App, props, plugin }) {
            return createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, Ziggy)
                .use(GlobalSpinnerPlugin)
                .use(VueCreditCardValidation)
                .provide('countryList', countryList)
                // .use(VueSignature)
                // .component('VueSignaturePad', VueSignature)
                .component('VueDatePicker', VueDatePicker)
                .component("VueSignaturePad", VueSignaturePad)
                .mount(el);
        },
        progress: {
            color: '#4B5563',
        },
    });
    moment.tz.setDefault('Asia/Kolkata')
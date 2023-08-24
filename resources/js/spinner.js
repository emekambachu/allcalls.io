import { createApp } from 'vue';
import GlobalSpinner from './GlobalSpinner.vue';

const GlobalSpinnerPlugin = {
    install(app) {
      app.component('GlobalSpinner', GlobalSpinner);
    },
  };
  
  export default GlobalSpinnerPlugin;
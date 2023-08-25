import { createApp } from 'vue';
import GlobalSpinner from './GlobalSpinner.vue';
import AnimationSlider from './AnimationSlider.vue'
const GlobalSpinnerPlugin = {
  install(app) {
    app.component('GlobalSpinner', GlobalSpinner);
    app.component('AnimationSlider', AnimationSlider);
  },
};

export default GlobalSpinnerPlugin;
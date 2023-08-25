import { createApp } from 'vue';
import GlobalSpinner from './Components/GlobalSpinner.vue';
import AnimationSlider from './Components/AnimationSlider.vue'
const GlobalSpinnerPlugin = {
  install(app) {
    app.component('GlobalSpinner', GlobalSpinner);
    app.component('AnimationSlider', AnimationSlider);
  },
};

export default GlobalSpinnerPlugin;
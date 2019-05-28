import Vue from 'vue';
import VueFlashMessage from 'vue-flash-message';

Vue.use(VueFlashMessage, {
  method: '$message',
  important: false,
  timout: 2000
});

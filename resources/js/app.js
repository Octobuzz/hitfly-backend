import Vue from 'vue';
import VTooltip from 'v-tooltip';
import VueFlashMessage from 'vue-flash-message';
import VueWindowSize from 'vue-window-size';
import router from './router';
import store from './store';
import apolloProvider from './apolloProvider';
import App from './pages/App.vue';
import './bootstrap';

Vue.use(VTooltip);
Vue.use(VueFlashMessage, {
  method: '$message'
});
Vue.use(VueWindowSize, {
  delay: 0
});

Vue.config.productionTip = false;

try {
  new Vue({
    router,
    store,
    apolloProvider,
    render: h => h(App)
  }).$mount('#app');
} catch (err) {
  console.log(err);
}

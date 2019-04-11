import Vue from 'vue';
import VTooltip from 'v-tooltip';
import router from './router';
import store from './store';
import apolloProvider from './apolloProvider';
import App from './pages/App.vue';
import './bootstrap';

Vue.use(VTooltip);

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


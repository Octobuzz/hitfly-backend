import Vue from 'vue';
import PortalVue from 'portal-vue';
import VTooltip from 'v-tooltip';
import VueFlashMessage from 'vue-flash-message';
import VueWindowSize from 'vue-window-size';
import EventBus from 'modules/eventBus';
import WsTunnel from 'modules/ws';
import router from './router';
import store from './store';
import apolloProvider from './apolloProvider';
import AppLayout from './components/layout/AppLayout.vue';
import './bootstrap';

Vue.use(EventBus);
Vue.use(WsTunnel);
Vue.use(PortalVue);
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
    render: h => h(AppLayout)
  }).$mount('#app');
} catch (err) {
  console.log(err);
}

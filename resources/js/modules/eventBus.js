export default {
  install(Vue) {
    // eslint-disable-next-line no-param-reassign
    Vue.prototype.$eventBus = new Vue();
  }
};

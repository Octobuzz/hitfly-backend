import Vue from 'vue';

const VueFlashMessageMock = {
  install(VueGlobal) {
    // eslint-disable-next-line
    VueGlobal.prototype.$message = function flashMessageMock(options) {};
  }
};

Vue.use(VueFlashMessageMock);

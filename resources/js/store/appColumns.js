/* eslint-disable no-param-reassign, no-shadow */

const state = {
  paddingClass: null,
  is404: false
};

const getters = {
  paddingClass({ paddingClass }) {
    return paddingClass;
  },
  is404({ is404 }) {
    return is404;
  }
};

const mutations = {
  setPaddingClass(state, paddingClassName) {
    state.paddingClass = paddingClassName;
  },
  set404(state, isHidden) {
    state.is404 = isHidden;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

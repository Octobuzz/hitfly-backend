/* eslint-disable no-param-reassign, no-shadow */

const state = {
  paddingClass: null
};

const getters = {
  paddingClass({ paddingClass }) {
    return paddingClass;
  }
};

const mutations = {
  setPaddingClass(state, paddingClassName) {
    state.paddingClass = paddingClassName;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

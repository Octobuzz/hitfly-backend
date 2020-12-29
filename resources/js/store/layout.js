/* eslint-disable no-param-reassign, no-shadow */

const state = {
  blurred: false,
  whitened: false
};

const getters = {
  blurred({ blurred }) {
    return blurred;
  },

  whitened({ whitened }) {
    return whitened;
  }
};

const mutations = {
  blur(state, flag) {
    state.blurred = flag;
  },

  whiten(state, flag) {
    state.whitened = flag;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

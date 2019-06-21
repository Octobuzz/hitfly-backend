/* eslint-disable no-param-reassign, no-shadow */

const state = {
  blurred: false
};

const getters = {
  blurred({ blurred }) {
    return blurred;
  }
};

const mutations = {
  blur(state, flag) {
    state.blurred = flag;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

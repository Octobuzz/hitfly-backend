/* eslint-disable no-param-reassign, no-shadow */

const state = {
  history: []
};

const getters = {
  history({ history }) {
    return history;
  }
};

const mutations = {
  push({ history }, { fullPath }) {
    history.push(fullPath);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

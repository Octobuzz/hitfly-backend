/* eslint-disable no-param-reassign, no-shadow */

import Vue from 'vue';
import Vuex, { Store } from 'vuex';
import player from './player';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const state = {};

const getters = {
  editGroupId(state) {
    return state.editGroupId;
  }
};

const mutations = {
  setEditGroupId(state, id) {
    state.editGroupId = id;
  }
};

export default new Store({
  modules: {
    player
  },
  state,
  getters,
  mutations,
  strict: debug,
  devtools: debug
});

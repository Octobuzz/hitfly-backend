/* eslint-disable no-param-reassign, no-shadow */

import Vue from 'vue';
import Vuex, { Store } from 'vuex';
import history from './history';
import appColumns from './appColumns';
import loading from './loading';
import player from './player';
import profile from './profile';
import links from './links';
import layout from './layout';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const state = {
  isAuthenticated: false,
  favInProcess: {
    track: [],
    album: [],
    collection: []
  },
  editGroupId: null
};

const getters = {
  isAuthenticated() {
    // TODO: implement authentication
    return true;
  },

  apolloClient(_, getters) {
    return getters.isAuthenticated ? 'private' : 'public';
  },

  favInProcess({ favInProcess }) {
    return ({ type, id }) => (
      favInProcess[type]
        .some(idInProcess => idInProcess === id)
    );
  }
};

const mutations = {
  addFavInProcess({ favInProcess }, { type, id }) {
    favInProcess[type].push(id);
  },

  removeFavInProcess({ favInProcess }, { type, id }) {
    favInProcess[type] = favInProcess[type].filter(
      idInStore => idInStore !== id
    );
  }
};

export default new Store({
  modules: {
    history,
    appColumns,
    loading,
    player,
    profile,
    links,
    layout
  },
  state,
  getters,
  mutations,
  strict: debug,
  devtools: debug
});

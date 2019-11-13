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

// TODO: consider including all header/footer data
// inject auth and profile data into stores

const authuserMeta = document.head.querySelector('meta[authuser]');
const myId = parseInt(authuserMeta.getAttribute('authuser'), 10);
const isAuthenticated = !Number.isNaN(myId);

const state = {
  isAuthenticated,
  favInProcess: {
    track: [],
    album: [],
    collection: []
  },
  isFavouriteRemoveOptionHidden: false,
  editGroupId: null,
  selectedLifeHacksCategoryId: null
};

const getters = {
  isAuthenticated({ isAuthenticated }) {
    return isAuthenticated;
  },

  apolloClient(_, getters) {
    return getters.isAuthenticated ? 'private' : 'public';
  },

  favInProcess({ favInProcess }) {
    return ({ type, id }) => (
      favInProcess[type]
        .some(idInProcess => idInProcess === id)
    );
  },

  isFavouriteRemoveOptionHidden(state, getters) {
    const history = getters['history/history'];
    const currentRoute = history[history.length - 1];

    return currentRoute.split('/').slice(1, 3)
      .join('-') === 'profile-favourite';
  },

  selectedLifeHacksCategoryId({ selectedLifeHacksCategoryId }) {
    return selectedLifeHacksCategoryId;
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
  },

  setSelectedLifeHacksCategoryId(state, id) {
    state.selectedLifeHacksCategoryId = id;
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

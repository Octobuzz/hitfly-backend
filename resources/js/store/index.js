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
import { cache } from '../apolloProvider';
import MY_PROFILE from './MyProfile.graphql';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

// TODO: consider including all header/footer data
// inject auth and profile data into stores

const authuserMeta = document.head.querySelector('meta[authuser]');
const myId = parseInt(authuserMeta.getAttribute('authuser'), 10);
const isAuthenticated = !Number.isNaN(myId);

if (!Number.isNaN(myId)) {
  const rolesMeta = document.head.querySelector('meta[roles]');
  const roles = JSON.parse(rolesMeta.getAttribute('roles'));
  const roleSlugs = roles.map(role => role.slug);

  cache.writeData({
    query: MY_PROFILE,
    data: {
      myProfile: {
        __typename: 'MyProfile',
        roles: roleSlugs.map(slug => ({
          __typename: 'Role',
          slug
        }))
      }
    }
  });
}

const state = {
  isAuthenticated,
  favInProcess: {
    track: [],
    album: [],
    collection: []
  },
  editGroupId: null
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

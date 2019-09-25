/* eslint-disable no-param-reassign, no-shadow */

import MY_ID_AND_ROLES from 'gql/query/MyId.graphql';
import { cache } from '../apolloProvider';

const authuserMeta = document.head.querySelector('meta[authuser]');
const myId = parseInt(authuserMeta.getAttribute('authuser'), 10);
let roleSlugs = [];

if (!Number.isNaN(myId)) {
  const rolesMeta = document.head.querySelector('meta[roles]');
  const roles = JSON.parse(rolesMeta.getAttribute('roles'));

  roleSlugs = roles.map(role => role.slug);

  cache.writeData({
    query: MY_ID_AND_ROLES,
    data: {
      myProfile: {
        __typename: 'MyProfile',
        id: myId,
        roles: roleSlugs.map(slug => ({
          __typename: 'Role',
          slug
        }))
      }
    }
  });
}

const state = {
  myId,
  roles: roleSlugs
};

const getters = {
  myId({ myId }) {
    return myId;
  },

  roles({ roles }) {
    function getter(roleInQuestion) {
      return roles.some(role => role === roleInQuestion);
    }

    getter.toString = () => roles;

    return getter;
  },

  ableToComment(state, { roles }) {
    return ['critic', 'prof_critic', 'star']
      .some(role => roles(role));
  },

  ableToPerform(state, { roles }) {
    if (['prof_critic', 'star'].some(role => roles(role))) {
      return false;
    }

    return ['listener', 'performer', 'critic']
      .some(role => roles(role));
  }
};

const mutations = {
  setMyId(state, id) {
    state.myId = id;
  },

  setRoles(state, roles) {
    state.roles = [...roles];
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

/* eslint-disable no-param-reassign, no-shadow */

const state = {
  loggedIn: null,
  myId: null,
  roles: []
};

const getters = {
  loggedIn({ loggedIn }) {
    return loggedIn;
  },

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
    return ['prof_critic', 'critic', 'star']
      .some(role => roles(role));
  },

  ableToPerform(state, { roles }) {
    return ['listener', 'performer', 'prof_critic']
      .some(role => roles(role));
  }
};

const mutations = {
  setLoggedIn(state, flag) {
    state.loggedIn = flag;
  },

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

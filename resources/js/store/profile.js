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

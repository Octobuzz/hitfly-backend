/* eslint-disable no-param-reassign, no-shadow */

const state = {
  myId: null,
  roles: []
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

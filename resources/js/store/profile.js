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

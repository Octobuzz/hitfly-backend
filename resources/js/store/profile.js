/* eslint-disable no-param-reassign, no-shadow */

const state = {
  // TODO: use request in header to populate on all pages
  roles: ['star']
};

const getters = {
  roles({ roles }) {
    function getter(roleInQuestion) {
      return roles.some(role => role === roleInQuestion);
    }

    getter.toString = () => roles;

    return getter;
  }
};

const mutations = {
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

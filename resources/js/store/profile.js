/* eslint-disable no-param-reassign, no-shadow */

const state = {
  editGroupId: null
};

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

const actions = {};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};

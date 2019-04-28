/* eslint-disable no-param-reassign, no-shadow */

const state = {
  editGroupId: null,
  editGroupIdHistory: []
};

const getters = {
  editGroupId(state) {
    return state.editGroupId;
  },

  editGroupIdHistory(state) {
    return state.editGroupIdHistory;
  }
};

const mutations = {
  setEditGroupId(state, { id, dontAffectHistory = false }) {
    state.editGroupId = id;

    if (id !== null && !dontAffectHistory) {
      state.editGroupIdHistory.push(id);
    }
  },

  popEditGroupId(state) {
    state.editGroupIdHistory.pop();
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

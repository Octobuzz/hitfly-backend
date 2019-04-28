/* eslint-disable no-param-reassign, no-shadow */

const state = {
  editGroupId: null,
  editGroupIdHistory: [],
  customRedirect: false
};

const getters = {
  editGroupId({ editGroupId }) {
    return editGroupId;
  },

  editGroupIdHistory({ editGroupIdHistory }) {
    return editGroupIdHistory;
  },

  customRedirect({ customRedirect }) {
    return customRedirect;
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
  },

  flushEditGroupIdHistory(state) {
    state.editGroupIdHistory = [];
  },

  setCustomRedirect(state, val) {
    state.customRedirect = val;
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

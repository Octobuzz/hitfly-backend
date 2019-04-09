/* eslint-disable no-param-reassign, no-shadow */

const state = {
  currentTrack: null,
  isPlaying: false
};

const getters = {
  isPlaying(state) {
    return state.isPlaying;
  },
  currentTrack(state) {
    return state.currentTrack;
  }
};

const mutations = {
  startPlaying(state, trackId) {
    state.isPlaying = true;
    state.currentTrack = trackId;
  },

  pausePlaying(state) {
    state.isPlaying = false;
  },

  stopPlaying(state) {
    state.isPlaying = false;
    state.currentTrack = null;
  }
};

const actions = {
  play({ commit }, trackId) {
    commit('startPlaying', trackId);

    console.log(`start track, id: ${trackId}`);
  },

  pause({ commit }) {
    commit('pausePlaying');

    console.log('pause track');
  },

  stop({ commit }) {
    commit('stopPlaying');

    console.log('stop track');
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};

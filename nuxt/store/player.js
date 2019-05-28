/* eslint-disable no-param-reassign, no-shadow */

export const state = () => ({
  currentTrack: null,
  isPlaying: false
});

export const getters = {
  isPlaying(state) {
    return state.isPlaying;
  },
  currentTrack(state) {
    return state.currentTrack;
  }
};

export const mutations = {
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

export const actions = {
  play({ commit }, trackId) {
    commit('startPlaying', trackId);
  },

  pause({ commit }) {
    commit('pausePlaying');
  },

  stop({ commit }) {
    commit('stopPlaying');
  }
};

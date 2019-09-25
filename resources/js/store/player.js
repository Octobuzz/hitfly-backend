/* eslint-disable no-param-reassign, no-shadow */

const state = {
  currentTrackId: null,
  currentTrack: {},
  isPlaying: false,
  currentPlaylist: [],
  currentType: {
    'type': String,
    'id': Number
  }
};

const getters = {
  isPlaying(state) {
    return state.isPlaying;
  },
  currentTrack(state) {
    return state.currentTrack;
  },
  currentPlaylist(state) {
    return state.currentPlaylist;
  },
  getCurrentType(state) {
    return state.currentType;
  }
};

const mutations = {
  startPlaying(state) {
    state.isPlaying = true;
  },

  pickTrack(state, track){
    state.currentTrack = track;
    state.isPlaying = true;
  },

  pausePlaying(state) {
    state.isPlaying = false;
  },

  togglePlaying(state) {
    state.isPlaying = !state.isPlaying;
  },

  stopPlaying(state) {
    state.isPlaying = false;
    state.currentTrack = {};
  },

  pickPlaylist(state, playlist) {
    state.currentPlaylist = playlist;
  },

  changeCurrentType(state, data) {
    state.currentType = data;
  }
};

const actions = {
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

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};

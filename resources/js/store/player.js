/* eslint-disable no-param-reassign, no-shadow */

import gql from 'graphql-tag';
import apolloProvider from '../apolloProvider';

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
  },

  setRandomTrack({ commit }) {
    apolloProvider.clients.public.query({
      fetchPolicy: 'network-only',
      query: gql`query player_tracks {
          tracks(page: 1, limit: 1) {
              data {
                  id
                  filename
                  singer
                  trackName
                  length
                  userFavourite
                  favouritesCount
                  cover(
                      sizes: [size_32x32, size_48x48, size_104x104, size_120x120, size_150x150]
                  ) {
                      size
                      url
                  }
              }
          }
      }`
    }).then(({ data: { tracks: { data } } }) => {
      const [track] = data;

      if (!track) return;

      commit('changeCurrentType', {
        type: 'track',
        id: track.id
      });
      commit('pickTrack', track);
      commit('pausePlaying');
    });
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};

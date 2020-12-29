/* eslint-disable no-param-reassign, no-shadow */

const stateFactory = () => ({
  initialized: false,
  success: false,
  errorMessage: null
});

const state = {
  userCard: {
    personalInfo: '',
    watchedUsers: '',
    watchedGroups: ''
  },
  editProfile: '',
  editGroup: '',
  music: {
    tracks: '',
    albums: '',
    collections: '',
    newAlbum: ''
  },
  mainPage: {
    news: '',
    recommended: '',
    genres: '',
    newTracks: '',
    superMelomaniac: '',
    weeklyTop: '',
    stars: '',
    sidebarStars: ''
  },
  favourite: {
    tracks: '',
    albums: '',
    collections: '',
    sets: ''
  },
  reviews: '',
  review: '',
  tracks: '',
  albums: '',
  album: '',
  playlists: '',
  playlist: '',
  sets: '',
  set: ''
};

function populateState(obj) {
  Object.keys(obj).forEach((key) => {
    if (typeof obj[key] !== 'object') {
      obj[key] = stateFactory();
    } else {
      populateState(obj[key]);
    }
  });
}

populateState(state);

const getters = {
  userCard({ userCard }) {
    return userCard;
  },

  editProfile({ editProfile }) {
    return editProfile;
  },

  editGroup({ editGroup }) {
    return editGroup;
  },

  music({ music }) {
    return music;
  },

  favourite({ favourite }) {
    return favourite;
  },

  mainPage({ mainPage }) {
    return mainPage;
  },

  reviews({ reviews }) {
    return reviews;
  },

  review({ review }) {
    return review;
  },

  tracks({ tracks }) {
    return tracks;
  },

  albums({ albums }) {
    return albums;
  },

  album({ album }) {
    return album;
  },

  playlists({ playlists }) {
    return playlists;
  },

  playlist({ playlist }) {
    return playlist;
  },

  sets({ sets }) {
    return sets;
  },

  set({ set }) {
    return set;
  }
};

const mutations = {
  setUserCard({ userCard }, loading) {
    Object.keys(loading).forEach((key) => {
      userCard[key] = {
        ...userCard[key],
        ...loading[key]
      };
    });
  },

  setEditProfile(state, loading) {
    state.editProfile = {
      ...state.editProfile,
      ...loading
    };
  },

  setEditGroup(state, loading) {
    state.editGroup = {
      ...state.editGroup,
      ...loading
    };
  },

  setMusic({ music }, loading) {
    Object.keys(loading).forEach((key) => {
      music[key] = {
        ...music[key],
        ...loading[key]
      };
    });
  },

  setMainPage({ mainPage }, loading) {
    Object.keys(loading).forEach((key) => {
      mainPage[key] = {
        ...mainPage[key],
        ...loading[key]
      };
    });
  },

  setFavourite({ favourite }, loading) {
    Object.keys(loading).forEach((key) => {
      favourite[key] = {
        ...favourite[key],
        ...loading[key]
      };
    });
  },

  setReviews(state, loading) {
    state.reviews = {
      ...state.reviews,
      ...loading
    };
  },

  setReview(state, loading) {
    state.review = {
      ...state.review,
      ...loading
    };
  },

  setTracks(state, loading) {
    state.tracks = {
      ...state.tracks,
      ...loading
    };
  },

  setAlbums(state, loading) {
    state.albums = {
      ...state.albums,
      ...loading
    };
  },

  setAlbum(state, loading) {
    state.Album = {
      ...state.Album,
      ...loading
    };
  },

  setPlaylists(state, loading) {
    state.playlists = {
      ...state.playlists,
      ...loading
    };
  },

  setPlaylist(state, loading) {
    state.playlist = {
      ...state.playlist,
      ...loading
    };
  },

  setSets(state, loading) {
    state.sets = {
      ...state.sets,
      ...loading
    };
  },

  setSet(state, loading) {
    state.set = {
      ...state.set,
      ...loading
    };
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

/* eslint-disable no-param-reassign, no-shadow */

const stateFactory = () => ({
  initialized: false,
  success: false,
  errorMessage: null
});

const state = {
  editProfile: '',
  editGroup: '',
  favourite: {
    tracks: '',
    albums: '',
    collections: '',
    sets: ''
  },

  myUserCard: '',
  myMusic: {
    tracks: '',
    albums: '',
    collections: ''
  },
  myReviews: '',

  otherUserMusic: {
    tracks: '',
    albums: '',
    collections: '',
    newAlbum: ''
  },
  otherUserReviews: ''
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
  myUserCard({ myUserCard }) {
    return myUserCard;
  },

  editProfile({ editProfile }) {
    return editProfile;
  },

  editGroup({ editGroup }) {
    return editGroup;
  },

  myMusic({ myMusic }) {
    return myMusic;
  },

  otherUserMusic({ otherUserMusic }) {
    return otherUserMusic;
  },

  favourite({ favourite }) {
    return favourite;
  },

  myReviews({ myReviews }) {
    return myReviews;
  },

  otherUserReviews({ otherUserReviews }) {
    return otherUserReviews;
  }
};

const mutations = {
  setMyUserCard(state, loading) {
    state.myUserCard = {
      ...state.myUserCard,
      ...loading
    };
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

  setMyMusic({ myMusic }, loading) {
    Object.keys(loading).forEach((key) => {
      myMusic[key] = {
        ...myMusic[key],
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

  setMyReviews(state, loading) {
    state.myReviews = {
      ...state.myReviews,
      ...loading
    };
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
};

/* eslint-disable no-param-reassign, no-shadow */

const stateFactory = () => ({
  initialized: false,
  success: false,
  errorMessage: null
});

const state = {
  userCard: '',
  editProfile: '',
  editGroup: '',
  myMusic: {
    tracks: '',
    albums: '',
    collections: ''
  },
  favourite: {
    tracks: '',
    albums: '',
    collections: '',
    sets: ''
  },
  myReviews: ''
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

  myMusic({ myMusic }) {
    return myMusic;
  },

  favourite({ favourite }) {
    return favourite;
  },

  myReviews({ myReviews }) {
    return myReviews;
  }
};

const mutations = {
  setUserCard(state, loading) {
    state.userCard = {
      ...state.userCard,
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

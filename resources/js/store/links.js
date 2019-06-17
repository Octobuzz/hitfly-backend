const state = {
  profile: {
    album: '/profile/album/',
    playlist: '/profile/playlist/',
    set: '/profile/set/'
  },
  // TODO: other user/group links
};

/* eslint-disable no-shadow */

const getters = {
  album(state) {
    return (route, id) => state[
      route.split('/')[1]
    ].album + id;
  },

  playlist() {
    return (route, id) => state[
      route.split('/')[1]
    ].playlist + id;
  },

  set() {
    return (route, id) => state[
      route.split('/')[1]
    ].set + id;
  }
};

/* eslint-enable no-shadow */

export default {
  namespaced: true,
  state,
  getters
};

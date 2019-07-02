const state = {};

/* eslint-disable no-shadow */

const getters = {
  album(_, getters) {
    return getters.universal('album');
  },

  playlist(_, getters) {
    return getters.universal('playlist');
  },

  set(_, getters) {
    return getters.universal('set');
  },

  universal() {
    return type => ({ fullPath, params }, id) => {
      const pathPrefix = fullPath.split('/')[1];

      // TODO: music-group route

      switch (pathPrefix) {
        case 'profile':
          return `/profile/${type}/${id}`;

        case 'user':
          return `/user/${params.userId}/${type}/${id}`;

        default:
          return `/${type}/${id}`;
      }
    };
  }
};

/* eslint-enable no-shadow */

export default {
  namespaced: true,
  state,
  getters
};

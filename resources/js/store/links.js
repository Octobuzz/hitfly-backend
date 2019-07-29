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

        case 'user': {
          let actualType = type;

          // this is used to redirect admin's sets as playlists
          if (type === 'playlist' || type === 'set') {
            actualType = 'playlist';
          }

          return `/user/${params.userId}/${actualType}/${id}`;
        }

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

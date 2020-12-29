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
      const routeSegments = fullPath.split('/').slice(1);

      // TODO: music-group route

      const getProfileRoute = (segment) => {
        switch (segment) {
          case 'my-music':
            return `/profile/my-music/${type}/${id}`;

          case 'favourite':
            return `/profile/favourite/${type}/${id}`;

          default:
            return '/__not_found__'; // some route that is guaranteed to be non existent
        }
      };

      switch (routeSegments[0]) {
        case 'profile':
          return getProfileRoute(routeSegments[1]);

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

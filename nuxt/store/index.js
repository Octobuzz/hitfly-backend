/* eslint-disable no-param-reassign, no-shadow */

export const state = () => ({
  favInProcess: {
    track: [],
    album: [],
    collection: []
  },
  editGroupId: null
});

export const getters = {
  favInProcess({ favInProcess }) {
    return ({ type, id }) => (
      favInProcess[type]
        .some(idInProcess => idInProcess === id)
    );
  }
};

export const mutations = {
  addFavInProcess({ favInProcess }, { type, id }) {
    favInProcess[type].push(id);
  },

  removeFavInProcess({ favInProcess }, { type, id }) {
    favInProcess[type] = favInProcess[type].filter(
      idInStore => idInStore !== id
    );
  }
};

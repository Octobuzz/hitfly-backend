/* eslint-disable no-param-reassign, no-shadow */

export const state = () => ({
  history: []
});

export const getters = {
  history({ history }) {
    return history;
  }
};

export const mutations = {
  push({ history }, { fullPath }) {
    history.push(fullPath);
  }
};

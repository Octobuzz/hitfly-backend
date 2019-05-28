/* eslint-disable no-param-reassign, no-shadow */

export const state = () => ({
  paddingClass: null
});

export const getters = {
  paddingClass({ paddingClass }) {
    return paddingClass;
  }
};

export const mutations = {
  setPaddingClass(state, paddingClassName) {
    state.paddingClass = paddingClassName;
  }
};

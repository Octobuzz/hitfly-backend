module.exports = ({ route, store }) => {
  if (process.server) return;

  store.commit('history/push', route);
};

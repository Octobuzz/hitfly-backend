export default {
  computed: {
    currentPath() {
      const { matched: matchedRoutes } = this.$route;

      const currentPath = matchedRoutes.reduce((longestPath, route) => {
        if (route.path.split('/').length > longestPath.split('/').length) {
          return route.path;
        }
        return longestPath;
      }, '');

      return currentPath;
    },
  }
};

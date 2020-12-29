import gql from './gql';

export default {
  methods: {
    refetchMyFavouriteTracksCount() {
      this.$apollo.query({
        query: gql.query.MY_FAOURITE_TRACKS_COUNT,
        fetchPolicy: 'network-only'
      }).catch(err => console.dir(err));
    }
  }
};

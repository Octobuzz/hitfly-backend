<template>
  <div class="track-list">
    <TrackListEntry
      v-for="(track, index) in trackList"
      :key="track.id"
      :index="index"
      :track="track"
    />
  </div>
</template>

<script>
import gql from './gql';
import TrackListEntry from './TrackListEntry.vue';

export default {
  components: {
    TrackListEntry
  },

  props: {
    type: {
      query: String,
      required: true,
      validator: value => (
        ['all', 'my', 'favourite'].includes(value)
      )
    }
  },

  data() {
    return {
      trackList: [],
      favTrackList: [] // todo: temp
    };
  },

  computed: {
    apolloQuery() {
      switch (this.type) {
        case 'favourite':
          return gql.query.FAVOURITE_TRACK_LIST; // TODO: this should resolve to trackList
        case 'my':
        case 'all':
        default:
          return gql.query.TRACK_LIST;
      }
    },

    apolloVariables() {
      // eslint-disable-next-line default-case
      switch (this.type) {
        case 'my':
          return {
            my: true
          };
        case 'favourite':
        case 'all':
        default:
          return {};
      }
    }
  },

  created() {
    // TODO:  temp
    this.$apollo.addSmartQuery('favTrackList', {
      query: gql.query.FAVOURITE_TRACK_LIST,
      update(data) {
        console.log(data);
      }
    });

    this.$apollo.addSmartQuery('trackList', {
      // TODO: implement pagination or loadable list

      query: this.apolloQuery,
      variables: this.apolloVariables,
      manual: true,
      result(res) {
        const { data: { tracks }, networkStatus } = res;

        if (networkStatus === 7) {
          this.trackList = tracks.data;
        }
      },
      error(err) {
        console.log(err);
      },
    });
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>

<template>
  <div>
    <TrackListEntry
      v-for="(track, index) in trackList"
      :key="track.id"
      :index="index"
      :track="track"
    >
      <button>
        add to playlist
      </button>
    </TrackListEntry>
  </div>
</template>

<script>
import gql from './gql';
import TrackListEntry from './TrackListEntry.vue';

export default {
  components: {
    TrackListEntry
  },
  data() {
    return {
      trackList: []
    };
  },
  apollo: {
    trackList: {
      // TODO: implement pagination or loadable list

      query: gql.query.TRACK_LIST,
      manual: true,
      result(res) {
        const { data: { tracks }, networkStatus } = res;

        if (networkStatus === 7) {
          this.trackList = tracks.data;
        }
      },
      error(err) {
        console.log(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>

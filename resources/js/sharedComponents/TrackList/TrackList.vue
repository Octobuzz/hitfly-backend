<template>
  <div>
    <TrackListEntry
      v-for="(track, index) in tracks"
      :key="track.id"
      :index="index"
      :track="track"
    />
  </div>
</template>

<script>
import gql from 'graphql-tag';
import TrackListEntry from './TrackListEntry.vue';

export default {
  components: {
    TrackListEntry
  },
  data() {
    return {
      tracks: []
    };
  },
  apollo: {
    trackList: {
      // TODO: implement pagination or loadable list
      // TODO: lift fetch logic up

      query: gql`
        query {
          tracks(limit: 100, page: 1, my: true) {
            data {
              id
              trackName
              singer
              album {
                title
                author
                cover
              }
              user {
                id
              }
            }
          }
        }
      `,
      manual: true,
      result(res) {
        const { data: { tracks }, networkStatus } = res;

        if (networkStatus === 7) {
          this.tracks = tracks.data;
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

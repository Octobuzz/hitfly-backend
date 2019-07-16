<template>
  <div class="track-list">
    <slot name="header" />

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index + 1"
      :track-id="trackId"
      :fake-fav-button="fakeFavButton"
      :show-remove-button="showRemoveButton"
      @remove-track="onTrackRemove"
      @press-favourite="onFavouritePress"
      @play-track="playTrack"
    />
    <slot name="loader" />
    <slot name="loadButton" />
  </div>
</template>

<script>
import TrackListEntry from '../TrackListEntry';
import gql from 'graphql-tag';

export default {
  components: {
    TrackListEntry
  },
  props: {
    trackIdList: {
      type: Array,
      required: true
    },
    fakeFavButton: {
      type: Boolean,
      default: false
    },
    showRemoveButton: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    onTrackRemove(id) {
      this.$emit('remove-track', id);
    },
    onFavouritePress(id) {
      this.$emit('press-favourite', id);
    },
    playTrack(id) {
      if(this.$store.getters['player/currentTrack'].id !== id){
        this.$store.commit('player/pausePlaying');
        this.$apollo.provider.defaultClient.query({
          variables: {
            id: id
          },
          query: gql`query tracks {
            track(id: $id) {
              id
              filename
              singer
              trackName
              length
              userFavourite
              favouritesCount
              cover(
                  sizes: [size_32x32, size_48x48, size_104x104, size_120x120, size_150x150]
              ) {
                  size
                  url
              }
            }
          }`
        })
        .then(response => {
          this.$store.commit('player/pickTrack', response.data.track);
          this.$store.commit('player/pickPlaylist', this.trackIdList);
        })
        .catch(error => {
          console.dir(error)
        })
      }else{
        this.$store.commit('player/togglePlaying');
      }
    }
  }
};
</script>

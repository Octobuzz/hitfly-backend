<template>
  <div class="track-list">
    <slot name="header" />

    <div
      v-if="showTableHeader"
      class="track-list__header"
    >
      <span class="track-list__header-number">
        №
      </span>
      <span class="track-list__header-song">
        Песня
      </span>
      <span class="track-list__header-singer">
        Музыкант
      </span>
      <span class="track-list__header-album">
        Альбом
      </span>
      <span
        :class="[
          'track-list__header-duration',
          { 'track-list__header-duration_padded': showRemoveButton }
        ]"
      >
        Время
      </span>
    </div>

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index + 1"
      :track-id="trackId"
      :show-album-section="showAlbumSection"
      :fake-fav-button="fakeFavButton"
      :show-remove-button="showRemoveButton"
      :show-track-index="showTrackIndex"
      :show-add-to-play-list="showAddToPlayList"
      :column-layout="columnLayout"
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
    showTableHeader: {
      type: Boolean,
      default: false
    },
    showAddToPlayList: {
      type: Boolean,
      default: false
    },
    columnLayout: {
      type: Boolean,
      default: false
    },
    showTrackIndex: {
      type: Boolean,
      default: true
    },
    showAlbumSection: {
      type: Boolean,
      default: false
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

<style
  scoped
  lang="scss"
  src="./TrackList.scss"
/>

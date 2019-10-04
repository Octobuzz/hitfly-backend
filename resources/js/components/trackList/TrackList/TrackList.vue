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
          {
            'track-list__header-duration_padded-l': !isStar,
            'track-list__header-duration_padded-r': showRemoveButton,
            'track-list__header-duration_padded-l-zero': !isAuthenticated
          }
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
      :show-add-to-playlist="showAddToPlayList"
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
import { mapGetters } from 'vuex';
import TrackListEntry from '../TrackListEntry';
import gql from './gql';

export default {
  components: {
    TrackListEntry
  },

  props: {
    forType: {
      type: String,
      required: false
    },
    forId: {
      required: false
    },
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
      default: true
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

  computed: {
    isStar() {
      return this.$store.getters['profile/roles']('star');
    },
    ...mapGetters(['isAuthenticated', 'apolloClient'])
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
        this.$apollo.provider.clients[this.apolloClient].query({
          variables: {
            isAuthenticated: this.isAuthenticated,
            id
          },
          query: gql.query.QUEUE_TRACK
        }).then(response => {
          let data = {};
          if(this.forType !== undefined){
            data = {
              'type': this.forType,
              'id': this.forId
            };
          };
          this.$store.commit('player/changeCurrentType', data);
          this.$store.commit('player/pickTrack', response.data.track);
          this.$store.commit('player/pickPlaylist', this.trackIdList);
        }).catch(error => {
          console.dir(error)
        })
      }else{
        this.$store.commit('player/togglePlaying');
      }
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackList.scss"
/>

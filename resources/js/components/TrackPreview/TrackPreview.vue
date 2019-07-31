<template>
  <div :class="['album-preview', $attrs.class]">
    <div
      v-if="isLoading"
      class="album-preview__loader"
    >
      loading track...
    </div>
    <div
      v-if="!isLoading"
      class="album-preview__content"
    >
      <div class="album-preview__drape" />

      <img
        :key="trackId"
        :src="trackCoverUrl"
        alt="Track cover"
        class="album-preview__cover"
      >

      <div class="album-preview__button-section">
        <AddToFavouriteButton
          ref="addToFavouriteButton"
          class="album-preview__icon-button"
          passive="mobile-passive"
          hover="mobile-hover"
          item-type="track"
          :item-id="track.id"
        />

        <IconButton
          v-if="!currentPlaying"
          :class="[
            'album-preview__icon-button',
            'album-preview__play-button'
          ]"
          passive="mobile-passive"
          hover="mobile-hover"
          @press="playTrack"
        >
          <PlayIcon />
        </IconButton>
        <IconButton
          v-else
          :class="[
            'album-preview__icon-button'
          ]"
          passive="mobile-passive"
          hover="mobile-hover"
          @press="playTrack"
        >
            <PauseIcon />
          </IconButton>

        <TrackPopover
          :track-id="trackId"
          @press-favourite="onPressFavourite"
        >
          <IconButton
            class="album-preview__icon-button"
            passive="mobile-passive"
            hover="mobile-hover"
          >
            <DotsIcon />
          </IconButton>
        </TrackPopover>
      </div>
    </div>

    <div
      v-if="!isLoading"
      class="album-preview__footer"
    >
      <router-link to="/">
        <span class="album-preview__title">
          {{ track.trackName }}
        </span>
      </router-link>
      <span class="album-preview__author">
        {{ track.singer }}
      </span>
    </div>
  </div>
</template>

<script>
import TrackPopover from 'components/TrackPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {
    TrackPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlayIcon,
    PauseIcon
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    trackList: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      isLoading: true,
      track: null
    };
  },

  computed: {
    trackCoverUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.track.cover
          .filter(cover => cover.size === 'size_104x104')[0].url;
      }

      return this.track.cover
        .filter(cover => cover.size === 'size_120x120')[0].url;
    },

    currentPlaying() {
      return this.currentType.type === 'track' && this.currentType.id === this.trackId && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },
  },

  methods: {
    onPressFavourite() {
      this.$refs.addToFavouriteButton.$el.dispatchEvent(new Event('click'));
    },

    playTrack(){
      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'track' && this.currentType.id === this.trackId) {
          this.$store.commit('player/startPlaying');
        }else{
          this.$apollo.provider.defaultClient.query({
            query: gql.query.TRACK,
            variables: {
              id: this.trackId
            },
          })
          .then(response => {
            let data = {
              'type': 'track',
              'id': this.trackId
            };
            this.$store.commit('player/pausePlaying');
            this.$store.commit('player/changeCurrentType', data);
            this.$store.commit('player/pickTrack', response.data.track);
            this.$store.commit('player/pickPlaylist', this.trackList);
          })
          .catch(error => {
            console.log(error);
          })
        }
      }
    }
  },

  apollo: {
    track() {
      return {
        query: gql.query.TRACK,
        variables() {
          // use function to allow rendering another album when the prop changes

          return {
            id: this.trackId
          };
        },
        update: ({ track }) => {
          this.isLoading = false;

          return track;
        },
        error: (error) => {
          console.log(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackPreview.scss"
/>

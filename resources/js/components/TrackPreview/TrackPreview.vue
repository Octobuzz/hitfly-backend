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

        <UnauthenticatedPopoverWrapper placement="right">
          <template #auth-content>
            <AddToFavouriteButton
              ref="addToFavouriteButton"
              class="album-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="track"
              :item-id="track.id"
            />
          </template>

          <template #unauth-popover-trigger>
            <AddToFavouriteButton
              class="album-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="track"
              :item-id="track.id"
              :fake="true"
            />
          </template>
        </UnauthenticatedPopoverWrapper>

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
      <span>
        <span class="album-preview__title">
          {{ track.trackName }}
        </span>
      </span>
      <span class="album-preview__author">
        {{ track.singer }}
      </span>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TrackPopover from 'components/TrackPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
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
    PauseIcon,
    UnauthenticatedPopoverWrapper
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
    ...mapGetters(['isAuthenticated', 'apolloClient']),

    trackCoverUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.track.cover
          .filter(cover => cover.size === 'size_48x48')[0].url;
      }

      return this.track.cover
        .filter(cover => cover.size === 'size_150x150')[0].url;
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
      // if(this.currentPlaying){
      //   this.$store.commit('player/togglePlaying');
      // }else{
      //   if(this.currentType.type === 'track' && this.currentType.id === this.trackId) {
      //     this.$store.commit('player/pausePlaying');
      //   }else{
      //     console.log(this.$store.getters['player/isPlaying']);
      //     this.$apollo.provider.clients[this.apolloClient].query({
      //       query: gql.query.QUEUE_TRACK,
      //       variables: {
      //         id: this.trackId,
      //         isAuthenticated: this.isAuthenticated
      //       },
      //     })
      //     .then(response => {
            // let data = {
            //   'type': 'track',
            //   'id': this.trackId
            // };
      //       this.$store.commit('player/changeCurrentType', data);
      //       this.$store.commit('player/pickTrack', response.data.track);
      //       this.$store.commit('player/pickPlaylist', this.trackList);
      //       console.log(this.$store.getters['player/currentTrack']);
      //     })
      //     .catch(error => {
      //       console.log(error);
      //     })
      //   }
      // }
      if(this.$store.getters['player/currentTrack'].id !== this.trackId){
        this.$store.commit('player/pausePlaying');
        this.$apollo.provider.clients[this.apolloClient].query({
          variables: {
            isAuthenticated: this.isAuthenticated,
            id: this.trackId
          },
          query: gql.query.QUEUE_TRACK
        })
        .then(response => {
          let data = {
            'type': 'track',
            'id': this.trackId
          };
          this.$store.commit('player/changeCurrentType', data);
          this.$store.commit('player/pickTrack', response.data.track);
          this.$store.commit('player/pickPlaylist', this.trackList);
        })
        .catch(error => {
          console.dir(error)
        })
      }else{
        this.$store.commit('player/togglePlaying');
      }
    }
  },

  apollo: {
    track() {
      return {
        query: gql.query.QUEUE_TRACK,
        variables() {
          // use function to allow rendering another album when the prop changes

          return {
            id: this.trackId,
            isAuthenticated: this.isAuthenticated
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

<template>
  <div v-if="trackLoaded" class="track-list-entry">
    <span
      v-if="showTrackIndex && desktop"
      class="track-list-entry__index"
    >
      {{ index }}
    </span>

    <button
      class="track-list-entry__album"
      @click="playTrack"
    >
      <img
        v-if="!activeTrack"
        :src="albumCoverUrl"
        alt="Album cover"
      >
      <PauseIcon
        v-else-if="activeTrack && this.$store.getters['player/isPlaying']"
      />
      <PlayIcon v-else />
    </button>

    <UnauthenticatedPopoverWrapper placement="right">
      <template #auth-content>
        <AddToFavouriteButton
          v-show="desktop"
          ref="addToFavButton"
          class="track-list-entry__icon-button"
          passive="secondary-passive"
          hover="secondary-hover"
          item-type="track"
          :item-id="trackId"
          :fake="fakeFavButton"
          @press-favourite="onFavouritePress"
        />
      </template>

      <template #unauth-popover-trigger>
        <AddToFavouriteButton
          v-show="desktop"
          ref="addToFavButton"
          class="track-list-entry__icon-button"
          passive="secondary-passive"
          hover="secondary-hover"
          item-type="track"
          :item-id="trackId"
          :fake="true"
        />
      </template>
    </UnauthenticatedPopoverWrapper>

    <template v-if="!columnLayout && desktop">
      <span class="track-list-entry__track-name">
        <WordTrimmedWithTooltip
          :word="track.trackName"
          @click="playTrack"
        />
      </span>

      <WordTrimmedWithTooltip
        :class="[
          'track-list-entry__track-author',
          { 'track-list-entry__track-author_no-highlight': isUserTrack }
        ]"
        :word="track.singer"
        @click.native="goToTrackSinger"
      />

      <WordTrimmedWithTooltip
        v-if="showAlbumSection"
        class="track-list-entry__album-title"
        :word="track.album && track.album.title"
      />
    </template>

    <div
      v-show="!desktop || columnLayout"
      :class="[
        'track-list-entry__track-data',
        'track-list-entry__track-data_column-layout'
      ]"
    >
      <span
        :class="[
          'track-list-entry__track-name',
          'track-list-entry__track-name_column-layout'
        ]"
      >
        <WordTrimmedWithTooltip
          class="track-list-entry__track-name"
          :word="track.trackName"
          @click.native="playTrack"
        />
      </span>

      <WordTrimmedWithTooltip
        :class="[
          'track-list-entry__track-author',
          'track-list-entry__track-author_underline'
        ]"
        :word="track.singer"
        @click.native="goToTrackSinger"
      />
    </div>

    <UnauthenticatedPopoverWrapper
      v-if="!columnLayout"
      placement="left"
    >
      <template #auth-content>
        <TrackToPlaylistPopover
          v-if="desktop && !isStar && showAddToPlaylist"
          :track-id="trackId"
        >
          <IconButton
            passive="secondary-passive"
            hover="secondary-hover"
            :tooltip="tooltip.add"
          >
            <PlusIcon />
          </IconButton>
        </TrackToPlaylistPopover>
      </template>

      <template #unauth-popover-trigger>
        <IconButton
          v-if="desktop && showAddToPlaylist"
          passive="secondary-passive"
          hover="secondary-hover"
          :tooltip="tooltip.add"
        >
          <PlusIcon />
        </IconButton>
      </template>
    </UnauthenticatedPopoverWrapper>

    <TrackActionsPopover
      v-if="isAuthenticated"
      :track-id="trackId"
      :show-remove-option="showRemoveButton"
      @press-favourite="pressFavourite"
      @remove-track="onRemovePress"
    >
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.actions"
      >
        <DotsIcon />
      </IconButton>
    </TrackActionsPopover>

    <span class="track-list-entry__duration">
      {{ formatTrackDuration(track.length) }}
    </span>

    <template v-if="isAuthenticated && showRemoveButton">
      <IconButton
        v-if="!isMyTrack"
        class="track-list-entry__icon-button"
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.remove"
        @press="onRemovePress"
      >
        <CrossIcon />
      </IconButton>

      <IconButton
        v-else
        class="track-list-entry__icon-button"
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.edit"
        @press="onEditPress"
      >
        <PencilIcon />
      </IconButton>
    </template>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import WordTrimmedWithTooltip from 'components/WordTrimmedWithTooltip';
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PencilIcon from 'components/icons/PencilIcon.vue';
import gql from './gql';
import TrackToPlaylistPopover from '../TrackToPlaylistPopover';
import TrackActionsPopover from '../TrackActionsPopover';

const MOBILE_WIDTH = 767;

export default {
  components: {
    UnauthenticatedPopoverWrapper,
    WordTrimmedWithTooltip,
    TrackToPlaylistPopover,
    TrackActionsPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlusIcon,
    CrossIcon,
    PauseIcon,
    PlayIcon,
    PencilIcon
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    index: {
      type: Number,
      required: true
    },
    showAddToPlaylist: {
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

  data() {
    return {
      track: null,
      trackLoaded: false,
      tooltip: {
        remove: {
          content: 'Удалить из текущего списка'
        },
        edit: {
          content: 'Редактировать песню'
        },
        add: {
          content: 'Добавить в плейлист'
        },
        actions: {
          content: 'Еще...'
        },
      }
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    albumCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_48x48')[0].url;
    },

    activeTrack() {
      return this.trackId === this.$store.getters['player/currentTrack'].id;
    },

    singerLink() {
      const { track, isMyTrack } = this;

      if (!track) return '';

      // if user exists the musicGroup.id could be presented or not
      // depending on who is author of the track

      const inProfile = this.$route.fullPath.startsWith('/profile/');

      if (track.musicGroup !== null) {
        return '';
      }
      if (isMyTrack && inProfile) {
        return '';
      }
      if (isMyTrack && !inProfile) {
        return '/profile/my-music';
      }

      return `/user/${track.user.id}/music`;
    },

    isMyTrack() {
      if (!this.track || !this.track.user) {
        return false;
      }
      return this.track.user.id === this.myId;
    },

    isUserTrack() {
      if (!this.track || !this.track.user) {
        return true;
      }

      if (this.$route.fullPath.startsWith('/profile/')) {
        return this.isMyTrack;
      }

      if (this.$route.fullPath.startsWith('/user/')) {
        return +this.$route.params.userId === this.track.user.id;
      }

      return false;
    },

    isStar() {
      return this.$store.getters['profile/roles']('star');
    },

    ...mapGetters({
      isAuthenticated: 'isAuthenticated',
      apolloClient: 'apolloClient',
      myId: 'profile/myId'
    })
  },

  methods: {
    playTrack() {
      this.$emit('play-track', this.trackId);
    },

    onFavouritePress() {
      this.$emit('press-favourite', this.trackId);
    },

    onRemovePress() {
      this.$emit('remove-track', this.trackId);
    },

    onEditPress() {
      this.$router.push(`/profile/edit/track/${this.trackId}`);
    },

    pressFavourite() {
      this.$refs.addToFavButton.onPress();
    },

    formatTrackDuration(sec) {
      if (sec === null) return '';

      const minutes = Math.floor(sec / 60);
      const seconds = Math.floor(sec % 60);

      return `${minutes}:${seconds < 10 ? `0${seconds}` : seconds}`;
    },

    goToTrackSinger() {
      this.$router.push(this.singerLink);
    }
  },

  apollo: {
    track() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables: {
          isAuthenticated: this.isAuthenticated,
          id: this.trackId,
        },
        update: ({ track }) => {
          this.trackLoaded = true;

          return track;
        },
        error(error) {
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
  src="./TrackListEntry.scss"
/>

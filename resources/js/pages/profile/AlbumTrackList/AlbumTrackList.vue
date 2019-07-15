<template>
  <div :class="['album-track-list', containerPaddingClass]">
    <ReturnHeader class="album-track-list__return-button" />

    <template v-if="albumFetched && shownTrack">
      <span class="album-track-list__singer">
        {{ shownTrack.singer }}
      </span>

      <div class="album-track-list__track-section">
        <img
          :src="
            shownTrack.cover.filter(
              cover => cover.size === 'size_150x150'
            )[0].url
          "
          alt="Track cover"
          class="album-track-list__track-cover"
        >

        <div class="album-track-list__player-section">
          <span class="h3 album-track-list__album-title">
            {{ album.title }}
          </span>
          <span class="album-track-list__album-data">
            <span class="album-track-list__total-tracks-info">
              {{ totalTracksInfo(album.tracksCount) }}
            </span>
            {{ totalDurationInfo('hours', album.tracksTime) }}
            {{ totalDurationInfo('mins', album.tracksTime) }}
          </span>

          <div class="album-track-list__player" />
        </div>
      </div>

      <div class="album-track-list__button-section">
        <button
          :class="[
            'album-track-list__button',
            'album-track-list__button_listen'
          ]"
        >
          <CirclePlayIcon />
          Слушать
        </button>

        <AddToFavButton
          ref="addToFavButton"
          class="album-track-list__button"
          passive="standard-passive"
          hover="standard-hover"
          modifier="squared bordered"
          item-type="album"
          :item-id="albumId"
        />

        <AlbumPopover
          :album-id="albumId"
          @press-favourite="onPressFavourite"
          @album-removed="goBack"
        >
          <IconButton
            class="album-track-list__button album-track-list__button_more"
            passive="standard-passive"
            hover="standard-hover"
            modifier="squared bordered"
            :tooltip="tooltip.more"
          >
            <DotsIcon />
          </IconButton>
        </AlbumPopover>
      </div>
    </template>

    <UniversalTrackList
      for-type="album"
      :for-id="albumId"
      :show-remove-button="showRemoveButton"
      @initialized="onTrackListInitialized"
      @tracks-removed="refetchTotalInfo"
    />
  </div>
</template>

<script>
// When receive update from vuex for the id of currently playing track
// we should check if the track album is the same as the album in question.
//
// If true, we should update current data regarding to the track.
// If false, we should ignore.
//
// We also have a case where we visit the page while already listening a track
// from the album. This case will be handled automatically thanks to reactivity.

import currentPath from 'mixins/currentPath';
import containerPaddingClass from 'mixins/containerPaddingClass';
import playingTrackId from 'mixins/playingTrackId';
import totalTracksInfo from 'mixins/totalInfoFormatting';
import IconButton from 'components/IconButton.vue';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavButton from 'components/AddToFavouriteButton';
import UniversalTrackList from 'components/UniversalTrackList';
import AlbumPopover from 'components/AlbumPopover';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    UniversalTrackList,
    ReturnHeader,
    AlbumPopover,
    IconButton,
    CirclePlayIcon,
    DotsIcon,
    AddToFavButton
  },

  mixins: [
    currentPath,
    containerPaddingClass,
    playingTrackId,
    totalTracksInfo
  ],

  data() {
    return {
      playingTrack: null,
      firstAlbumTrack: null,
      firstAlbumTrackId: null,
      album: null,
      albumFetched: false,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    albumId() {
      return +this.$route.params.albumId;
    },

    showRemoveButton() {
      const pathPrefix = this.currentPath.split('/')[1];

      switch (pathPrefix) {
        case 'profile':
          return true;

        case 'user':
          return false;

        case 'music-group':
          // TODO: check if the group belongs to current user
          return false;

        default:
          return false;
      }
    },

    shownTrack() {
      const {
        playingTrack,
        firstAlbumTrack,
        albumId
      } = this;

      if (!firstAlbumTrack) {
        return null;
      }

      if (playingTrack && playingTrack.album.id === albumId) {
        return playingTrack;
      }

      return firstAlbumTrack;
    }
  },

  methods: {
    onTrackListInitialized(data) {
      if (data instanceof Array === false
        || data.length === 0) return;

      this.firstAlbumTrackId = data[0].id;
    },

    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    },

    goBack() {
      this.$router.go(-1);
    },

    refetchTotalInfo() {
      this.$apollo.query({
        query: gql.query.ALBUM_TOTAL_INFO,
        fetchPolicy: 'network-only',
        variables: { id: this.albumId },
        error(err) {
          console.dir(err);
        }
      });
    }
  },

  apollo: {
    album: {
      query: gql.query.ALBUM,
      variables() {
        return {
          id: this.albumId
        };
      },
      update({ album }) {
        this.albumFetched = true;

        return album;
      },
      error(err) {
        console.dir(err);
      }
    },

    playingTrack: {
      query: gql.query.TRACK,
      variables() {
        return {
          id: this.playingTrackId
        };
      },
      update({ track }) {
        return track;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !ofNumber(this.playingTrackId);
      }
    },

    firstAlbumTrack: {
      query: gql.query.TRACK,
      variables() {
        return {
          id: this.firstAlbumTrackId
        };
      },
      update({ track }) {
        return track;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !ofNumber(this.firstAlbumTrackId);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AlbumTrackList.scss"
/>

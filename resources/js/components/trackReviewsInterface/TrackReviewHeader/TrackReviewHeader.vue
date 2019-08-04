<template>
  <div class="track-review-header">
    <img
      :src="trackCoverUrl"
      alt="Track cover"
      class="track-review-header__cover"
    >

    <span class="h4 track-review-header__m-track-name">
      {{ track.trackName }}
    </span>

    <span class="track-review-header__m-singer">
      {{ track.singer }}
    </span>

    <div class="track-review-header__cover-button-container">
      <UnauthenticatedPopoverWrapper>
        <template #auth-content>
          <TrackToPlaylistPopover
            :track-id="trackId"
            :position-change-breakpoint="1024"
          >
            <IconButton
              passive="standard-passive"
              hover="standard-hover"
              :tooltip="tooltip.add"
            >
              <PlusIcon />
            </IconButton>
          </TrackToPlaylistPopover>
        </template>

        <template #unauth-popover-trigger>
          <IconButton
            passive="standard-passive"
            hover="standard-hover"
            :tooltip="tooltip.add"
          >
            <PlusIcon />
          </IconButton>
        </template>
      </UnauthenticatedPopoverWrapper>

      <UnauthenticatedPopoverWrapper>
        <template #auth-content>
          <AddToFavouriteButton
            ref="addToFavButton"
            item-type="track"
            :item-id="trackId"
            :with-counter="true"
          />
        </template>

        <template #unauth-popover-trigger>
          <AddToFavouriteButton
            ref="addToFavButton"
            item-type="track"
            :item-id="trackId"
            :with-counter="true"
            :fake="true"
          />
        </template>
      </UnauthenticatedPopoverWrapper>

      <TrackActionsPopover
        :track-id="trackId"
        :show-remove-option="false"
        :position-change-breakpoint="1024"
        @press-favourite="onPressFavourite"
      >
        <IconButton
          passive="standard-passive"
          hover="standard-hover"
          :tooltip="tooltip.more"
        >
          <DotsIcon />
        </IconButton>
      </TrackActionsPopover>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import TrackToPlaylistPopover from 'components/trackList/TrackToPlaylistPopover';
import TrackActionsPopover from 'components/trackList/TrackActionsPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import IconButton from 'components/IconButton.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import gql from './gql';

export default {
  components: {
    AddToFavouriteButton,
    IconButton,
    PlusIcon,
    DotsIcon,
    TrackToPlaylistPopover,
    TrackActionsPopover,
    UnauthenticatedPopoverWrapper
  },

  props: {
    trackId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      track: null,
      tooltip: {
        add: {
          content: 'Добавить в плейлист'
        },
        more: {
          content: 'Еще'
        },
        actions: {
          content: 'Еще...'
        }
      }
    };
  },

  computed: {
    trackCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_150x150')[0].url;
    },
    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  methods: {
    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    }
  },

  apollo: {
    track() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables: {
          isAuthenticated: this.isAuthenticated,
          id: this.trackId
        },
        update({ track }) {
          return track;
        },
        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackRreviewHeader.scss"
/>

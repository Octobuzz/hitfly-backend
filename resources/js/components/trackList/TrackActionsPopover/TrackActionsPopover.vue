<template>
  <v-popover
    :popover-base-class="[
      'track-actions-popover',
      `track-actions-popover_breakpoint-${positionChangeBreakpoint}`
    ].join(' ')"
    popover-wrapper-class="track-actions-popover__wrapper"
    popover-inner-class="track-actions-popover__inner"
    popover-arrow-class="track-actions-popover__arrow"
    :placement="popoverPlacement"
    :popper-options="popperOptions"
    :auto-hide="true"
    @auto-hide="leaveAllMenus(300)"
  >
    <slot />

    <button
      ref="closeButton"
      v-close-popover
      style="display: none;"
    />

    <template #popover>
      <img
        :src="albumCoverUrl"
        alt="Album cover"
        class="track-actions-popover__album-cover"
      >
      <div
        v-if="track"
        class="track-actions-popover__header"
      >
        <span class="track-actions-popover__album-song">
          {{ track.trackName }}
        </span>
        <span class="track-actions-popover__album-author">
          {{ track.singer }}
        </span>
      </div>

      <hr class="track-actions-popover__delimiter">

      <div
        v-if="!inPlaylistMenu && !inReviewMenu"
        class="track-actions-popover__menu"
      >
        <!--TODO: use interactive elements-->
        <template v-if="isAuthenticated && canAddReviews">
          <span
            :class="[
              'track-actions-popover__menu-item',
              'track-actions-popover__review-item'
            ]"
            @click="enterReviewMenu"
          >
            <span class="track-actions-popover__menu-item-icon">
              <PopupIcon />
            </span>
            Написать отзыв
          </span>

          <hr
            :class="[
              'track-actions-popover__delimiter',
              'track-actions-popover__delimeter_zero-on-top'
            ]"
          >
        </template>

        <span
          class="track-actions-popover__menu-item"
        >
          <span class="track-actions-popover__menu-item-icon">
            <PlayNextIcon />
          </span>
          Слушать далее
        </span>
        <span class="track-actions-popover__menu-item">
          <!--TODO: removing from queue should be available instead-->
          <!--of this under corresponding conditions-->

          <span class="track-actions-popover__menu-item-icon">
            <ListPlusIcon />
          </span>
          Добавить в список воспроизведения
        </span>
        <span
          v-if="isAuthenticated"
          class="track-actions-popover__menu-item"
          @click="enterPlaylistMenu"
        >
          <span class="track-actions-popover__menu-item-icon">
            <PlusIcon />
          </span>
          Добавить в плейлист
        </span>
        <span
          v-if="isAuthenticated && track"
          class="track-actions-popover__menu-item"
          @click="onFavouritePress"
        >
          <span class="track-actions-popover__menu-item-icon">
            <HeartIcon />
          </span>
          <span v-if="!track.userFavourite">
            Добавить в любимые треки
          </span>
          <span v-if="track.userFavourite">
            Убрать из любимых треков
          </span>
        </span>
        <span
          v-if="isAuthenticated && isWatchable"
          class="track-actions-popover__menu-item"
          @click="onWatchOwnerPress"
        >
          <span class="track-actions-popover__menu-item-icon">
            <UserPlusIcon />
          </span>
          {{ ownerIsWatched ? 'Не следить за автором' : 'Следить за автором' }}
        </span>

<!--        Commented code should be present in future release        -->

<!--        <span-->
<!--          class="track-actions-popover__menu-item"-->
<!--        >-->
<!--          <span class="track-actions-popover__menu-item-icon">-->
<!--            <BendedArrowIcon />-->
<!--          </span>-->
<!--          Поделиться песней-->
<!--        </span>-->
      </div>

<!--      Commented code should be present in future release-->

<!--      <span-->
<!--        v-if="!inPlaylistMenu && !inReviewMenu"-->
<!--        class="track-actions-popover__tell-problem"-->
<!--      >-->
<!--        Сообщить о проблеме-->
<!--      </span>-->
      <span
        v-if="isAuthenticated && inPlaylistMenu"
        class="track-actions-popover__add-playlist-header"
      >
        Добавить в плейлист
      </span>

      <TrackToPlaylist
        v-if="isAuthenticated"
        v-show="inPlaylistMenu"
        ref="playlistMenu"
        :track-id="trackId"
        @track-added="onTrackAdded"
      />

      <AddTrackReview
        v-show="inReviewMenu"
        ref="reviewMenu"
        :track-id="trackId"
        @review-added="onReviewAdded"
      />

      <span
        v-if="inPlaylistMenu"
        class="track-actions-popover__go-back-button"
        @click="leavePlaylistMenu(50)"
      >
        Назад
      </span>

      <span
        v-if="inReviewMenu"
        @click="leaveReviewMenu(50)"
      >
        <FormButton
          class="track-actions-popover__cancel-button"
          modifier="secondary"
        >
          Отменить
        </FormButton>
      </span>
    </template>
  </v-popover>
</template>

<script>
import { mapGetters } from 'vuex';
import followMixin from 'mixins/followMixin';
import PopupIcon from 'components/icons/popover/PopupIcon.vue';
import PlayNextIcon from 'components/icons/popover/PlayNextIcon.vue';
import ListPlusIcon from 'components/icons/popover/ListPlusIcon.vue';
import PlusIcon from 'components/icons/popover/PlusIcon.vue';
import HeartIcon from 'components/icons/popover/HeartIcon.vue';
import UserPlusIcon from 'components/icons/popover/UserPlusIcon.vue';
import CrossIcon from 'components/icons/popover/CrossIcon.vue';
import BendedArrowIcon from 'components/icons/popover/BendedArrowIcon.vue';
import FormButton from 'components/FormButton.vue';
import gql from './gql';
import TrackToPlaylist from '../TrackToPlaylist';
import AddTrackReview from '../AddTrackReview';

export default {
  components: {
    TrackToPlaylist,
    AddTrackReview,
    FormButton,
    PopupIcon,
    PlayNextIcon,
    ListPlusIcon,
    PlusIcon,
    HeartIcon,
    UserPlusIcon,
    CrossIcon,
    BendedArrowIcon
  },

  mixins: [followMixin('track', 'track')],

  props: {
    trackId: {
      type: Number,
      required: true
    },
    showRemoveOption: {
      type: Boolean,
      default: true
    },
    positionChangeBreakpoint: {
      type: Number,
      default: 767,
      validator: val => (
        [767, 1024].includes(val)
      )
    }
  },

  data() {
    return {
      popover: {
        desktop: {
          placement: 'left-start',
          popperOptions: { modifiers: { offset: { offset: '-30%p' } } },
        },
        mobile: {
          placement: 'bottom-end',
          popperOptions: {
            modifiers: {
              flip: { enabled: false },
              preventOverflow: {
                enabled: true,
                padding: 20
              }
            }
          }
        }
      },
      track: null,
      inPlaylistMenu: false,
      inReviewMenu: false,
      isFetching: true
    };
  },

  computed: {
    popoverPlacement() {
      return this.windowWidth > this.positionChangeBreakpoint
        ? this.popover.desktop.placement
        : this.popover.mobile.placement;
    },

    popperOptions() {
      return this.windowWidth > this.positionChangeBreakpoint
        ? this.popover.desktop.popperOptions
        : this.popover.mobile.popperOptions;
    },

    albumCoverUrl() {
      if (!this.track) return '#';

      return this.track.cover
        .filter(cover => cover.size === 'size_48x48')[0].url;
    },

    isWatchable() {
      if (!this.track) return false;

      return !this.track.my;
    },

    canAddReviews() {
      const rolePermission = this.$store.getters['profile/ableToComment'];

      return rolePermission && this.track && !this.track.my;
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  methods: {
    playNext() {

    },

    onFavouritePress() {
      this.$refs.closeButton.click();
      setTimeout(() => {
        this.$emit('press-favourite');
      }, 50);
    },

    enterPlaylistMenu() {
      this.$refs.playlistMenu.fetchPlaylistList();

      // have problem capturing click:
      // popover becomes hidden when inPlaylistMenu updates (hides) menu div
      // (consider the case when playlist list is empty)

      setTimeout(() => {
        this.inPlaylistMenu = true;
      }, 50);
    },

    enterReviewMenu() {
      setTimeout(() => {
        this.inReviewMenu = true;
      }, 50);
    },

    leavePlaylistMenu(delay) {
      setTimeout(() => {
        this.inPlaylistMenu = false;
      }, delay);
    },

    leaveReviewMenu(delay) {
      setTimeout(() => {
        this.inReviewMenu = false;
      }, delay);
    },

    leaveAllMenus(delay) {
      this.leavePlaylistMenu(delay);
      this.leaveReviewMenu(delay);
    },

    onTrackAdded(addedTrack, toPlaylist) {
      setTimeout(() => {
        this.$refs.closeButton.click();
        this.leavePlaylistMenu(300);

        setTimeout(() => {
          this.$message(
            `Трек добавлен в "${toPlaylist.title}"`,
            'info',
            { timeout: 2000 }
          );
        }, 200);
      }, 200);
    },

    onReviewAdded() {
      setTimeout(() => {
        this.$refs.closeButton.click();
        this.leaveReviewMenu(300);

        setTimeout(() => {
          this.$message(
            'Отзыв добавлен',
            'info',
            { timeout: 2000 }
          );
        }, 100);
      }, 50);
    },

    emitRemoveTrack() {
      this.$emit('remove-track');
    },

    followMixinCallback() {
      this.$refs.closeButton.click();
    }
  },

  apollo: {
    track() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.trackId,
          };
        },
        update: ({ track }) => track,
        error(error) {
          console.dir(error);
        }
      };
    }
  }
};
</script>

<style
  lang="scss"
  src="./TrackActionsPopover.scss"
/>

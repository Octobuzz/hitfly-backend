<template>
  <v-popover
    popover-base-class="album-popover"
    popover-wrapper-class="album-popover__wrapper"
    popover-inner-class="album-popover__inner"
    popover-arrow-class="album-popover__arrow"
    :placement="popover.placement"
    :popper-options="popover.popperOptions"
    :auto-hide="true"
    @auto-hide="leaveRemoveMenu"
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
        class="album-popover__cover"
      >
      <div class="album-popover__header">
        <span class="album-popover__title">
          {{ album.title }}
        </span>
        <span
          v-if="album.singer"
          class="album-popover__singer"
        >
          {{ album.singer }}
        </span>
      </div>

      <hr class="album-popover__delimiter">

      <div
        v-if="!inRemoveMenu"
        class="album-popover__menu"
      >
        <!--TODO: use interactive elements-->
        <span
          v-if="!hidePlayerActions"
          class="album-popover__menu-item"
        >
          <span class="album-popover__menu-item-icon">
            <PlayNextIcon />
          </span>
          Слушать далее
        </span>

        <span
          class="album-popover__menu-item"
          @click="onFavouritePress"
        >
          <span class="album-popover__menu-item-icon">
            <HeartIcon />
          </span>
          <span v-if="!album.userFavourite">
            Добавить в любимые альбомы
          </span>
          <span v-if="album.userFavourite">
            Убрать из любимых альбомов
          </span>
        </span>

        <span
          v-if="!hidePlayerActions"
          class="album-popover__menu-item"
        >
          <!--TODO: removing from queue should be available instead-->
          <!--of this under corresponding conditions-->

          <span class="album-popover__menu-item-icon">
            <ListPlusIcon />
          </span>
          Добавить в список воспроизведения
        </span>

        <span
          v-if="isWatchable"
          class="album-popover__menu-item"
          @click="onWatchOwnerPress"
        >
          <span class="album-popover__menu-item-icon">
            <UserPlusIcon />
          </span>
          {{ ownerIsWatched ? 'Не следить за автором' : 'Следить за автором' }}
        </span>

<!--        <span class="album-popover__menu-item">-->
<!--          <span class="album-popover__menu-item-icon">-->
<!--            <BendedArrowIcon />-->
<!--          </span>-->
<!--          Поделиться-->
<!--        </span>-->

        <span
          v-if="isRemovable"
          class="album-popover__menu-item"
          @click="goToRemoveMenu"
        >
          <span class="album-popover__menu-item-icon">
            <CrossIcon />
          </span>
          Удалить альбом
        </span>
      </div>

      <AlbumPopoverRemoveMenu
        v-show="inRemoveMenu"
        :album-id="albumId"
        @cancel-remove="leaveRemoveMenu"
        @album-removed="onAlbumRemoved"
      />
    </template>
  </v-popover>
</template>

<script>
import followMixin from 'mixins/followMixin';
import PlayNextIcon from 'components/icons/popover/PlayNextIcon.vue';
import ListPlusIcon from 'components/icons/popover/ListPlusIcon.vue';
import HeartIcon from 'components/icons/popover/HeartIcon.vue';
import UserPlusIcon from 'components/icons/popover/UserPlusIcon.vue';
import CrossIcon from 'components/icons/popover/CrossIcon.vue';
import BendedArrowIcon from 'components/icons/popover/BendedArrowIcon.vue';
import AlbumPopoverRemoveMenu from '../AlbumPopoverRemoveMenu';
import gql from './gql';

export default {
  components: {
    PlayNextIcon,
    HeartIcon,
    ListPlusIcon,
    UserPlusIcon,
    BendedArrowIcon,
    CrossIcon,
    AlbumPopoverRemoveMenu
  },

  mixins: [followMixin('album', 'album')],

  props: {
    albumId: {
      type: Number,
      required: true
    },
    hidePlayerActions: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      popover: {
        placement: 'right-start',
        popperOptions: { modifiers: { offset: { offset: '-30%p' } } }
      },
      inRemoveMenu: false,
      album: null
    };
  },

  computed: {
    albumCoverUrl() {
      return this.album.cover
        .filter(cover => cover.size === 'size_48x48')[0].url;
    },

    isWatchable() {
      if (!this.album) return false;

      return !this.album.my;
    },

    isRemovable() {
      if (!this.album) return false;

      return this.album.my;
    },
  },

  methods: {
    onFavouritePress() {
      this.$refs.closeButton.click();
      setTimeout(() => {
        this.$emit('press-favourite', this.albumId);
      }, 200);
    },

    goToRemoveMenu() {
      // microtask problem: popover gets handled before click
      // so that the click could miss popover and collapse it

      setTimeout(() => { this.inRemoveMenu = true; });
    },

    leaveRemoveMenu() {
      setTimeout(() => { this.inRemoveMenu = false; });
    },

    onAlbumRemoved() {
      this.$refs.closeButton.click();

      setTimeout(() => {
        this.$emit('album-removed', this.albumId);

        this.$eventBus.$emit(
          'album-removed',
          this.albumId
        );
      }, 300);
    },

    followMixinCallback() {
      this.$refs.closeButton.click();
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
        return album;
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  lang="scss"
  src="./AlbumPopover.scss"
/>

<template>
  <v-popover
    popover-base-class="album-popover"
    popover-wrapper-class="album-popover__wrapper"
    popover-inner-class="album-popover__inner"
    popover-arrow-class="album-popover__arrow"
    :placement="popover.placement"
    :popper-options="popover.popperOptions"
    :auto-hide="true"
  >
    <slot />

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
        <span class="album-popover__menu-item">
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

        <span class="album-popover__menu-item">
          <!--TODO: removing from queue should be available instead-->
          <!--of this under corresponding conditions-->

          <span class="album-popover__menu-item-icon">
            <ListPlusIcon />
          </span>
          Добавить в список воспроизведения
        </span>

        <span
          v-if="!myAlbum"
          class="album-popover__menu-item"
        >
          <span class="album-popover__menu-item-icon">
            <UserPlusIcon />
          </span>
          Следить за автором
        </span>

        <span class="album-popover__menu-item">
          <span class="album-popover__menu-item-icon">
            <BendedArrowIcon />
          </span>
          Поделиться
        </span>

        <span
          v-if="myAlbum"
          class="album-popover__menu-item"
          @click="onAlbumRemovePress"
        >
          <span class="album-popover__menu-item-icon">
            <CrossIcon />
          </span>
          Удалить альбом
        </span>
      </div>

      <!--remove album component-->
    </template>
  </v-popover>
</template>

<script>
import PlayNextIcon from 'components/icons/popover/PlayNextIcon.vue';
import ListPlusIcon from 'components/icons/popover/ListPlusIcon.vue';
import HeartIcon from 'components/icons/popover/HeartIcon.vue';
import UserPlusIcon from 'components/icons/popover/UserPlusIcon.vue';
import CrossIcon from 'components/icons/popover/CrossIcon.vue';
import BendedArrowIcon from 'components/icons/popover/BendedArrowIcon.vue';
import gql from './gql';

export default {
  components: {
    PlayNextIcon,
    HeartIcon,
    ListPlusIcon,
    UserPlusIcon,
    BendedArrowIcon,
    CrossIcon
  },

  props: {
    albumId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      popover: {
        placement: 'right-start',
        popperOptions: { modifiers: { offset: { offset: '-30%p' } } }
      },
      inRemoveMenu: false
    };
  },

  computed: {
    albumCoverUrl() {
      return this.album.cover
        .filter(cover => cover.size === 'size_48x48')[0].url;
    },

    myAlbum() {
      return this.album.my;
    }
  },

  methods: {
    onFavouritePress() {
      this.emit('press-favourite', this.albumId);
    },

    onAlbumRemovePress() {

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
    },
  }
};
</script>

<style
  lang="scss"
  src="./AlbumPopover.scss"
/>

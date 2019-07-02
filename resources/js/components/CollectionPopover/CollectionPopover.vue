<template>
  <v-popover
    popover-base-class="collection-popover"
    popover-wrapper-class="collection-popover__wrapper"
    popover-inner-class="collection-popover__inner"
    popover-arrow-class="collection-popover__arrow"
    :placement="popover.placement"
    :popper-options="popover.popperOptions"
    :auto-hide="true"
  >
    <slot />

    <template #popover>
      <img
        :src="collectionCoverUrl"
        alt="Collection cover"
        class="collection-popover__cover"
      >
      <div class="collection-popover__header">
        <span class="collection-popover__title">
          {{ collection.title }}
        </span>
        <span
          v-if="collection.singer"
          class="collection-popover__singer"
        >
          {{ collection.singer }}
        </span>
      </div>

      <hr class="collection-popover__delimiter">

      <div
        v-if="!inRemoveMenu"
        class="collection-popover__menu"
      >
        <!--TODO: use interactive elements-->
        <span class="collection-popover__menu-item">
          <span class="collection-popover__menu-item-icon">
            <PlayNextIcon />
          </span>
          Слушать далее
        </span>

        <span
          class="collection-popover__menu-item"
          @click="onFavouritePress"
        >
          <span class="collection-popover__menu-item-icon">
            <HeartIcon />
          </span>

          <template v-if="!collection.isSet">
            <span v-if="!collection.userFavourite">
              Добавить в любимые плейлисты
            </span>
            <span v-if="collection.userFavourite">
              Убрать из любимых плейлистов
            </span>
          </template>

          <template v-else>
            <span v-if="!collection.userFavourite">
              Добавить в любимые подборки
            </span>
            <span v-if="collection.userFavourite">
              Убрать из любимых подборки
            </span>
          </template>
        </span>

        <span class="collection-popover__menu-item">
          <!--TODO: removing from queue should be available instead-->
          <!--of this under corresponding conditions-->

          <span class="collection-popover__menu-item-icon">
            <ListPlusIcon />
          </span>
          Добавить в список воспроизведения
        </span>

        <span
          v-if="!myCollection"
          class="collection-popover__menu-item"
        >
          <span class="collection-popover__menu-item-icon">
            <UserPlusIcon />
          </span>
          Следить за автором
        </span>

        <span class="collection-popover__menu-item">
          <span class="collection-popover__menu-item-icon">
            <BendedArrowIcon />
          </span>
          Поделиться
        </span>

        <span
          v-if="myCollection"
          class="collection-popover__menu-item"
          @click="onCollectionRemovePress"
        >
          <span class="collection-popover__menu-item-icon">
            <CrossIcon />
          </span>
          Удалить плейлист
        </span>
      </div>

      <!--remove collection component-->
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
    collectionId: {
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
    collectionCoverUrl() {
      return this.collection.image
        .filter(image => image.size === 'size_48x48')[0].url;
    },

    myCollection() {
      return this.collection.my;
    }
  },

  methods: {
    onFavouritePress() {
      this.emit('press-favourite', this.collectionId);
    },

    onCollectionRemovePress() {

    }
  },

  apollo: {
    collection: {
      query: gql.query.COLLECTION,
      variables() {
        return {
          id: this.collectionId
        };
      },
      update({ collection }) {
        return collection;
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
  src="./CollectionPopover.scss"
/>

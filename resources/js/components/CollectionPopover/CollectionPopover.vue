<template>
  <v-popover
    popover-base-class="collection-popover"
    popover-wrapper-class="collection-popover__wrapper"
    popover-inner-class="collection-popover__inner"
    popover-arrow-class="collection-popover__arrow"
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
        :src="collectionCoverUrl"
        alt="Collection cover"
        class="collection-popover__cover"
      >
      <div class="collection-popover__header">
        <span class="collection-popover__title">
          {{ collection.title }}
        </span>
        <span
          v-if="collection.user && collection.user.username"
          class="collection-popover__user"
        >
          {{ collection.user.username }}
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
          @click="goToRemoveMenu"
        >
          <span class="collection-popover__menu-item-icon">
            <CrossIcon />
          </span>
          Удалить плейлист
        </span>
      </div>

      <CollectionPopoverRemoveMenu
        v-show="inRemoveMenu"
        :collection-id="collectionId"
        @cancel-remove="leaveRemoveMenu"
        @collection-removed="onCollectionRemoved"
      />
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
import CollectionPopoverRemoveMenu from '../CollectionPopoverRemoveMenu';
import gql from './gql';

export default {
  components: {
    PlayNextIcon,
    HeartIcon,
    ListPlusIcon,
    UserPlusIcon,
    BendedArrowIcon,
    CrossIcon,
    CollectionPopoverRemoveMenu
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
      this.$refs.closeButton.click();
      setTimeout(() => {
        this.$emit('press-favourite', this.collectionId);
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

    onCollectionRemoved() {
      this.$refs.closeButton.click();

      setTimeout(() => {
        this.$emit('collection-removed', this.collectionId);

        this.$eventBus.$emit(
          'collection-removed',
          this.collectionId
        );
      }, 300);
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
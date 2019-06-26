<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <div
        v-show="showFirstLoader"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>

      <OtherUserCard
        v-show="!showFirstLoader"
        :item-container-class="itemContainerClass"
      />
    </template>
    <template
      v-if="desktop || !showFirstLoader"
      #right-column="{ paddingClass }"
    >
      <div
        v-if="renderNavBar"
        :class="['profile__nav-wrapper', paddingClass]"
      >
        <ul class="profile__nav">
          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': currentPath === '/user/:userId/music' }
            ]"
          >
            <router-link :to="`/user/${userId}/music`">
              Музыка
            </router-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': currentPath ==='/user/:userId/reviews' }
            ]"
          >
            <router-link :to="`/user/${userId}/reviews`">
              Отзывы
            </router-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': currentPath === '/user/:userId/my-reviews' }
            ]"
          >
            <router-link :to="`/user/${userId}/my-reviews`">
              Мои отзывы
            </router-link>
          </li>
        </ul>

        <div class="profile__nav-scroll-cloak" />
      </div>

      <PageHeader
        v-if="renderNavBar"
        :class="['profile__page-header', paddingClass]"
      >
        ПРОФИЛЬ
      </PageHeader>

      <div
        v-show="showSecondLoader"
        class="profile__user-card-loader_second"
      >
        <SpinnerLoader />
      </div>

      <router-view v-show="!showSecondLoader" />
    </template>
  </AppColumns>
</template>

<script>
import currentPath from 'mixins/currentPath';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';
import OtherUserCard from '../OtherUserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    SpinnerLoader,
    AppColumns,
    PageHeader,
    OtherUserCard
  },

  mixins: [currentPath],

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    userId() {
      return this.$route.params.userId;
    },

    renderNavBar() {
      return [
        '/user/:userId/music',
        '/user/:userId/reviews',
        '/user/:userId/user-reviews'
      ].some(mask => mask === this.currentPath);
    },

    showFirstLoader() {
      const {
        personalInfo,
        watchedUsers,
        watchedGroups
      } = this.$store.getters['loading/userCard'];

      return !(
        personalInfo.initialized
        && watchedUsers.initialized
        && watchedGroups.initialized
      );
    },

    showSecondLoader() {
      const { getters } = this.$store;

      /* eslint-disable no-fallthrough, no-case-declarations */

      switch (this.currentPath) {
        case '/user/:userId/music':
          const {
            tracks,
            albums,
            collections,
          } = getters['loading/music'];

          return !(
            tracks.initialized
            && albums.initialized
            && collections.initialized
          );

        case '/user/:userId/music/albums':
          return !getters['loading/music'].albums.initialized;

        case '/user/:userId/music/playlists':
          return !getters['loading/music'].collections.initialized;

        default:
          return false;
      }

      /* eslint-disable no-fallthrough */
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../MyProfileLayout/MyProfileLayout.scss"
/>

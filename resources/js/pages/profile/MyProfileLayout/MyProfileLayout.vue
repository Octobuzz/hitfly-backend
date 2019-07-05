<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <div
        v-show="showFirstLoader"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>

      <MyUserCard
        v-show="!showFirstLoader && renderUserCard"
        :item-container-class="itemContainerClass"
      />
    </template>

    <template
      v-if="desktop || !showFirstLoader"
      #right-column="{ paddingClass }"
    >
      <template v-if="loggedIn">
        <div
          v-if="renderNavBar"
          :class="['profile__nav-wrapper', paddingClass]"
        >
          <ul class="profile__nav">
            <template v-if="ableToPerform">
              <li
                :class="[
                  'profile__nav-endpoint',
                  { 'profile__nav-endpoint_active': currentPath === '/profile/my-music' }
                ]"
              >
                <router-link to="/profile/my-music">
                  Моя музыка
                </router-link>
              </li>

              <li
                :class="[
                  'profile__nav-endpoint',
                  { 'profile__nav-endpoint_active': currentPath === '/profile/favourite' }
                ]"
              >
                <router-link to="/profile/favourite">
                  Мне нравится
                </router-link>
              </li>

              <li
                :class="[
                  'profile__nav-endpoint',
                  { 'profile__nav-endpoint_active': currentPath === '/profile/reviews' }
                ]"
              >
                <router-link to="/profile/reviews">
                  Отзывы
                </router-link>
              </li>
            </template>

            <template v-if="ableToComment">
              <li
                :class="[
                  'profile__nav-endpoint',
                  { 'profile__nav-endpoint_active': currentPath === '/profile/my-reviews' }
                ]"
              >
                <router-link to="/profile/my-reviews">
                  Мои отзывы
                </router-link>
              </li>
            </template>
          </ul>

          <div class="profile__nav-scroll-cloak" />
        </div>

        <PageHeader
          v-if="renderNavBar"
          :class="['profile__page-header', paddingClass]"
        >
          ПРОФИЛЬ
        </PageHeader>

        <router-view v-show="!showSecondLoader" />
      </template>

      <div
        v-show="showSecondLoader || !loggedIn"
        class="profile__user-card-loader_second"
      >
        <SpinnerLoader />
      </div>
    </template>
  </AppColumns>
</template>

<script>
import currentPath from 'mixins/currentPath';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';
import MyUserCard from '../MyUserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    SpinnerLoader,
    AppColumns,
    PageHeader,
    MyUserCard
  },

  mixins: [currentPath],

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    renderNavBar() {
      return [
        '/profile/my-music',
        '/profile/favourite',
        '/profile/reviews',
        '/profile/my-reviews'
      ].some(mask => mask === this.currentPath);
    },

    renderUserCard() {
      return this.desktop || [
        '/profile/edit',
        '/profile/edit-group/:groupId',
        '/profile/create-group',
        '/profile/bonus-program'
      ].every(mask => mask !== currentPath);
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

      // TODO: bonus program loading state

      /* eslint-disable no-fallthrough, no-case-declarations */

      switch (this.currentPath) {
        case '/profile/my-music':
          // eslint-disable-next-line no-case-declarations
          const {
            tracks: myTracks,
            albums: myAlbums,
            collections: myCollections,
          } = getters['loading/music'];

          return !(
            myTracks.initialized
            && myAlbums.initialized
            && myCollections.initialized
          );

        case '/profile/favourite':
          const {
            tracks: favouriteTracks,
            albums: favouriteAlbums,
            collections: favouriteCollections,
            sets: favouriteSets
          } = getters['loading/favourite'];

          return !(
            favouriteTracks.initialized
            && favouriteAlbums.initialized
            && favouriteCollections.initialized
            && favouriteSets.initialized
          );

        case '/profile/edit':
          return !getters['loading/editProfile'].initialized;

        case '/profile/edit-group':
          return !getters['loading/editGroup'].initialized;

        case '/profile/my-music/albums':
          return !getters['loading/music'].albums.initialized;

        case '/profile/my-music/playlists':
          return !getters['loading/music'].collections.initialized;

        case '/profile/favourite/albums':
          return !getters['loading/favourite'].albums.initialized;

        case '/profile/favourite/playlists':
          return !getters['loading/favourite'].collections.initialized;

        case '/profile/favourite/sets':
          return !getters['loading/favourite'].sets.initialized;

        default:
          return false;
      }

      /* eslint-disable no-fallthrough */
    },

    loggedIn() {
      return this.$store.getters['profile/loggedIn'];
    },

    ableToPerform() {
      return this.$store.getters['profile/ableToPerform'];
    },

    ableToComment() {
      return this.$store.getters['profile/ableToComment'];
    }
  },

  watch: {
    loggedIn: {
      handler(val) {
        if (val !== true) return;

        // TODO: add possibility to visit review requests page

        const { ableToPerform, ableToComment } = this;
        const profilePathSection = this.currentPath.split('/')[2];

        if (!ableToComment && profilePathSection === 'my-reviews') {
          this.$router.push('/profile/my-music');

          return;
        }

        if (!ableToPerform && profilePathSection !== 'my-reviews') {
          this.$router.push('/profile/my-reviews');
        }
      },
      immediate: true
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyProfileLayout.scss"
/>

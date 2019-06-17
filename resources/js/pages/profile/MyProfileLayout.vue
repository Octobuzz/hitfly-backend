<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <div
        v-show="showFirstLoader"
        class="profile__user-card-loader"
      >
        loading user card...
      </div>

      <MyUserCard
        v-show="!showFirstLoader && renderUserCard"
        :item-container-class="itemContainerClass"
      />
    </template>
    <template #right-column="{ paddingClass }">
      <div
        v-if="renderNavBar"
        :class="['profile__nav-wrapper', paddingClass]"
      >
        <ul class="profile__nav">
          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': $route.fullPath === '/profile/my-music' }
            ]"
          >
            <router-link to="/profile/my-music">
              Моя музыка
            </router-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': $route.fullPath === '/profile/favourite' }
            ]"
          >
            <router-link to="/profile/favourite">
              Мне нравится
            </router-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': $route.fullPath === '/profile/reviews' }
            ]"
          >
            <router-link to="/profile/reviews">
              Отзывы
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

      <div v-show="showSecondLoader">
        loading...
      </div>

      <router-view v-show="!showSecondLoader" />
    </template>
  </AppColumns>
</template>

<script>
import AppColumns from 'components/layout/AppColumns.vue';
import PageHeader from 'components/PageHeader.vue';
import MyUserCard from './MyUserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    AppColumns,
    PageHeader,
    MyUserCard
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    renderNavBar() {
      const { $route: { fullPath } } = this;

      return !/(edit|create|bonus|tracks|albums|playlists|sets)/.test(fullPath);
    },

    renderUserCard() {
      const { desktop, $route: { fullPath } } = this;

      return desktop || !/(edit|create|bonus)/.test(fullPath);
    },

    showFirstLoader() {
      return !this.$store.getters['loading/userCard'].initialized;
    },

    showSecondLoader() {
      // TODO: if (this.windowWidth <= MOBILE_WIDTH) { ... }

      const { getters } = this.$store;
      const { fullPath } = this.$route;
      const trimmedPath = fullPath.split('/').slice(0, 3).join('/');

      /* eslint-disable no-fallthrough */

      // eslint-disable-next-line default-case
      switch (fullPath) {
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
      }

      switch (trimmedPath) {
        case '/profile/edit':
          return !getters['loading/editProfile'].initialized;

        case '/profile/edit-group':
          return !getters['loading/editGroup'].initialized;

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
          // eslint-disable-next-line no-case-declarations
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
>
@import '~scss/_variables.scss';

$tab_width_desktop: 208px;
$tab_width_mobile: 150px;

.profile__nav {
  display: flex;
  min-width: 100%;
  border-bottom: 1px solid $layout_border_color;
  padding: 0;
  margin: 18px 0 24px;
  list-style: none;
}

.profile__nav-endpoint {
  min-width: $tab_width_mobile;
  width: $tab_width_desktop;
  cursor: pointer;
  margin-bottom: -4px;
  border-bottom: 4px solid transparent;
  font-size: 20px;
  text-align: center;
  transition: all .3s;

  &_active {
    border-bottom: 4px solid #b36fcb;
    font-family: 'Gotham Pro Ton', sans-serif;
  }

  &::v-deep a {
    color: #313131;
    display: block;
    width: 100%;
    padding: 20px 0;
  }
}

.profile__nav-scroll-cloak {
  display: none;
  background: white;
  position: absolute;
  width: 100%;
  height: 20px;
  padding: 0;
  top: 100%;
}

.profile__page-header {
  margin-bottom: 8px;

  @media screen and (min-width: 1025px) {
    .main__profile {
      box-sizing: border-box;
      width: 68.2%;
    }
  }
}

.profile__user-card-loader {
  height: calc(100vh - #{$header_height_desktop} - #{$footer_height_desktop});
}

@media screen and (max-width: 767px) {
  .profile__nav-wrapper {
    padding-right: 0 !important;
    overflow-x: scroll;
  }

  .profile__nav {
    // TODO: make x4 whenever statistics is ready
    width: $tab_width_mobile * 3;
    margin: 8px 16px 18px 0;
  }

  .profile__nav-endpoint::v-deep a {
    font-size: 18px;
  }
}

@media screen and (max-width: 576px) {
  .main__profile {
    padding: {
      left: 16px;
      right: 16px;
    }
  }
}
</style>

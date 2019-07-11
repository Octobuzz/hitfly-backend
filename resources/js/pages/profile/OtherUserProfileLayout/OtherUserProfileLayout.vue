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
      <template v-if="userRolesFetched">
        <div
          v-if="renderNavBar"
          :class="['profile__nav-wrapper', paddingClass]"
        >
          <ul class="profile__nav">
            <template v-if="ableToPerform">
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
            </template>

            <template v-if="ableToComment">
              <li
                :class="[
                  'profile__nav-endpoint',
                  'profile__nav-endpoint_long',
                  { 'profile__nav-endpoint_active': currentPath === '/user/:userId/user-reviews' }
                ]"
              >
                <router-link :to="`/user/${userId}/user-reviews`">
                  Оставленные отзывы
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
          <span class="profile__status">
            {{ status === null ? '' : status }}
          </span>
        </PageHeader>

        <router-view v-show="!showSecondLoader" />
      </template>

      <div
        v-show="showSecondLoader || !userRolesFetched"
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
import OtherUserCard from '../OtherUserCard';
import gql from './gql';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    SpinnerLoader,
    AppColumns,
    PageHeader,
    OtherUserCard
  },

  mixins: [currentPath],

  data() {
    return {
      userRolesFetched: false,
      ableToPerform: false,
      ableToComment: false,
      status: null
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    userId() {
      return +this.$route.params.userId;
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
      } = this.$store.getters['loading/userCard'];

      return !personalInfo.initialized;
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
            newAlbum
          } = getters['loading/music'];

          return !(
            tracks.initialized
            && albums.initialized
            && collections.initialized
            && newAlbum.initialized
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
  },

  apollo: {
    userRoles: {
      query: gql.query.USER_ROLES,

      fetchPolicy: 'no-cache',

      variables() {
        return { id: this.userId };
      },

      update({ user }) {
        if (user === null) {
          // TODO: redirect to 404 page
        }

        this.userRolesFetched = true;

        const roles = user.roles.map(role => role.slug);

        this.ableToPerform = ['listener', 'performer', 'critic']
          .some(role => roles.includes(role));

        this.ableToComment = ['critic', 'prof_critic', 'star']
          .some(role => roles.includes(role));

        const statuses = {
          listener: {
            priority: 1,
            title: 'слушатель'
          },
          performer: {
            priority: 2,
            title: 'исполнитель'
          },
          critic: {
            priority: 3,
            title: 'критик'
          },
          prof_critic: {
            priority: 4,
            title: 'критик'
          },
          star: {
            priority: 5,
            title: 'звезда'
          }
        };

        const topStatus = roles.reduce((status, role) => {
          if (statuses[status].priority < statuses[role].priority) {
            return role;
          }
          return status;
        }, 'listener');

        this.status = statuses[topStatus].title;
      },

      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="../MyProfileLayout/MyProfileLayout.scss"
/>

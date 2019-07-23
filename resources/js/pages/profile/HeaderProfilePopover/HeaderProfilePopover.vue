<template>
  <v-popover
    popover-base-class="header-profile-popover"
    popover-wrapper-class="header-profile-popover__wrapper"
    popover-inner-class="header-profile-popover__inner"
    popover-arrow-class="header-profile-popover__arrow"
    placement="bottom-center"
    :auto-hide="true"
    @show="fetchAllowed = true"
  >
    <slot />

    <template #popover>
      <SpinnerLoaderWrapper :is-loading="isFetching" size="small">
        <span class="header-profile-popover__header">
          {{ myProfile.username }}
        </span>

        <hr class="header-profile-popover__delimiter">

        <router-link
          to="/profile/edit"
          class="header-profile-popover__link"
        >
          Мой профиль
        </router-link>

        <router-link
          v-if="myMusicLink"
          :to="myMusicLink"
          class="header-profile-popover__link"
        >
          Моя музыка
        </router-link>

        <router-link
          v-if="reviewsLink"
          :to="reviewsLink"
          class="header-profile-popover__link"
        >
          Мои отзывы
        </router-link>

        <router-link
          to="/logout"
          class="header-profile-popover__link header-profile-popover__link_top-padded"
        >
          Выйти
        </router-link>
      </SpinnerLoaderWrapper>
    </template>
  </v-popover>
</template>

<script>
import { mapGetters } from 'vuex';
import SpinnerLoaderWrapper from 'components/SpinnerLoaderWrapper.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoaderWrapper
  },

  data() {
    return {
      myProfile: {},
      fetchAllowed: false
    };
  },

  computed: {
    isFetching() {
      return this.$apollo.queries.myProfile.loading;
    },

    myMusicLink() {
      const { roles } = this.myProfile;

      if (!roles) return null;

      const isNotProfCriticOrStar = this.myProfile.roles
        .every(({ slug }) => (
          slug !== 'professional_critic' && slug !== 'star'
        ));
      if (isNotProfCriticOrStar) {
        return '/profile/my-music';
      }

      return null;
    },

    reviewsLink() {
      const { roles } = this.myProfile;

      if (!roles) return null;

      const isProfCriticOrStar = roles.some(({ slug }) => (
        slug === 'professional_critic' || slug === 'star'
      ));
      if (isProfCriticOrStar) {
        return '/profile/my-reviews';
      }

      const isPerformer = roles.some(({ slug }) => (
        slug === 'performer'
      ));
      if (isPerformer) {
        return '/profile/reviews';
      }

      return null;
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  apollo: {
    myProfile: {
      $client() {
        return this.apolloClient;
      },
      query: gql.query.MY_PROFILE,
      update({ myProfile }) {
        return myProfile;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !this.isAuthenticated || !this.fetchAllowed;
      }
    }
  }
};
</script>

<style
  lang="scss"
  src="./HeaderProfilePopover.scss"
/>
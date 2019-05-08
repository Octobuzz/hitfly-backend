<template>
  <TwoColumnLayout>
    <template #left-column="{ itemContainerClass }">
      <UserCard
        v-if="renderUserCard"
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
        </ul>
      </div>

      <PageHeader
        v-if="renderNavBar"
        :class="['profile__page-header', paddingClass]"
      >
        ПРОФИЛЬ
      </PageHeader>

      <router-view :container-padding-class="paddingClass" />
    </template>
  </TwoColumnLayout>
</template>

<script>
import TwoColumnLayout from 'components/TwoColumnLayout.vue';
import PageHeader from 'components/PageHeader.vue';
import UserCard from './UserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    TwoColumnLayout,
    PageHeader,
    UserCard
  },
  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    renderNavBar() {
      const { $route: { fullPath } } = this;

      return fullPath !== '/profile/edit'
        && fullPath !== '/profile/update-group'
        && fullPath !== '/profile/create-group';
    },

    renderUserCard() {
      const { desktop, $route: { fullPath } } = this;

      return desktop || (
        fullPath !== '/profile/edit'
          && fullPath !== '/profile/update-group'
          && fullPath !== '/profile/create-group'
      );
    }
  }
};
</script>

<style
  scoped
  lang="sass"
  src="./ProfilePage.sass"
/>

<template>
  <AppColumns>
    <template #left-column="{ itemContainerClass }">
      <UserCard
        v-show="renderUserCard"
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
            <nuxt-link to="/profile/my-music">
              Моя музыка
            </nuxt-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': $route.fullPath === '/profile/favourite' }
            ]"
          >
            <nuxt-link to="/profile/favourite">
              Мне нравится
            </nuxt-link>
          </li>

          <li
            :class="[
              'profile__nav-endpoint',
              { 'profile__nav-endpoint_active': $route.fullPath === '/profile/reviews' }
            ]"
          >
            <nuxt-link to="/profile/reviews">
              Отзывы
            </nuxt-link>
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

      <nuxt-child />
    </template>
  </AppColumns>
</template>

<script>
import AppColumns from '~/components/layout/AppColumns.vue';
import PageHeader from '~/components/shared/PageHeader.vue';
import UserCard from '~/components/profile/UserCard';

const MOBILE_WIDTH = 1024;

export default {
  components: {
    AppColumns,
    PageHeader,
    UserCard
  },
  computed: {
    desktop() {
      return process.server ? true : this.windowWidth > MOBILE_WIDTH;
    },

    renderNavBar() {
      const { $route: { fullPath } } = this;

      return !/(edit|create)/.test(fullPath);
    },

    renderUserCard() {
      const { desktop, $route: { fullPath } } = this;

      return desktop || !/(edit|create)/.test(fullPath);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '@/assets/scss/_variables.scss';

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

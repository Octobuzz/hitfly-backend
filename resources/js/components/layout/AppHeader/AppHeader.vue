<template>
  <header :class="['head', $attrs.class]">
    <div class="head__left">
      <span class="menu-call is-close">
        <span class="menu-call__rows" />
      </span>
      <router-link to="/" class="head__logo">
        <img :src="logo" alt="logo">
      </router-link>
    </div>

    <nav class="head__nav">
      <router-link
        to="/"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/' }
        ]"
      >
        Главная
      </router-link>
      <router-link
        to="/"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/sets' }
        ]"
      >
        Подборки
      </router-link>
      <router-link
        to="/about"
        :class="[
          'button',
          'head-nav-item',
          { active: path === '/about' }
        ]"
      >
        О нас
      </router-link>
    </nav>

    <div class="head__right">
      <router-link
        to="/upload"
        class="button gradient head-right-item head-right-item_upload"
      >
        Загрузить музыку
      </router-link>

      <span class="head-right-item app-header__loupe-icon">
        <IconButton>
          <LoupeIcon />
        </IconButton>
      </span>

      <span class="head-right-item">
        <NotificationButtonWithPopover />
      </span>

      <img
        class="head__profile head-right-item"
        :src="myProfile.avatar || anonymousAvatar"
        alt="User avatar"
        @click="goToProfilePage"
      >
    </div>

    <div class="drop-menu">
      <div class="drop-menu-list">
        <div class="drop-menu-list__item drop-menu-list__item_upload">
          <router-link to="/upload" class="button drop-menu-list__button-upload">
            <img :src="logo" alt="logo">
          </router-link>
        </div>
        <router-link to="/profile/bonus-program" class="drop-menu-list__item">
          <span>
            Бонусная программа
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Мои покупки
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Вопросы и ответы
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Истории победителей
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Звезды
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Настройки
          </span>
        </router-link>
        <router-link to="/" class="drop-menu-list__item">
          <span>
            Форма обратной связи
          </span>
        </router-link>
      </div>
    </div>
  </header>
</template>

<script>
import logo from 'images/logo.svg';
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import LoupeIcon from 'components/icons/LoupeIcon.vue';
import NotificationButtonWithPopover from 'components/notifications/NotificationButtonWithPopover';
import gql from './gql';

export default {
  components: {
    IconButton,
    LoupeIcon,
    NotificationButtonWithPopover
  },

  data() {
    return {
      logo,
      anonymousAvatar,
      myProfile: {
        avatar: ''
      }
    };
  },

  computed: {
    path() {
      return this.$route.fullPath;
    }
  },

  methods: {
    goToProfilePage() {
      const { getters } = this.$store;

      if (!getters['profile/loggedIn']) return;

      if (getters['profile/ableToPerform']) {
        this.$router.push('/profile/my-music');

        return;
      }

      if (getters['profile/ableToComment']) {
        this.$router.push('/profile/my-reviews');
      }
    }
  },

  apollo: {
    myProfile: {
      query: gql.query.MY_PROFILE,
      update({ myProfile }) {
        this.$store.commit('profile/setLoggedIn', true);

        this.$store.commit(
          'profile/setRoles',
          myProfile.roles.map(role => role.slug)
        );

        this.$store.commit('profile/setMyId', myProfile.id);
        this.$store.commit('profile/setLoggedIn', true);

        const avatar = myProfile.avatar
          .filter(av => av.size === 'size_56x56')[0].url;

        return {
          avatar
        };
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
  src="../../../../sass/app.scss"
/>

<style scoped>
.app-header__loupe-icon::v-deep svg {
  fill: transparent !important;
}
</style>
